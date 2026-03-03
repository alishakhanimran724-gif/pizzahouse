<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-heart"></i> My Wishlist</h1>
        <p>Your favourite pizzas, saved for later</p>
    </div>
</div>

<section class="wishlist-page">
    <div class="container">
        <?php if(empty($wishlist_items)): ?>
            <div class="empty-wishlist">
                <i class="far fa-heart"></i>
                <h2>Your Wishlist is Empty</h2>
                <p>Start adding your favourite pizzas!</p>
                <a href="?page=menu" class="btn btn-primary btn-lg"><i class="fas fa-utensils"></i> Browse Menu</a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach($wishlist_items as $item): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
                            <?php if($item['is_veg']): ?>
                                <div class="product-badges"><span class="badge veg"><i class="fas fa-leaf"></i> VEG</span></div>
                            <?php endif; ?>
                            <button class="wishlist-btn active" onclick="removeFromWishlist(<?= $item['product_id'] ?>, this.closest('.product-card'))"><i class="fas fa-heart"></i></button>
                        </div>
                        <div class="product-info">
                            <span class="product-category"><?= htmlspecialchars($item['category']) ?></span>
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p><?= htmlspecialchars(substr($item['description'],0,80)) ?>...</p>
                            <div class="product-price">₹<?= number_format($item['price'],0) ?> <span>onwards</span></div>
                            <div class="product-actions">
                                <a href="?page=product&id=<?= $item['id'] ?>" class="btn btn-outline"><i class="fas fa-eye"></i> View</a>
                                <a href="?page=product&id=<?= $item['id'] ?>" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Order</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align:center;margin-top:40px;">
                <a href="?page=menu" class="btn btn-outline"><i class="fas fa-utensils"></i> Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function removeFromWishlist(productId, card) {
    if(!confirm('Remove from wishlist?')) return;
    fetch('?action=remove_from_wishlist', {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'product_id='+productId})
    .then(r=>r.json()).then(data => { if(data.success) card.remove(); });
}
</script>