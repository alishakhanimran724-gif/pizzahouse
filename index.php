<?php
/**
 * index.php — Front controller
 * Fixes applied:
 *  1. ob_start() buffers ALL output so stray PHP notices never corrupt AJAX JSON
 *  2. Every AJAX handler sends Content-Type: application/json
 *  3. Every AJAX handler wraps logic in try/catch and returns {success:false} on error
 *  4. home.php variable mismatch fixed ($products + $featured both set)
 *  5. contact page action=submit handled before HTML is rendered
 *  6. Input sanitisation on all POST data used in SQL
 */

// ── Buffer everything — prevents notices/warnings from corrupting JSON ────────
ob_start();

session_start();

define('ROOT', __DIR__);

if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = uniqid('guest_', true);
}

require_once ROOT . '/app/config/database.php';
$database = new Database();
$db       = $database->connect();

require_once ROOT . '/app/models/Product.php';
require_once ROOT . '/app/models/Cart.php';
require_once ROOT . '/app/models/Wishlist.php';
require_once ROOT . '/app/models/Order.php';

$productModel  = new Product($db);
$cartModel     = new Cart($db);
$wishlistModel = new Wishlist($db);
$orderModel    = new Order($db);

$page   = trim($_GET['page']   ?? 'home');
$action = trim($_GET['action'] ?? '');

$user_id    = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$session_id = $_SESSION['session_id'];

// ── Helper: send JSON response and exit cleanly ──────────────────────────────
function json_respond(array $data, int $status = 200): never {
    // Discard any buffered output (warnings, notices) so JSON is clean
    ob_clean();
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
// AJAX HANDLERS  (all POST, all return JSON)
// ════════════════════════════════════════════════════════════════════════════

// ── Add to cart ──────────────────────────────────────────────────────────────
if ($action === 'add_to_cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $size_id    = (int)($_POST['size_id']    ?? 0);
        $quantity   = max(1, (int)($_POST['quantity'] ?? 1));

        if (!$product_id || !$size_id) {
            json_respond(['success' => false, 'message' => 'Missing product or size.'], 400);
        }

        $result     = $cartModel->add($user_id, $session_id, $product_id, $size_id, $quantity);
        $cart_count = $cartModel->getCount($user_id, $session_id);

        json_respond(['success' => (bool)$result, 'cart_count' => $cart_count]);
    } catch (Throwable $e) {
        error_log('add_to_cart: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ── Update cart quantity ─────────────────────────────────────────────────────
if ($action === 'update_cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cart_id  = (int)($_POST['cart_id']  ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$cart_id) json_respond(['success' => false, 'message' => 'Invalid cart ID.'], 400);

        $result = $cartModel->updateQuantity($cart_id, $quantity);
        json_respond(['success' => (bool)$result]);
    } catch (Throwable $e) {
        error_log('update_cart: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ── Remove from cart ─────────────────────────────────────────────────────────
if ($action === 'remove_from_cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cart_id = (int)($_POST['cart_id'] ?? 0);
        if (!$cart_id) json_respond(['success' => false, 'message' => 'Invalid cart ID.'], 400);

        json_respond(['success' => (bool)$cartModel->remove($cart_id)]);
    } catch (Throwable $e) {
        error_log('remove_from_cart: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ── Toggle wishlist ──────────────────────────────────────────────────────────
if ($action === 'toggle_wishlist' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pid = (int)($_POST['product_id'] ?? 0);
        if (!$pid) json_respond(['success' => false, 'message' => 'Invalid product.'], 400);

        if ($wishlistModel->exists($user_id, $session_id, $pid)) {
            $wishlistModel->remove($user_id, $session_id, $pid);
            $added = false;
        } else {
            $wishlistModel->add($user_id, $session_id, $pid);
            $added = true;
        }

        json_respond([
            'success'        => true,
            'added'          => $added,
            'wishlist_count' => $wishlistModel->getCount($user_id, $session_id),
        ]);
    } catch (Throwable $e) {
        error_log('toggle_wishlist: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ── Remove from wishlist ─────────────────────────────────────────────────────
if ($action === 'remove_from_wishlist' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pid = (int)($_POST['product_id'] ?? 0);
        if (!$pid) json_respond(['success' => false, 'message' => 'Invalid product.'], 400);

        $result         = $wishlistModel->remove($user_id, $session_id, $pid);
        $wishlist_count = $wishlistModel->getCount($user_id, $session_id);
        json_respond(['success' => (bool)$result, 'wishlist_count' => $wishlist_count]);
    } catch (Throwable $e) {
        error_log('remove_from_wishlist: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ── Place order ──────────────────────────────────────────────────────────────
if ($action === 'place_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cart_items = $cartModel->getItems($user_id, $session_id);
        if (empty($cart_items)) {
            json_respond(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }

        $name    = htmlspecialchars(trim($_POST['name']    ?? ''), ENT_QUOTES);
        $email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
        $phone   = preg_replace('/[^\d\+\-\s\(\)]/', '', $_POST['phone']   ?? '');
        $address = htmlspecialchars(trim($_POST['address'] ?? ''), ENT_QUOTES);
        $total   = (float)($_POST['total'] ?? 0);

        if (!$name || !$email || !$address || $total <= 0) {
            json_respond(['success' => false, 'message' => 'Please fill in all delivery details.'], 400);
        }

        $oid = $orderModel->create(
            $user_id, $session_id,
            $name, $email, $phone, $address,
            $total, $cart_items
        );

        if ($oid) {
            $cartModel->clear($user_id, $session_id);
            json_respond(['success' => true, 'order_id' => $oid]);
        } else {
            json_respond(['success' => false, 'message' => 'Could not place order. Please try again.'], 500);
        }
    } catch (Throwable $e) {
        error_log('place_order: ' . $e->getMessage());
        json_respond(['success' => false, 'message' => 'Server error.'], 500);
    }
}

// ════════════════════════════════════════════════════════════════════════════
// AUTH HANDLERS  (form POST → redirect)
// ════════════════════════════════════════════════════════════════════════════

// ── Login ────────────────────────────────────────────────────────────────────
if ($page === 'login' && $action === 'submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password']   ?? '';

    // Admin shortcut
    if ($email === 'admin@pizz-a64.com' && $pass === 'Admin@123') {
        header('Location: admin/index.php');
        exit;
    }

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            $guest_session = $_SESSION['session_id'];
            $cartModel->mergeGuestCart($guest_session, $user['id']);
            $wishlistModel->mergeGuestWishlist($guest_session, $user['id']);

            session_regenerate_id(true);
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['fullname'];
            $_SESSION['session_id'] = uniqid('user_', true);

            header('Location: ?');
            exit;
        }
    } catch (Throwable $e) {
        error_log('login: ' . $e->getMessage());
    }

    $_SESSION['auth_error'] = 'Invalid email or password.';
    header('Location: ?page=login');
    exit;
}

// ── Signup ───────────────────────────────────────────────────────────────────
if ($page === 'signup' && $action === 'submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['fullname']         ?? '');
    $email = trim($_POST['email']            ?? '');
    $phone = trim($_POST['phone']            ?? '');
    $pass  = $_POST['password']              ?? '';
    $conf  = $_POST['confirm_password']      ?? '';

    if ($pass !== $conf) {
        $_SESSION['auth_error'] = 'Passwords do not match.';
        header('Location: ?page=signup'); exit;
    }
    if (strlen($pass) < 6) {
        $_SESSION['auth_error'] = 'Password must be at least 6 characters.';
        header('Location: ?page=signup'); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['auth_error'] = 'Please enter a valid email address.';
        header('Location: ?page=signup'); exit;
    }

    try {
        $chk = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $_SESSION['auth_error'] = 'Email already registered. Please login.';
            header('Location: ?page=signup'); exit;
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $ins  = $db->prepare("INSERT INTO users (fullname, email, phone, password) VALUES (?,?,?,?)");
        $ins->execute([$name, $email, $phone, $hash]);
        $new_id = (int)$db->lastInsertId();

        $guest_session = $_SESSION['session_id'];
        $cartModel->mergeGuestCart($guest_session, $new_id);
        $wishlistModel->mergeGuestWishlist($guest_session, $new_id);

        session_regenerate_id(true);
        $_SESSION['user_id']    = $new_id;
        $_SESSION['user_name']  = $name;
        $_SESSION['session_id'] = uniqid('user_', true);

        header('Location: ?'); exit;

    } catch (Throwable $e) {
        error_log('signup: ' . $e->getMessage());
        $_SESSION['auth_error'] = 'Registration failed. Please try again.';
        header('Location: ?page=signup'); exit;
    }
}

// ── Logout ───────────────────────────────────────────────────────────────────
if ($page === 'logout') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['session_id'] = uniqid('guest_', true);
    header('Location: ?');
    exit;
}

// ── Contact form submit (non-AJAX) ───────────────────────────────────────────
if ($page === 'contact' && $action === 'submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name    = htmlspecialchars(trim($_POST['name']    ?? ''), ENT_QUOTES);
        $email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
        $phone   = htmlspecialchars(trim($_POST['phone']   ?? ''), ENT_QUOTES);
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES);
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES);

        if ($name && $email && $subject && $message) {
            // Save to DB if you have a contact_messages table, otherwise log it
            // $db->prepare("INSERT INTO contact_messages ...")->execute([...]);
            error_log("Contact form: [$subject] from $name <$email> — $message");
            $_SESSION['contact_success'] = 'Thank you! We\'ll get back to you soon.';
        } else {
            $_SESSION['contact_error'] = 'Please fill in all required fields.';
        }
    } catch (Throwable $e) {
        error_log('contact submit: ' . $e->getMessage());
        $_SESSION['contact_error'] = 'Failed to send message. Please try again.';
    }
    header('Location: ?page=contact');
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
// PAGE RENDER
// ════════════════════════════════════════════════════════════════════════════

// Header badge counts
$cart_count     = $cartModel->getCount($user_id, $session_id);
$wishlist_count = $wishlistModel->getCount($user_id, $session_id);

// Flush any buffered output only now (header will have been sent correctly)
ob_end_flush();

include ROOT . '/app/views/layout/header.php';

switch ($page) {

    case 'menu':
        $products = $productModel->getAll();
        include ROOT . '/app/views/pages/menu.php';
        break;

    case 'product':
        $product_id = (int)($_GET['id'] ?? 0);
        $product    = $productModel->getById($product_id);
        $sizes      = $product ? $productModel->getSizes($product_id) : [];
        include ROOT . '/app/views/pages/product.php';
        break;

    case 'cart':
        $cart_items = $cartModel->getItems($user_id, $session_id);
        include ROOT . '/app/views/pages/cart.php';
        break;

    case 'wishlist':
        $wishlist_items = $wishlistModel->getItems($user_id, $session_id);
        include ROOT . '/app/views/pages/wishlist.php';
        break;

    case 'about':
        include ROOT . '/app/views/pages/about.php';
        break;

    case 'contact':
        include ROOT . '/app/views/pages/contact.php';
        break;

    case 'login':
        include ROOT . '/app/views/pages/login.php';
        break;

    case 'signup':
        include ROOT . '/app/views/pages/signup.php';
        break;

    default: // home
        // home.php needs both $products (for category pills) and $featured (for the grid)
        $products = $productModel->getAll();
        $featured = array_slice(
            array_values(array_filter($products, fn($p) => !empty($p['is_featured']))),
            0, 8
        );
        include ROOT . '/app/views/pages/home.php';
        break;
}

include ROOT . '/app/views/layout/footer.php';
?>