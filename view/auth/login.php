<?php $pageTitle = 'Login';
$hideSearch = true; ?>
<?php require BASE_PATH . '/view/layout/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

<div class="auth-page">
    <div class="auth-card">

        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Login to continue to Media Library</p>

        <?php if (!empty($message)): ?>
            <div class="auth-alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="auth-alert">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" class="auth-form">

            <div class="auth-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email">
            </div>

            <div class="auth-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit" class="auth-btn">Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="?page=register">Register</a>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>