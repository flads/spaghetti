<?php
require(__DIR__ . "/../resources/php/helpers.php");

$form = $_POST;

if ($form["title"] && $form["summary"] && $form["content"]) {
    $filename = toKebab($form["title"]) . ".md";
    $filePath = __DIR__ . "/../posts/" . str_replace("/post/", "", $filename);

    $postSettings = [
        "title" => '"' . $form["title"] . '"',
        "date" => $form["date"] ?? date_format(date_create(), 'Y-m-d'),
        "summary" => '"' . $form["summary"] . '"',
        "draft" => $form["draft"] ?? "false",
        "pinned" => $form["pinned"] ?? "false",
    ];

    $postContent = "---" . PHP_EOL;

    foreach ($postSettings as $key => $value) {
        $postContent .= "$key: $value" . PHP_EOL;
    }

    $postContent .= "---" . PHP_EOL;
    $postContent .= PHP_EOL;
    $postContent .= $form["content"];

    file_put_contents($filePath, $postContent);
}
?>

<main class="add-new-post">
    <div class="container">
        <h2 class="m-0">Add New Post</h2>
        <form class="w-100" action="" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" class="w-50" placeholder="Enter title here">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea id="summary" class="summary" name="summary" placeholder="Summarize what your post is about" maxlength="255"></textarea>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" class="content" name="content" placeholder="Use your markdown skills here"></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Publish</button>
            </div>
        </form>
    </div>
</main>