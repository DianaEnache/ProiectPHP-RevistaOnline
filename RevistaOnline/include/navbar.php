<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Revista Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="articles.php">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="php_mail_send/contact.php">Contact</a>
                </li>
            </ul>

            <?php if (isset($_SESSION['username'])): ?>
                <span class="navbar-text text-white me-3">
                    Welcome back, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
                </span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-success me-2">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
