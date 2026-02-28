<?php
require 'config.php';
include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Пароли не совпадают!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$email, $phone]);
        if ($stmt->rowCount() > 0) {
            $message = "Email или телефон уже зарегистрированы!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $hash]);
            $message = "Регистрация успешна! <a href='login.php'>Войти</a>";
        }
    }
}
?>

<h2>Регистрация</h2>
<form method="post">
    Имя: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Телефон: <input type="text" name="phone" required><br>
    Пароль: <input type="password" name="password" required><br>
    Повтор пароля: <input type="password" name="confirm_password" required><br>
    <button type="submit">Зарегистрироваться</button>
</form>

<p class="message"><?= $message ?></p>
<a href="login.php">Войти</a>

<?php include 'footer.php'; ?>