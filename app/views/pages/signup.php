<div class="auth-page">
    <div class="auth-container">
        <div class="auth-logo">🍕 Pizz_a64</div>
        <h2><i class="fas fa-user-plus"></i> Create Account</h2>
        <?php if(!empty($_SESSION['auth_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['auth_error']) ?></div>
            <?php unset($_SESSION['auth_error']); ?>
        <?php endif; ?>
        <form method="POST" action="?page=signup&action=submit">
            <div class="form-group"><label>Full Name</label><input type="text" name="fullname" required placeholder="John Doe"></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" required placeholder="your.email@example.com"></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" name="phone" placeholder="+91 98765 43210"></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required placeholder="Create a strong password" minlength="6"></div>
            <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm_password" required placeholder="Re-enter your password"></div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;"><i class="fas fa-user-plus"></i> Create Account</button>
        </form>
        <div class="auth-footer">Already have an account? <a href="?page=login">Login here</a></div>
    </div>
</div>