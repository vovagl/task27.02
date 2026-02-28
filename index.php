<?php
require 'config.php';
include 'header.php';
?>

<h1>Добро пожаловать</h1>

<?php if(isset($_SESSION['user_id'])): ?>
    <p>Привет, <?= htmlspecialchars($_SESSION['user_name']) ?>! <a href="profile.php">Профиль</a> | <a href="logout.php">Выйти</a></p>
<?php else: ?>
    <a href="register.php">Регистрация</a> | <a href="login.php">Войти</a>
<?php endif; ?>