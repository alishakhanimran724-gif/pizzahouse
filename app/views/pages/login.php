<div class="auth-page">
    <div class="auth-container">
        <div class="auth-logo">🍕 Pizz_a64</div>
        <h2><i class="fas fa-sign-in-alt"></i> Welcome Back</h2>
        <?php if(!empty($_SESSION['auth_error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['auth_error']) ?></div>
            <?php unset($_SESSION['auth_error']); ?>
        <?php endif; ?>
        <form method="POST" action="?page=login&action=submit">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="your.email@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <div class="auth-footer">Don't have an account? <a href="?page=signup">Sign up now</a></div>
    </div>
</div>