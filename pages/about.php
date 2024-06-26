<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();
$filePath = __DIR__ . '/../posts/_about.md';

$content = $parsedown->text(file_get_contents($filePath));
?>

<main class="about">
    <div class="container">
        <div>
            <h2 class="m-0">About me</h2>
            <?php echo $content ?>
        </div>
        <div class="socials">
            <a target="_blank" href="http://github.com/"><i class="fa-brands fa-github"></i></a>
            <a target="_blank" href="http://linkedin.com/"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
</main>