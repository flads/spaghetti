<?php
require(__DIR__ . "/../resources/php/helpers.php");

if ($_SESSION["logged"]) {
    return header('Location: /');
}

$form = $_POST;
$errorMessage = null;

if (!empty($form["password"])) {
    $env = parse_ini_file('.env');

    if ($form["password"] === $env["PASSWORD"]) {
        unset($_SESSION["failed_login_attempts"]);
        $_SESSION["logged"] = true;

        return header('Location: /');
    }

    $_SESSION["failed_login_attempts"] = ($_SESSION["failed_login_attempts"] ?? 0) + 1;

    $attempts = $_SESSION["failed_login_attempts"];

    if ($attempts >= 3) {
        return header('Location: /');
    }

    $errorMessage = 'Invalid password! ' . (3 - $attempts) . ' attempts left.';
}
?>

<script>
    document.addEventListener('keydown', (event) => {
        if (event.keyCode === 13 && event.ctrlKey) {
            document.getElementById('submit').click();
        }
    });
</script>

<main class="login">
    <div class="container">
        <form class="login w-100" action="" method="POST">
            <div class="form-group">
                <div>
                    <input id="password" name="password" class="w-50" type="password" placeholder="Password...">

                    <?php if ($errorMessage) { ?>
                        <span><?php echo $errorMessage ?></span>
                    <?php } ?>
                </div>
                <button id="submit" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    document.getElementById('password').focus();
</script>