<?php if(!$product): ?>
<div class="container" style="padding:90px 0;text-align:center">
  <i class="fas fa-exclamation-circle" style="font-size:52px;color:var(--dim);margin-bottom:18px;display:block"></i>
  <h2 style="margin-bottom:8px">Pizza Not Found</h2>
  <p style="color:var(--dim);margin-bottom:28px">That pizza doesn't seem to exist in our menu.</p>
  <a href="?page=menu" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Menu</a>
</div>
<?php else: ?>
<div class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="?">Home</a> › <a href="?page=menu">Menu</a> › <?= htmlspecialchars($product['name']) ?></p>
  </div>
</div>

<section class="pd-section">
  <div class="container">
    <div class="pd-grid">

      <!-- Image -->
      <div>
        <img class="pd-img" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
      </div>

      <!-- Details -->
      <div class="pd-meta">
        <?php if($product['is_veg']): ?>
          <span class="badge veg" style="margin-bottom:14px"><i class="fas fa-leaf"></i> Vegetarian</span>
        <?php endif; ?>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="pd-cat-line"><i class="fas fa-tag" style="margin-right:5px;color:var(--a)"></i><?= htmlspecialchars($product['category']) ?></p>
        <div class="pd-desc"><p><?= htmlspecialchars($product['description']) ?></p></div>

        <!-- ADD TO CART form — wishlist is OUTSIDE this form -->
        <form id="acForm" onsubmit="doAddCart(event)">
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

          <div class="pd-sz">
            <span class="opt-lbl">Select Size</span>
            <div class="size-opts">
              <?php foreach($sizes as $i=>$s): ?>
              <label class="size-opt">
                <input type="radio" name="size_id" value="<?= $s['id'] ?>"
                  data-price="<?= $s['price'] ?>" <?= $i===0?'checked':'' ?>
                  onchange="calcPrice()">
                <strong style="margin-right:6px"><?= $s['size_name'] ?></strong>
                — ₹<?= number_format($s['price'],0) ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="pd-qtyblock">
            <span class="opt-lbl">Quantity</span>
            <div class="pd-qty-row">
              <div class="qty-ctrl">
                <button type="button" class="qty-btn" onclick="stepQ(-1)"><i class="fas fa-minus"></i></button>
                <span class="qty-n" id="qdisp">1</span>
                <button type="button" class="qty-btn" onclick="stepQ(1)"><i class="fas fa-plus"></i></button>
              </div>
              <input type="hidden" name="quantity" id="qval" value="1">
            </div>
          </div>

          <div class="pd-total">₹<span id="tprice">0</span></div>

          <div class="pd-actions">
            <button type="submit" id="cartBtn" class="btn btn-primary">
              <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
          </div>
        </form>

        <!-- WISHLIST — fully outside form, type="button" prevents form submit -->
        <div class="pd-actions" style="margin-top:12px">
          <button type="button" id="wlBtn" class="btn btn-outline"
            onclick="doWishlist(<?= (int)$product['id'] ?>, this)">
            <i class="far fa-heart"></i> Save to Wishlist
          </button>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', calcPrice);

function calcPrice(){
  var sel = document.querySelector('input[name="size_id"]:checked');
  if(!sel) return;
  var total = parseFloat(sel.dataset.price) * parseInt(document.getElementById('qval').value);
  document.getElementById('tprice').textContent = Math.round(total).toLocaleString('en-IN');
}

function stepQ(d){
  var inp  = document.getElementById('qval');
  var disp = document.getElementById('qdisp');
  var v    = parseInt(inp.value) + d;
  if(v<1) v=1; if(v>10) v=10;
  inp.value = v; disp.textContent = v; calcPrice();
}

function doAddCart(e){
  e.preventDefault();
  var btn  = document.getElementById('cartBtn');
  var orig = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding…';
  btn.disabled  = true;
  fetch('?action=add_to_cart', {method:'POST', body:new FormData(e.target)})
  .then(function(r){
    if(!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
  })
  .then(function(d){
    if(d.success){
      var badge = document.getElementById('cart-badge');
      if(badge){ badge.textContent = d.cart_count; badge.style.display = d.cart_count > 0 ? '' : 'none'; }
      btn.innerHTML = '<i class="fas fa-check"></i> Added!';
      btn.style.background = '#16a34a'; btn.style.borderColor = '#16a34a';
      setTimeout(function(){
        btn.innerHTML = orig;
        btn.style.background = ''; btn.style.borderColor = '';
        btn.disabled = false;
      }, 2200);
    } else {
      alert(d.message || 'Could not add to cart. Please try again.');
      btn.innerHTML = orig; btn.disabled = false;
    }
  })
  .catch(function(err){
    console.error('Add to cart error:', err);
    alert('Could not reach server. Please check your connection and try again.');
    btn.innerHTML = orig; btn.disabled = false;
  });
}

function doWishlist(id, btn){
  var orig = btn.innerHTML;
  btn.disabled = true;
  fetch('?action=toggle_wishlist',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'product_id='+id
  })
  .then(function(r){
    if(!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
  })
  .then(function(d){
    if(d.success){
      var wbadge = document.getElementById('wishlist-badge');
      if(wbadge){ wbadge.textContent = d.wishlist_count; wbadge.style.display = d.wishlist_count > 0 ? '' : 'none'; }
      if(d.added){
        btn.innerHTML = '<i class="fas fa-heart"></i> Saved!';
        btn.style.borderColor = 'var(--a)';
        btn.style.color       = 'var(--a)';
        btn.style.background  = 'var(--ag)';
      } else {
        btn.innerHTML = '<i class="far fa-heart"></i> Save to Wishlist';
        btn.style.borderColor = '';
        btn.style.color       = '';
        btn.style.background  = '';
      }
    }
    btn.disabled = false;
  })
  .catch(function(err){ console.error('Wishlist error:', err); btn.disabled = false; });
}
</script>
<?php endif; ?>