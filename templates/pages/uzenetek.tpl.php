<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/pdo.php';

if (empty($_SESSION['login'])) {
    echo '<p>Az üzenetek megtekintéséhez be kell jelentkezni.</p>';
    return;
}

$pdo = getPdo();

$stmt = $pdo->query(
    'SELECT id, name, email, subject, message, created_at
     FROM contact_messages
     ORDER BY created_at DESC, id DESC'
);

$messages = $stmt->fetchAll();
?>

<h2>Beérkezett üzenetek</h2>

<?php if (empty($messages)): ?>
    <p>Még nincs beérkezett üzenet.</p>
<?php endif; ?>

<?php foreach ($messages as $msg): ?>
    <div class="w3-card w3-padding">
        <h3><?= htmlspecialchars($msg['subject']) ?></h3>

        <p><b>Név:</b> <?= htmlspecialchars($msg['name']) ?></p>
        <p><b>Dátum:</b> <?= htmlspecialchars($msg['created_at']) ?></p>
        <p><b>Üzenet:</b><br><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
    </div>

    <br>
<?php endforeach; ?>