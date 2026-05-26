<?php $pageTitle = 'Login';
$hideSearch = true;
?>
<?php require BASE_PATH . '/view/Layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">


<div class="auth-page">
    <div class="auth-card">
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Login to continue to Media Library</p>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="auth-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- <?php if (!empty($message)): ?>
            <div class="auth-alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?> -->

        <form method="POST" class="auth-form">
            <div class="auth-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>

            <div class="auth-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit" class="auth-btn">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="auth-alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="auth-alert">
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="auth-footer">
            Don't have an account? <a href="?page=register">Register</a>
        </div>
    </div>
</div>
<?php require BASE_PATH . '/view/layout/footer.php'; ?>