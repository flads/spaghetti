<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$filenames = array_diff(scandir(__DIR__ . '/../posts/'), array('..', '.'));
$postsFilenames = array_filter($filenames, fn ($filename) => $filename[0] !== '_');

$posts = array_map(function ($filename) use ($parsedown) {
    $filePath = __DIR__ . '/../posts/' . $filename;
    $file = file($filePath);

    return [
        'filename' => str_replace('.md', '', $filename),
        'title' => substr($file[1], 8, -2),
        'date' => date_format(date_create(substr($file[2], 6, -1)), 'd-m-Y'),
        'summary' => $parsedown->text(substr($file[3], 10, -2)),
        'draft' => substr($file[4], 6, -1),
        'pinned' => substr($file[5], 8, -1),
    ];
}, $postsFilenames);

usort($posts, fn ($a, $b) => $b['date'] <=> $a['date']);
?>

<main class="posts">
    <div class="container">
        <ul>
            <?php foreach ($posts as $post) { ?>
                <li>
                    <div>
                        <h2 class="m-0">
                            <a href="/post/<?php echo $post['filename'] ?>">
                                <?php echo $post['title'] ?>
                            </a>
                        </h2>
                        <p class="m-0"><?php echo $post['date'] ?></p>
                    </div>
                    <?php echo $post['summary'] ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</main>