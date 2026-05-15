<?php

require_once __DIR__ . '/../includes/pdo.php';



/*
|--------------------------------------------------------------------------
| POST contactapi.php
| Creates a contact message.
|--------------------------------------------------------------------------
*/


if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (strlen($name) < 3) {
        $errors[] = 'A név legalább 3 karakter legyen.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Hibás e-mail cím.';
    }

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
        'INSERT INTO contact_messages (name, email, subject, message, created_at)
        VALUES (?, ?, ?, ?, NOW())'
    );

    $stmt->execute([$name, $email, $subject, $message]);

    header('Location: ../kapcsolat?success=1');
    exit;
}
