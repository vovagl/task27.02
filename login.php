<?php
require 'config.php';
include 'header.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptcha_response)) {
        $message = "Пожалуйста, подтвердите что вы не робот!";
    } else {

        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$GOOGLE_SERVER_KEY}&response={$recaptcha_response}"
        );

        $captcha_success = json_decode($verify);

        if (!$captcha_success->success) {
            $message = "Ошибка проверки reCAPTCHA!";
        } else {

            $login = trim($_POST['login']);
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: profile.php");
                exit;
            } else {
                $message = "Неверные данные!";
            }
        }
    }
}
?>

<h2>Авторизация</h2>
<form method="post">
    Email или телефон: <input type="text" name="login" required><br>
    Пароль: <input type="password" name="password" required><br>
     <div class="g-recaptcha" data-sitekey='<?= $GOOGLE_CLIENT_KEY ?>'></div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <button type="submit">Войти</button>
</form>

<p class="message"><?= $message ?></p>
<a href="register.php">Регистрация</a>

<?php include 'footer.php'; ?>