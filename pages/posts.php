<?php
include 'header.php';
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$files = array_diff(scandir(__DIR__ . '/../posts/'), array('..', '.'));
$posts = array_map(function ($filename) use ($parsedown) {
    $filePath = __DIR__ . '/../posts/' . $filename;

    $file = file($filePath);
    return [
        'title' => substr($file[1], 8, -2),
        'date' => substr($file[2], 6, -1),
        'summary' => $parsedown->text(substr($file[3], 10, -2)),
        'draft' => substr($file[4], 6, -1),
        'pinned' => substr($file[5], 8, -1),
    ];

    /* TODO: para a tela de exibição da postagem
    for ($i = 0; $i < 7; $i++) {
        unset($file[$i]);
    }

    $response['summary'] = $parsedown->text(implode($file));

    return $response; */
}, $files);
?>

<main class="posts">
    <div class="container">
        <ul>
            <?php foreach ($posts as $post) { ?>
                <li>
                    <div>
                        <h2 class="m-0"><?php echo $post['title'] ?></h2>
                        <p class="m-0"><?php echo $post['date'] ?></p>
                    </div>
                    <p><?php echo $post['summary'] ?></p>
                </li>
            <?php } ?>
        </ul>
    </div>
</main>

<?php include 'footer.php' ?>