<div class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="?">Home</a> › Menu</p>
    <span class="sec-tag">Our Menu</span>
    <h1>Every Pizza, a Masterpiece</h1>
  </div>
</div>

<div class="menu-page">
  <div class="container">
    <div class="menu-wrap">

      <!-- Sidebar filters -->
      <aside class="filter-panel">
        <p class="filter-ttl">Category</p>
        <div id="catFilters">
          <?php
          $cats = array_unique(array_column($products,'category'));
          foreach($cats as $c):
          ?>
          <label class="frow">
            <input type="checkbox" class="cat-cb" value="<?= htmlspecialchars($c) ?>" checked>
            <?= htmlspecialchars($c) ?>
          </label>
          <?php endforeach; ?>
        </div>
        <div class="fdiv"></div>
        <p class="filter-ttl">Dietary</p>
        <label class="frow">
          <input type="checkbox" id="vegOnly"> Vegetarian Only
        </label>
      </aside>

      <!-- Grid -->
      <div>
        <div class="menu-top">Showing <strong id="rcnt"><?= count($products) ?></strong> pizzas</div>
        <div class="prod-grid" id="mgrid">
          <?php foreach($products as $p): ?>
          <div class="prod-card"
            data-cat="<?= htmlspecialchars($p['category']) ?>"
            data-veg="<?= $p['is_veg']?'1':'0' ?>">
            <div class="prod-img">
              <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
              <div class="prod-badges">
                <?php if($p['is_veg']): ?><span class="badge veg"><i class="fas fa-leaf"></i> VEG</span><?php endif; ?>
                <?php if($p['is_featured']): ?><span class="badge featured">⭐ Popular</span><?php endif; ?>
              </div>
              <button class="wl-btn" onclick="toggleWL(<?= (int)$p['id'] ?>,this)"><i class="far fa-heart"></i></button>
            </div>
            <div class="prod-body">
              <span class="prod-cat"><?= htmlspecialchars($p['category']) ?></span>
              <h3><?= htmlspecialchars($p['name']) ?></h3>
              <p class="prod-desc"><?= htmlspecialchars(substr($p['description'],0,82)) ?>…</p>
              <div class="prod-price">₹<?= number_format($p['min_price'] ?? $p['price'],0) ?><span>onwards</span></div>
              <div class="prod-acts">
                <a href="?page=product&id=<?= $p['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</a>
                <a href="?page=product&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-cart-plus"></i> Order</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div id="noResults">
          <i class="fas fa-search"></i>
          No pizzas match your current filters. Try adjusting them.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function doFilter(){
  var cats = [...document.querySelectorAll('.cat-cb:checked')].map(e=>e.value);
  var veg  = document.getElementById('vegOnly').checked;
  var cards = document.querySelectorAll('#mgrid .prod-card');
  var vis = 0;
  cards.forEach(function(c){
    var show = cats.includes(c.dataset.cat) && (!veg || c.dataset.veg==='1');
    c.style.display = show ? '' : 'none';
    if(show) vis++;
  });
  document.getElementById('rcnt').textContent = vis;
  document.getElementById('noResults').style.display = vis===0 ? 'block' : 'none';
}
document.querySelectorAll('.cat-cb, #vegOnly').forEach(function(el){
  el.addEventListener('change', doFilter);
});

function toggleWL(id,btn){
  btn.disabled=true;
  fetch('?action=toggle_wishlist',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'product_id='+id})
  .then(r=>r.json()).then(d=>{
    if(d.success){
      btn.innerHTML = d.added?'<i class="fas fa-heart"></i>':'<i class="far fa-heart"></i>';
      btn.classList.toggle('active',d.added);
      var wb = document.getElementById('wishlist-badge');
      if(wb){ wb.textContent = d.wishlist_count; wb.style.display = d.wishlist_count > 0 ? '' : 'none'; }
    }
    btn.disabled=false;
  }).catch(function(){btn.disabled=false});
}
</script>