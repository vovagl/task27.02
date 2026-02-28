<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<h2>Личный кабинет</h2>
<p>Привет, <?= htmlspecialchars($user['name']) ?></p>
<p>Email: <?= htmlspecialchars($user['email']) ?></p>
<p>Телефон: <?= htmlspecialchars($user['phone']) ?></p>

<a href="update_profile.php">Изменить данные</a><br>
<a href="logout.php">Выйти</a>

<?php include 'footer.php'; ?>