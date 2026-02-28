<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = '';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password && $password !== $confirm_password) {
        $message = "Пароли не совпадают!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = ? OR phone = ?) AND id != ?");
        $stmt->execute([$email, $phone, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            $message = "Email или телефон уже используются другим пользователем!";
        } else {
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=?");
                $stmt->execute([$name, $email, $phone, $hash, $_SESSION['user_id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
                $stmt->execute([$name, $email, $phone, $_SESSION['user_id']]);
            }
            $message = "Данные успешно обновлены!";
        }
    }
}
?>

<h2>Изменение данных</h2>
<form method="post">
    Имя: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    Телефон: <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br>
    Новый пароль: <input type="password" name="password"><br>
    Повтор пароля: <input type="password" name="confirm_password"><br>
    <button type="submit">Сохранить</button>
</form>

<p class="message"><?= $message ?></p>
<a href="profile.php">Назад</a>

<?php include 'footer.php'; ?>