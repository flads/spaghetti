<?php
require(__DIR__ . '/../../libs/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$file = file(__DIR__ . '/../posts/_about.md');
$title = mb_substr($file[1], 8, -2);

for ($i = 0; $i < 3; $i++) {
    unset($file[$i]);
}

$content = $parsedown->text(implode($file));

$env = parse_ini_file(__DIR__ . '/../../.env');

$githubUser = $env["GITHUB_USER"];
$linkedinUser = $env["LINKEDIN_USER"];
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
            <a target="_blank" href="http://github.com/<?php echo $githubUser ?>"><i class="fa-brands fa-github"></i></a>
            <a target="_blank" href="http://linkedin.com/in/<?php echo $linkedinUser ?>"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
</main>

<script>
    const loginUrl = document.querySelector('html').getAttribute('data-login-url');
    const editButton = document.querySelector("div.page-actions i.fa-pencil");

    if (editButton) {
        editButton.onclick = (event) => {
            window.location.href = `/${loginUrl}/edit-page?file=_about`;
        }
    }
</script>