<?php
if (class_exists('Cart')) return;
class Cart {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function add($uid, $sid, $pid, $size_id, $qty) {
        // Check if same product+size already exists — if so, increment quantity
        $cond  = $uid ? "user_id = :key" : "session_id = :key";
        $check = $this->conn->prepare(
            "SELECT id, quantity FROM cart WHERE product_id = :pid AND size_id = :size AND $cond"
        );
        $check->execute([':pid' => $pid, ':size' => $size_id, ':key' => $uid ?: $sid]);
        $existing = $check->fetch();

        if ($existing) {
            return $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?")
                              ->execute([$existing['quantity'] + (int)$qty, $existing['id']]);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO cart (user_id, session_id, product_id, size_id, quantity) VALUES (?,?,?,?,?)"
        );
        return $stmt->execute([$uid, $sid, $pid, $size_id, (int)$qty]);
    }

    public function getItems($uid, $sid) {
        $cond = $uid ? "c.user_id = :key" : "c.session_id = :key";
        $stmt = $this->conn->prepare(
            "SELECT c.*, p.name, p.image, ps.size_name, ps.price
             FROM cart c
             LEFT JOIN products p  ON c.product_id = p.id
             LEFT JOIN product_sizes ps ON c.size_id = ps.id
             WHERE $cond"
        );
        $stmt->execute([':key' => $uid ?: $sid]);
        return $stmt->fetchAll();
    }

    public function updateQuantity($cart_id, $qty) {
        return $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?")
                          ->execute([(int)$qty, $cart_id]);
    }

    public function remove($cart_id) {
        return $this->conn->prepare("DELETE FROM cart WHERE id = ?")
                          ->execute([$cart_id]);
    }

    public function getCount($uid, $sid) {
        $cond = $uid ? "user_id = :key" : "session_id = :key";
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(quantity), 0) as cnt FROM cart WHERE $cond"
        );
        $stmt->execute([':key' => $uid ?: $sid]);
        return (int)($stmt->fetch()['cnt'] ?? 0);
    }

    public function clear($uid, $sid) {
        $cond = $uid ? "user_id = :key" : "session_id = :key";
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE $cond");
        $stmt->execute([':key' => $uid ?: $sid]);
    }

    /**
     * On login/signup: move guest session cart items into the user's account.
     * If the user already has the same product+size, add quantities together.
     * Then delete the guest rows.
     */
    public function mergeGuestCart($guest_session, $user_id) {
        // Get all guest cart items
        $stmt = $this->conn->prepare(
            "SELECT product_id, size_id, quantity FROM cart WHERE session_id = ? AND user_id IS NULL"
        );
        $stmt->execute([$guest_session]);
        $guest_items = $stmt->fetchAll();

        foreach ($guest_items as $item) {
            // Check if user already has this product+size
            $check = $this->conn->prepare(
                "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size_id = ?"
            );
            $check->execute([$user_id, $item['product_id'], $item['size_id']]);
            $existing = $check->fetch();

            if ($existing) {
                // Merge: add quantities
                $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?")
                           ->execute([$existing['quantity'] + $item['quantity'], $existing['id']]);
            } else {
                // Assign guest row to this user
                $this->conn->prepare(
                    "UPDATE cart SET user_id = ?, session_id = NULL WHERE user_id IS NULL AND session_id = ? AND product_id = ? AND size_id = ?"
                )->execute([$user_id, $guest_session, $item['product_id'], $item['size_id']]);
            }
        }

        // Remove any remaining guest rows for this session
        $this->conn->prepare("DELETE FROM cart WHERE session_id = ? AND user_id IS NULL")
                   ->execute([$guest_session]);
    }
}
?>