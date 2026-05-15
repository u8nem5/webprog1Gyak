<?php
session_start();

if (empty($_SESSION['login'])) {
    http_response_code(403);
    exit('Nincs jogosultság a szerkesztő használatához.');
}

$id = (int)($_GET['id'] ?? 0);
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blogpost szerkesztő</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../styles/stilus.css">
</head>
<body>

<div class="blog-editor w3-card w3-padding">

    <h2><?= $id > 0 ? 'Blogpost szerkesztése' : 'Új blogpost' ?></h2>

    <form id="blogForm" action="/logicals/blogapi.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="content" id="content">

        <label>Cím</label>
        <input class="w3-input w3-border" type="text" name="title" id="title" required>

        <label>Borítókép</label>
        <input class="w3-input w3-border" type="file" name="cover_image" accept="image/*">

        <div id="current-cover"></div>

        <label>Cikk szövege</label>

        <div class="editor-toolbar w3-border">

            <button class="w3-button w3-border" type="button" onclick="cmd('bold')"><b>B</b></button>
            <button class="w3-button w3-border" type="button" onclick="cmd('italic')"><i>I</i></button>
            <button class="w3-button w3-border" type="button" onclick="cmd('underline')"><u>U</u></button>
            <button class="w3-button w3-border" type="button" onclick="cmd('insertUnorderedList')">Lista</button>
            <button class="w3-button w3-border" type="button" onclick="cmd('insertOrderedList')">Számozott lista</button>
            <button class="w3-button w3-border" type="button" onclick="addLink()">Link</button>
        </div>

        <div id="editor" class="w3-border w3-padding" contenteditable="true"></div>

        <br>

        <button class="w3-button w3-orange" type="submit">Mentés</button>
        <a class="w3-button w3-border" href="/blog">Mégse</a>
    </form>

</div>

<script>
let postId = <?= $id ?>;
let editor = document.getElementById('editor');
let form = document.getElementById('blogForm');

function cmd(name) {
    editor.focus();
    document.execCommand(name, false, null);
}

function addLink() {
    let url = prompt('Link címe:');

    if (url) {
        editor.focus();
        document.execCommand('createLink', false, url);
    }
}

form.addEventListener('submit', function () {
    document.getElementById('content').value = editor.innerHTML;
});

if (postId > 0) {
    fetch('/logicals/blogapi.php?id=' + postId)
        .then(function (response) {
            return response.json();
        })
        .then(function (post) {
            document.getElementById('title').value = post.title;
            editor.innerHTML = post.content;

            if (post.cover_image) {
                document.getElementById('current-cover').innerHTML =
                    '<p>Jelenlegi kép:</p><img class="w3-border" src="../' + post.cover_image + '">';
            }
        });
}
</script>

</body>
</html>