<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-shopping-cart"></i> Shopping Cart</h1>
        <p>Review your order before checkout</p>
    </div>
</div>

<section class="cart-page">
    <div class="container">
        <?php if(empty($cart_items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your Cart is Empty</h2>
                <p>Add some delicious pizzas to get started!</p>
                <a href="?page=menu" class="btn btn-primary btn-lg"><i class="fas fa-utensils"></i> Browse Menu</a>
            </div>
        <?php else:
            $subtotal = 0;
            foreach($cart_items as $item) $subtotal += $item['price'] * $item['quantity'];
            $delivery = $subtotal >= 499 ? 0 : 49;
            $gst = $subtotal * 0.05;
            $total = $subtotal + $delivery + $gst;
        ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <h3><i class="fas fa-list"></i> Cart Items (<?= count($cart_items) ?>)</h3>
                    <?php foreach($cart_items as $item): $it = $item['price'] * $item['quantity']; ?>
                        <div class="cart-item" data-cart-id="<?= $item['id'] ?>">
                            <div class="cart-item-image">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="cart-item-details">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <p><i class="fas fa-expand-arrows-alt"></i> <?= htmlspecialchars($item['size_name']) ?></p>
                                <p>₹<?= number_format($item['price'],0) ?> each</p>
                                <div class="quantity-controls">
                                    <button class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] ?>, -1)"><i class="fas fa-minus"></i></button>
                                    <span class="quantity"><?= $item['quantity'] ?></span>
                                    <button class="quantity-btn" onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] ?>, 1)"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="cart-item-price">
                                <div class="price">₹<?= number_format($it,0) ?></div>
                                <button class="remove-item" onclick="removeItem(<?= $item['id'] ?>)"><i class="fas fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                    <div class="summary-row"><span>Subtotal</span><span>₹<?= number_format($subtotal,0) ?></span></div>
                    <div class="summary-row"><span>Delivery</span><span><?= $delivery ? '₹'.$delivery : '<span style="color:#28a745">FREE</span>' ?></span></div>
                    <div class="summary-row"><span>GST (5%)</span><span>₹<?= number_format($gst,0) ?></span></div>
                    <div class="summary-row total"><span>Total</span><span>₹<?= number_format($total,0) ?></span></div>

                    <?php if($subtotal < 499): ?>
                    <div class="info-banner"><i class="fas fa-info-circle"></i> Add ₹<?= number_format(499-$subtotal,0) ?> more for FREE delivery!</div>
                    <?php endif; ?>

                    <div style="margin-top:24px;">
                        <h4 style="margin-bottom:16px;"><i class="fas fa-map-marker-alt"></i> Delivery Details</h4>
                        <form id="orderForm" onsubmit="placeOrder(event)">
                            <input type="text" name="name" placeholder="Full Name" required class="form-input">
                            <input type="email" name="email" placeholder="Email Address" required class="form-input">
                            <input type="tel" name="phone" placeholder="Phone Number" required class="form-input">
                            <textarea name="address" placeholder="Delivery Address" required class="form-input" style="min-height:80px;resize:vertical;"></textarea>
                            <input type="hidden" name="total" value="<?= $total ?>">
                            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px;">
                                <i class="fas fa-check-circle"></i> Place Order — ₹<?= number_format($total,0) ?>
                            </button>
                        </form>
                    </div>
                    <div class="secure-badge"><i class="fas fa-shield-alt"></i> <strong>100% Secure</strong> — Your info is protected</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function updateQuantity(id, cur, d) {
    const nq = cur + d; if(nq < 1) return;
    fetch('?action=update_cart', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'cart_id='+id+'&quantity='+nq})
    .then(r=>r.json()).then(data => { if(data.success) location.reload(); });
}

function removeItem(id) {
    if(!confirm('Remove this item?')) return;
    fetch('?action=remove_from_cart', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'cart_id='+id})
    .then(r=>r.json()).then(data => { if(data.success) location.reload(); });
}

function placeOrder(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...'; btn.disabled = true;
    fetch('?action=place_order', {method:'POST',body:new FormData(e.target)})
    .then(r=>r.json()).then(data => {
        if(data.success) {
            alert('🎉 Order placed! Order ID: #'+data.order_id+'\n\nYou\'ll receive a confirmation email. Thank you!');
            window.location.href = '?';
        } else { alert('Failed. Please try again.'); btn.innerHTML=orig; btn.disabled=false; }
    }).catch(() => { alert('Error. Please try again.'); btn.innerHTML=orig; btn.disabled=false; });
}
</script>