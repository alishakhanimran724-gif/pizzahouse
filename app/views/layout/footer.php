</main>

<!-- ═══ FOOTER ════════════════════════════════════════════════════════════ -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">

      <!-- Col 1: Brand + social -->
      <div>
        <span class="footer-brand"><span>Pizza</span> House</span>
        <div class="footer-brand-sub">Family Restaurant</div>
        <p class="footer-about">
          Artisan pizza crafted with passion, baked in authentic wood-fired ovens
          and delivered fresh to your door every single day — since 2015.
        </p>
        <div class="socials">
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>

      <!-- Col 2: Quick links -->
      <div>
        <h4>Explore</h4>
        <ul class="footer-links">
          <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
          <li><a href="#pizzas"><i class="fas fa-pizza-slice"></i> Our Menu</a></li>
          <li><a href="#about"><i class="fas fa-info-circle"></i> About Us</a></li>
          <li><a href="#sides"><i class="fas fa-utensils"></i> Sides & Desserts</a></li>
          <li><a href="#contact"><i class="fas fa-phone"></i> Contact Us</a></li>
        </ul>
      </div>

      <!-- Col 3: Contact info -->
      <div id="contact-info">
        <h4>Reach Us</h4>
        <ul class="footer-contact">
          <li><i class="fas fa-map-marker-alt"></i>123 Main Street, Your City</li>
          <li><i class="fas fa-phone"></i>(123) 456-7890</li>
          <li><i class="fas fa-envelope"></i>info@pizzahouse.com</li>
          <li><i class="fas fa-clock"></i>Mon–Sun: 10:00 AM – 11:00 PM</li>
          <li><i class="fas fa-truck"></i>Delivery: 11:00 AM – 10:30 PM</li>
        </ul>
      </div>

      <!-- Col 4: Newsletter -->
      <div>
        <h4>Stay in the Loop</h4>
        <p class="nl-note">
          Exclusive deals, new menu arrivals, and weekend specials
          straight to your inbox. No spam, ever.
        </p>
        <div class="nl-row">
          <input type="email" class="nl-input" placeholder="you@email.com">
          <button class="nl-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer bottom bar -->
  <div class="footer-bot">
    <div class="container">
      <p>© 2026 Pizza House Family Restaurant. All rights reserved.</p>
      <div class="footer-bot-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= $base_url ?>/public/js/main.js"></script>
<script>
(function(){
  // Hamburger toggle
  var ham = document.getElementById('hamburger');
  var mnav = document.getElementById('mobileNav');
  if(ham && mnav){
    ham.addEventListener('click', function(){
      ham.classList.toggle('open');
      mnav.classList.toggle('open');
    });
  }

  // Sticky header shadow
  window.addEventListener('scroll', function(){
    document.getElementById('mainHeader').classList.toggle('scrolled', window.scrollY > 24);
  }, {passive:true});

  // Scroll reveal
  var rvEls = document.querySelectorAll('.rv');
  if('IntersectionObserver' in window){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ if(e.isIntersecting) e.target.classList.add('on'); });
    }, {threshold:0.11});
    rvEls.forEach(function(el){ io.observe(el); });
  } else {
    rvEls.forEach(function(el){ el.classList.add('on'); });
  }
})();
</script>
</body>
</html>