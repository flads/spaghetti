<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$file = file(__DIR__ . '/../posts/_about.md');
$title = mb_substr($file[1], 8, -2);

for ($i = 0; $i < 3; $i++) {
    unset($file[$i]);
}

$content = $parsedown->text(implode($file));
?>

<main class="about">
    <div class="container">
        <div class="page-header">
            <div>
                <h2 class="m-0"><?php echo $title ?></h2>
            </div>
            <div class="page-actions">
                <?php if ($isLogged) { ?>
                    <i class="fa-solid fa-pencil" title="Edit page"></i>
                <?php } ?>
            </div>
        </div>
        <div>
            <?php echo $content ?>
        </div>
        <div class="socials">
            <a target="_blank" href="http://github.com/"><i class="fa-brands fa-github"></i></a>
            <a target="_blank" href="http://linkedin.com/"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
</main>

<script>
    const editButton = document.querySelector("div.page-actions i.fa-pencil");

    if (editButton) {
        editButton.onclick = (event) => {
            window.location.href = `/admin/edit-page?file=_about`;
        }
    }
</script>