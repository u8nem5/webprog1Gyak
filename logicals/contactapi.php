<?php
session_start();

require_once __DIR__ . '/../includes/pdo.php';



/*
|--------------------------------------------------------------------------
| POST contactapi.php
| Creates a contact message.
|--------------------------------------------------------------------------
*/


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_SESSION['login'])) {
        $name = $_SESSION['csn'] . " " . $_SESSION['un'];
    } else {
        $name = 'Vendég';
    }

    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (strlen($subject) < 3) {
        $errors[] = 'A tárgy legalább 3 karakter legyen.';
    }

    if (strlen($message) < 10) {
        $errors[] = 'Az üzenet legalább 10 karakter legyen.';
    }

    if (!empty($errors)) {
        echo '<h2>Hiba történt</h2>';

        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }

        echo '<p><a href="../kapcsolat">Vissza</a></p>';
        exit;
    }

    $pdo = getPdo();

    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, subject, message, created_at)
        VALUES (?, ?, ?, NOW())'
    );

    $stmt->execute([$name, $subject, $message]);

    header('Location: ../kapcsolat?success=1');
    exit;
}
