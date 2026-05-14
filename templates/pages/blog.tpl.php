<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/pdo.php';

function blogUserLoggedIn()
{
    return !empty($_SESSION['login']);
}

$editorUrl = 'logicals/editor.php';
$apiUrl = 'logicals/blogapi.php';

$pdo = getPdo();

$stmt = $pdo->query('SELECT id, title, content, cover_image, created_at FROM blogposts ORDER BY created_at DESC, id DESC');
$posts = $stmt->fetchAll();

$isLoggedIn = blogUserLoggedIn();
?>

<style>

</style>

<div class="blog-header">
    <h2>Blog</h2>

    <?php if ($isLoggedIn): ?>
        <a class="w3-button w3-orange" href="<?= $editorUrl ?>">Új post</a>
    <?php endif; ?>
</div>

<?php if (empty($posts)): ?>
    <p>Még nincs blogbejegyzés.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <article class="blog-card w3-card w3-padding">

        <h2><?= htmlspecialchars($post['title']) ?></h2>

        <div class="blog-date">
            <?= htmlspecialchars(date('Y. m. d. H:i', strtotime($post['created_at']))) ?>
        </div>

        <?php if (!empty($post['cover_image'])): ?>
            <img class="blog-cover" src="<?= htmlspecialchars($post['cover_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        <?php endif; ?>

        <div class="blog-content">
            <?= $post['content'] ?>
        </div>

        <?php if ($isLoggedIn): ?>
            <div class="blog-actions">
                <a class="w3-button w3-border" href="<?= $editorUrl ?>?id=<?= (int)$post['id'] ?>">
                    Szerkesztés
                </a>

                <button class="w3-button w3-border" type="button" onclick="deletePost(<?= (int)$post['id'] ?>)">
                    Törlés
                </button>
            </div>
        <?php endif; ?>

    </article>
<?php endforeach; ?>

<script>
    function deletePost(id) {
        if (!confirm('Biztosan törlöd ezt a bejegyzést?')) {
            return;
        }

        fetch(<?= json_encode($apiUrl) ?> + '?id=' + id, {
                method: 'DELETE'
            })
            .then(function() {
                window.location.reload();
            });
    }
</script>