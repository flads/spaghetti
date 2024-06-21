<?php
include 'header.php';

$pages = array_map(function ($page) {
    return [
        'name' => str_replace('.md', '', $page),
        'summary' => substr(file_get_contents(__DIR__ . '/../posts/' . $page), 0, 300) . '...'
    ];
}, array_diff(scandir(__DIR__ . '/../posts/'), array('..', '.')));
?>

<main class="posts">
    <div class="container">
        <ul>
            <?php foreach ($pages as $page) { ?>
                <li>
                    <h2><?php echo $page['name'] ?></h2>
                    <p><?php echo $page['summary'] ?></p>
                </li>
            <?php } ?>
        </ul>
    </div>
</main>

<?php include 'footer.php' ?>