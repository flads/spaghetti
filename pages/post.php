<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$uri = $_SERVER['REQUEST_URI'];

$filePath = __DIR__ . '/../posts/' . str_replace('/post/', '', $uri) . '.md';
$file = file($filePath);

$post = [
    'title' => mb_substr($file[1], 8, -2),
    'date' => date_format(date_create(mb_substr($file[2], 6, -1)), 'd-m-Y'),
    'summary' => $parsedown->text(mb_substr($file[3], 10, -2)),
    'draft' => mb_substr($file[4], 6, -1),
    'pinned' => mb_substr($file[5], 8, -1),
];

for ($i = 0; $i < 7; $i++) {
    unset($file[$i]);
}

$post['content'] = $parsedown->text(implode($file));
?>

<main class="post">
    <div class="container">
        <div class="title">
            <h2 class="m-0">
                <?php echo $post['title'] ?>
            </h2>
            <p class="m-0"><?php echo $post['date'] ?></p>
        </div>
        <div class="content">
            <?php echo $post['content'] ?>
        </div>
    </div>
</main>