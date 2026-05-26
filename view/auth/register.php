<?php $pageTitle = 'Register';
$hideSearch = true;
?>
<?php require BASE_PATH . '/view/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

<div class="auth-page">
    <div class="auth-card">
        <h2 class="auth-title">Create Account</h2>
        <p class="auth-subtitle">Start building your Media Library</p>

        <!-- <?php if (!empty($message)): ?>
            <div class="auth-alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?> -->

        <form method="POST" class="auth-form">
            <div class="auth-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose username">
            </div>

            <div class="auth-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>

            <div class="auth-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create password">
            </div>

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

            <button type="submit" class="auth-btn">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="?page=login">Login</a>
        </div>
    </div>
</div>
<?php require BASE_PATH . '/view/layout/footer.php'; ?>