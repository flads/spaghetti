<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();
$filePath = __DIR__ . '/../posts/_about.md';

$content = $parsedown->text(file_get_contents($filePath));
?>

<main class="about">
    <div class="container">
        <h2 class="m-0">About me</h2>
        <p class="m-0"><?php echo $content ?></p>
    </div>
</main>