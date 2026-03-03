<?php
// home.php — backend-integrated partial (included by your router/index.php)
// ─────────────────────────────────────────────────────────────────────────
// Variables expected from the router:
//   $products       — full product array from DB
//   $featured       — (optional) pre-filtered featured products; falls back below
//   $cart_count     — int
//   $wishlist_count — int
//   $_SESSION       — standard PHP session

// Build featured list: prefer $featured if already set, otherwise filter $products
if (empty($featured)) {
    $featured = array_filter($products ?? [], fn($p) => !empty($p['is_featured']));
}
$featured = array_slice(array_values($featured), 0, 8); // cap at 8 on homepage
?>

<!-- ═══ HERO ══════════════════════════════════════════════════════════════ -->
<section class="hero">
  <div class="hero-bg-art"></div>
  <div class="container">
    <div class="hero-inner">

      <!-- Left: Text -->
      <div>
        <div class="hero-eyebrow rv">
          <i class="fas fa-fire-alt"></i>
          Wood-Fired &nbsp;·&nbsp; Farm-Fresh &nbsp;·&nbsp;
          <span>Delivered in 40 min</span>
        </div>

        <h1 class="rv d1">
          Hot &amp; Fresh Pizza<br>
          <em>Delivered to Your Door</em>
        </h1>

        <p class="hero-sub rv d2">
          Craving something delicious? We bake every pizza fresh, use premium ingredients,
          and deliver fast — so you can enjoy restaurant-quality taste at home.
        </p>

        <div class="hero-highlights rv d3">
          <span class="h-chip"><i class="fas fa-truck"></i> Free delivery over ₹499</span>
          <span class="h-chip"><i class="fas fa-fire"></i> Oven-baked in 7 min</span>
          <span class="h-chip"><i class="fas fa-clock"></i> Open till 11 PM</span>
        </div>

        <div class="hero-btns rv d3">
          <a href="?page=menu" class="btn btn-primary btn-lg">
            <i class="fas fa-utensils"></i> Order Now
          </a>
          <a href="?page=about" class="btn btn-outline btn-lg">
            <i class="fas fa-book-open"></i> Our Story
          </a>
        </div>

        <div class="hero-stats rv d4">
          <div class="hero-stat"><strong>50<em>K+</em></strong><span>Pizzas Baked</span></div>
          <div class="hero-stat"><strong>15<em>K+</em></strong><span>Happy Customers</span></div>
          <div class="hero-stat"><strong>40<em>min</em></strong><span>Avg Delivery</span></div>
        </div>
      </div>

      <!-- Right: Pizza visual -->
      <div class="hero-visual">
        <div class="pizza-plate">
          <img src="https://i.pinimg.com/736x/d1/cd/01/d1cd0192d3f4fcc20f454a4143f157d7.jpg"
               alt="Fresh Artisan Pizza">
          <span class="ing ing-1">🍅</span>
          <span class="ing ing-2">🌿</span>
          <span class="ing ing-3">🫒</span>
          <span class="ing ing-4">🧀</span>
          <span class="ing ing-5">🫑</span>
          <span class="ing ing-6">🧄</span>
        </div>
        <div class="free-tag">
          <strong>Free Delivery</strong> on orders over ₹499
        </div>
        <div class="fchip fchip-a">
          <i class="fas fa-star"></i>
          <div class="fchip-label"><strong>4.9 / 5</strong><span>15K+ reviews</span></div>
        </div>
        <div class="fchip fchip-b">
          <i class="fas fa-shipping-fast"></i>
          <div class="fchip-label"><strong>40 Minutes</strong><span>Express delivery</span></div>
        </div>
        <div class="fchip fchip-c">
          <i class="fas fa-leaf"></i>
          <div class="fchip-label"><strong>Farm Fresh</strong><span>Sourced daily</span></div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══ FEATURES BAR ══════════════════════════════════════════════════════ -->
<section class="features-bar" id="about">
  <div class="container">
    <div class="sec-hd rv">
      <span class="sec-tag">Why Choose Pizz_a64</span>
      <h2>The Difference You Taste</h2>
      <p>From hand-kneaded dough to our signature sauce — every bite is crafted with care</p>
    </div>
    <div class="feat-grid">
      <div class="feat-item rv d1">
        <div class="feat-icon"><i class="fas fa-fire-alt"></i></div>
        <h3>Wood-Fired Ovens</h3>
        <p>Authentic Italian wood-fired ovens at 450°C for that signature crispy crust and smoky depth.</p>
      </div>
      <div class="feat-item rv d2">
        <div class="feat-icon"><i class="fas fa-leaf"></i></div>
        <h3>Fresh Ingredients Daily</h3>
        <p>Hand-kneaded dough, signature tomato sauce, and premium toppings sourced fresh every morning.</p>
      </div>
      <div class="feat-item rv d3">
        <div class="feat-icon"><i class="fas fa-shipping-fast"></i></div>
        <h3>Fast Delivery</h3>
        <p>Hot, fresh pizza at your doorstep in under 40 minutes with real-time order tracking.</p>
      </div>
      <div class="feat-item rv d4">
        <div class="feat-icon"><i class="fas fa-thumbs-up"></i></div>
        <h3>100% Satisfaction</h3>
        <p>Not happy? We make it right every time. Friendly support available daily 10 AM – 11 PM.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ POPULAR PIZZAS — dynamic from DB ═════════════════════════════════ -->
<section class="pizzas-section" id="pizzas">
  <div class="container">

    <div class="section-top rv">
      <div>
        <span class="sec-tag">Chef's Selection</span>
        <h2>Popular Pizzas</h2>
      </div>
      <!-- Cart badge links to cart page and shows live count -->
      <a href="?page=cart" class="cart-badge" title="View cart">
        <i class="fas fa-shopping-cart"></i>
        <?php if($cart_count > 0): ?>
          <?= $cart_count ?> item<?= $cart_count > 1 ? 's' : '' ?> in cart
          <div class="cart-badge-dot"><?= $cart_count ?></div>
        <?php else: ?>
          Cart
        <?php endif; ?>
      </a>
    </div>

    <!-- Category filter pills built from live DB categories -->
    <?php $cats = array_unique(array_column($products ?? [], 'category')); ?>
    <div class="cat-pills rv">
      <button class="cat-pill active" data-cat="all">
        <i class="fas fa-pizza-slice"></i> All
      </button>
      <?php foreach($cats as $c): ?>
      <button class="cat-pill" data-cat="<?= htmlspecialchars($c) ?>">
        <?= htmlspecialchars($c) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Pizza grid — featured products from DB -->
    <div class="pizza-grid" id="homeGrid">
      <?php if(empty($featured)): ?>
        <p style="color:var(--dim);grid-column:1/-1;text-align:center;padding:40px 0">
          No featured pizzas right now. <a href="?page=menu">Browse the full menu →</a>
        </p>
      <?php else: ?>
        <?php foreach($featured as $i => $p): $delay = 'd'.($i % 4 + 1); ?>
        <div class="pizza-card rv <?= $delay ?>"
             data-cat="<?= htmlspecialchars($p['category']) ?>">

          <div class="card-img">
            <img src="<?= htmlspecialchars($p['image']) ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <div class="card-badges">
              <?php if($p['is_veg']): ?>
                <span class="badge badge-veg"><i class="fas fa-leaf"></i> VEG</span>
              <?php endif; ?>
              <?php if($p['is_featured']): ?>
                <span class="badge badge-hit">⭐ Popular</span>
              <?php endif; ?>
            </div>
            <!-- Wishlist heart — calls real toggle_wishlist action -->
            <button class="heart-btn" onclick="homeToggleWL(<?= (int)$p['id'] ?>, this)">
              <i class="far fa-heart"></i>
            </button>
          </div>

          <div class="card-body">
            <span class="card-cat"><?= htmlspecialchars($p['category']) ?></span>
            <div class="card-title-row">
              <h3><?= htmlspecialchars($p['name']) ?></h3>
            </div>
            <p class="card-ing"><?= htmlspecialchars(substr($p['description'], 0, 80)) ?>…</p>
          </div>

          <div class="card-footer">
            <div class="card-price">
              ₹<?= number_format($p['min_price'] ?? $p['price'], 0) ?> <em>from</em>
            </div>
            <!-- Links to product page so user can pick size before adding to cart -->
            <a href="?page=product&id=<?= (int)$p['id'] ?>"
               class="order-btn" title="Order <?= htmlspecialchars($p['name']) ?>">
              <i class="fas fa-cart-plus"></i>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:36px" class="rv">
      <a href="?page=menu" class="btn btn-outline btn-lg">
        <i class="fas fa-utensils"></i> View Full Menu
      </a>
    </div>

    <button class="scroll-btn"
            onclick="document.getElementById('sides').scrollIntoView({behavior:'smooth'})">
      <i class="fas fa-chevron-down"></i>
    </button>

  </div>
</section>

<!-- ═══ SIDES & DESSERTS ══════════════════════════════════════════════════ -->
<section class="extras-section" id="sides">
  <div class="container">
    <div class="sec-hd rv">
      <span class="sec-tag">More to Love</span>
      <h2>Sides &amp; Desserts</h2>
      <p>Complete your order with our hand-picked sides and classic Italian desserts</p>
    </div>
    <div class="extras-grid">

      <?php
      // Pull non-pizza categories from $products (e.g. 'Sides', 'Desserts', 'Drinks')
      $pizza_cats = ['pizza','classic','meat','veg','vegetarian','chicken','seafood','signature','cheese'];
      $sides = array_filter($products ?? [], function($p) use ($pizza_cats){
          foreach($pizza_cats as $pc){
              if(stripos($p['category'], $pc) !== false) return false;
          }
          return true;
      });
      $sides = array_slice(array_values($sides), 0, 6);

      if(!empty($sides)):
          foreach($sides as $j => $s): $sd = 'd'.($j % 3 + 1); ?>
          <div class="extra-card rv <?= $sd ?>">
            <div class="extra-img">
              <img src="<?= htmlspecialchars($s['image']) ?>"
                   alt="<?= htmlspecialchars($s['name']) ?>" loading="lazy">
            </div>
            <div class="extra-body">
              <h3><?= htmlspecialchars($s['name']) ?></h3>
              <p><?= htmlspecialchars(substr($s['description'], 0, 100)) ?>…</p>
              <div class="extra-footer">
                <span class="extra-price">₹<?= number_format($s['min_price'] ?? $s['price'], 0) ?></span>
                <a href="?page=product&id=<?= (int)$s['id'] ?>" class="extra-btn" title="Order">
                  <i class="fas fa-plus"></i>
                </a>
              </div>
            </div>
          </div>
          <?php endforeach;
      else: // Static fallback when no side-dish products in DB ?>

      <div class="extra-card rv d1">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1619740455993-9d10f5db0a4c?w=500&q=80" alt="Garlic Bread" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Garlic Bread</h3>
          <p>Crispy on the outside, soft inside, brushed with rich garlic butter. The perfect starter.</p>
          <div class="extra-footer">
            <span class="extra-price">₹149</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <div class="extra-card rv d2">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=500&q=80" alt="Chicken Wings" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Chicken Wings</h3>
          <p>BBQ, spicy, or honey garlic — crispy outside, juicy inside. Always a crowd pleaser.</p>
          <div class="extra-footer">
            <span class="extra-price">₹299</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <div class="extra-card rv d3">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&q=80" alt="Chocolate Lava Cake" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Chocolate Lava Cake</h3>
          <p>Warm, rich chocolate cake with a molten center. Served with vanilla ice cream.</p>
          <div class="extra-footer">
            <span class="extra-price">₹199</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <div class="extra-card rv d1">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=500&q=80" alt="Tiramisu" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Tiramisu</h3>
          <p>Classic Italian dessert layered with espresso-soaked ladyfingers and silky mascarpone cream.</p>
          <div class="extra-footer">
            <span class="extra-price">₹219</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <div class="extra-card rv d2">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1551248429-40975aa4de74?w=500&q=80" alt="Caesar Salad" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Caesar Salad</h3>
          <p>Romaine lettuce, shaved parmesan, house croutons, and our classic Caesar dressing.</p>
          <div class="extra-footer">
            <span class="extra-price">₹179</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <div class="extra-card rv d3">
        <div class="extra-img">
          <img src="https://images.unsplash.com/photo-1541614101331-1a5a3a194e92?w=500&q=80" alt="Soft Drinks" loading="lazy">
        </div>
        <div class="extra-body">
          <h3>Drinks &amp; Beverages</h3>
          <p>Soft drinks, fresh lemonade, sparkling water, and Italian sodas to complete your meal.</p>
          <div class="extra-footer">
            <span class="extra-price">from ₹79</span>
            <a href="?page=menu" class="extra-btn"><i class="fas fa-plus"></i></a>
          </div>
        </div>
      </div>

      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ═══ INSTAGRAM STRIP ════════════════════════════════════════════════════ -->
<section class="ig-section">
  <div class="ig-strip">
    <div class="ig-img">
      <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&q=80" alt="Restaurant" loading="lazy">
      <div class="ig-overlay"></div>
    </div>
    <div class="ig-img">
      <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&q=80" alt="Pizza" loading="lazy">
      <div class="ig-overlay"></div>
    </div>
    <div class="ig-img" style="position:relative">
      <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&q=80" alt="Kitchen" loading="lazy">
      <div class="ig-overlay"></div>
      <div class="ig-badge">
        <i class="fab fa-instagram"></i>
        <strong>@Pizz_a64Official</strong>
        <span>Follow us for daily specials</span>
      </div>
    </div>
    <div class="ig-img">
      <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&q=80" alt="Pizza fresh" loading="lazy">
      <div class="ig-overlay"></div>
    </div>
    <div class="ig-img">
      <img src="https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&q=80" alt="Pepperoni" loading="lazy">
      <div class="ig-overlay"></div>
    </div>
  </div>
</section>

<!-- ═══ CALLBACK FORM ══════════════════════════════════════════════════════ -->
<section class="callback-section" id="contact">
  <div class="container">
    <div class="callback-box">
      <div class="rv">
        <span class="sec-tag">Get in Touch</span>
        <h2>Let Us Call You Back</h2>
        <p class="callback-sub">
          Have questions about your order or catering? Leave your details and
          our team will contact you within minutes.
        </p>
        <!-- Posts to the same contact action used by contact.php -->
        <form method="POST" action="?page=contact&action=submit" class="cb-form">
          <input type="text"  name="name"    class="cb-input" placeholder="Your Name"    required>
          <input type="tel"   name="phone"   class="cb-input" placeholder="Phone Number" required>
          <input type="hidden" name="subject" value="Callback Request">
          <input type="hidden" name="email"   value="callback@pizz-a64.com">
          <input type="hidden" name="message" value="Please call me back.">
          <button type="submit" class="btn btn-primary"
                  style="height:50px;border-radius:var(--rp);padding:0 28px;flex-shrink:0">
            <i class="fas fa-phone-alt"></i> Call Me Back
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ═══ DELIVERY SECTION ══════════════════════════════════════════════════ -->
<section class="delivery-section">
  <div class="container">
    <div class="delivery-inner">
      <div class="delivery-text rv">
        <span class="sec-tag">Delivery</span>
        <h2>Fast &amp; Reliable<br>Delivery</h2>
        <p>
          We deliver hot and fresh pizza across Hyderabad in under 40 minutes.
          Track your order in real time and enjoy contactless delivery right to your door.
        </p>
        <div class="delivery-meta">
          <span class="dmeta"><i class="fas fa-clock"></i> Under 40 minutes</span>
          <span class="dmeta"><i class="fas fa-map-marker-alt"></i> City-wide coverage</span>
          <span class="dmeta"><i class="fas fa-mobile-alt"></i> Real-time tracking</span>
          <span class="dmeta"><i class="fas fa-hand-paper"></i> Contactless delivery</span>
        </div>
      </div>
      <div class="delivery-visual rv">
        <div class="delivery-box">
          <i class="fas fa-box"></i>
          <div class="delivery-box-label">
            <strong>On the way!</strong>
            <span>Estimated 18 min</span>
          </div>
        </div>
        <div class="speed-lines">
          <div class="speed-line"></div>
          <div class="speed-line"></div>
          <div class="speed-line"></div>
        </div>
        <div class="vehicle-stack">
          <i class="fas fa-motorcycle vehicle-main"></i>
        </div>
        <div class="road">
          <div class="road-line"></div>
          <div class="road-line"></div>
          <div class="road-line"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ TESTIMONIALS ═══════════════════════════════════════════════════════ -->
<section class="testi-section">
  <div class="container">
    <div class="sec-hd rv">
      <span class="sec-tag">Customer Love</span>
      <h2>What People Are Saying</h2>
      <p>Over 15,000 happy customers — and growing every single day</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card rv d1">
        <div class="testi-stars">★★★★★</div>
        <div class="testi-quote">"</div>
        <p class="testi-text">
          Absolutely the best pizza in Hyderabad. The Margherita is pure perfection —
          crispy crust, fresh basil, and the most flavourful sauce I've ever tasted. We order every Friday!
        </p>
        <div class="testi-author">
          <div class="testi-av">RK</div>
          <div><h4>Rahul Kumar</h4><p>Verified Customer · Regular Order</p></div>
        </div>
      </div>
      <div class="testi-card rv d2">
        <div class="testi-stars">★★★★★</div>
        <div class="testi-quote">"</div>
        <p class="testi-text">
          Lightning-fast delivery and the pizza arrived piping hot. The BBQ Chicken is now my
          weekend ritual. I've tried dozens of places — none come close to Pizz_a64.
        </p>
        <div class="testi-author">
          <div class="testi-av">PS</div>
          <div><h4>Priya Sharma</h4><p>Food Enthusiast · Loyal Customer</p></div>
        </div>
      </div>
      <div class="testi-card rv d3">
        <div class="testi-stars">★★★★★</div>
        <div class="testi-quote">"</div>
        <p class="testi-text">
          You can taste the freshness in every single bite. The Meat Lovers pizza is insane.
          Family pizza nights are a whole new level now. Consistent quality, every single time.
        </p>
        <div class="testi-author">
          <div class="testi-av">AM</div>
          <div><h4>Anil Mehta</h4><p>Family Customer · Weekly Order</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CTA ════════════════════════════════════════════════════════════════ -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner rv">
      <div class="cta-label">🔥 Weekend Special</div>
      <h2>Buy 1 Large, Get 1 Medium Free!</h2>
      <p>
        This weekend only — order any large pizza and get a medium pizza absolutely free.
        Use the code below at checkout. No strings attached.
      </p>
      <div class="promo-chip">PIZZA50</div>
      <div>
        <a href="?page=menu" class="btn btn-white btn-lg">
          <i class="fas fa-pizza-slice"></i> Grab the Deal
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HOME PAGE JAVASCRIPT ═══════════════════════════════════════════════ -->
<script>
/* ── Category pill filter ─────────────────────────────────────────────── */
document.querySelectorAll('.cat-pill').forEach(function(pill){
  pill.addEventListener('click', function(){
    document.querySelectorAll('.cat-pill').forEach(function(p){ p.classList.remove('active'); });
    this.classList.add('active');
    var cat = this.dataset.cat;
    document.querySelectorAll('#homeGrid .pizza-card').forEach(function(card){
      card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
    });
  });
});

/* ── Wishlist toggle — mirrors menu.php toggleWL, uses same AJAX action ─ */
function homeToggleWL(id, btn) {
  <?php if (!isset($_SESSION['user_id'])): ?>
    window.location.href = '?page=login';
    return;
  <?php endif; ?>
  btn.disabled = true;
  fetch('?action=toggle_wishlist', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + id
  })
  .then(function(r){ return r.json(); })
  .then(function(d){
    if (d.success) {
      var icon = btn.querySelector('i');
      icon.className  = d.added ? 'fas fa-heart' : 'far fa-heart';
      btn.classList.toggle('liked', d.added);
      // Sync header wishlist badge (id set in header.php)
      var badge = document.getElementById('wishlist-badge');
      if (badge) { badge.textContent = d.wishlist_count; badge.style.display = d.wishlist_count > 0 ? '' : 'none'; }
    }
    btn.disabled = false;
  })
  .catch(function(){ btn.disabled = false; });
}

/* ── Scroll reveal ────────────────────────────────────────────────────── */
(function(){
  var els = document.querySelectorAll('.rv');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ if (e.isIntersecting) e.target.classList.add('on'); });
    }, {threshold: 0.11});
    els.forEach(function(el){ io.observe(el); });
  } else {
    els.forEach(function(el){ el.classList.add('on'); });
  }
})();
</script>