<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();
$filePath = __DIR__ . '/../posts/_404.md';

$content = $parsedown->text(file_get_contents($filePath));
?>

<main>
    <div class="container">
        <h2 class="m-0">404</h2>
        <?php echo $content ?>
    </div>
</main>