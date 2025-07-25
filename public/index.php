<?php
session_start();

$isLogged = isset($_SESSION["logged"]) && $_SESSION["logged"];

if (isset($_GET['logout']) && $_GET['logout']) {
    unset($_SESSION['logged']);
}

$envPath = __DIR__ . '/../.env';
$env = parse_ini_file($envPath);

if (!$env) {
    $_SESSION["logged"] = true;
    $isLogged = true;

    copy(__DIR__ . '/../.env.example', $envPath);

    $env = parse_ini_file($envPath);
}

$websiteName = $env["WEBSITE_NAME"];
$loginUrl = $env["LOGIN_URL"];
$githubUser = $env["GITHUB_USER"];
$linkedinUser = $env["LINKEDIN_USER"];
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark" data-login-url="<?php echo $loginUrl ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $websiteName ?></title>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/resources/main.css">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="/resources/favicon.png"/>

    <script>
        const spaghettiLocalStorage = JSON.parse(localStorage.getItem('spaghetti'));

        if (
            spaghettiLocalStorage &&
            spaghettiLocalStorage.hasOwnProperty('isDarkTheme')
        ) {
            const isDarkTheme = spaghettiLocalStorage['isDarkTheme'];

            document.querySelector('html').setAttribute(
                'data-theme', isDarkTheme ? 'dark' : 'light'
            );
        }
    </script>
</head>

<body>
    <header>
        <div class="container">
            <a class="logo prevent-select" href="/"><?php echo $websiteName ?></a>
            <nav>
                <ul>
                    <li class="prevent-select"><a href="/about">About</a></li>

                    <li class="dark-theme-activator pointer">
                        <i class="fa-solid fa-moon prevent-select"></i>
                        <i class="fa-solid fa-sun prevent-select"></i>
                    </li>

                    <?php if ($isLogged) { ?>
                        <li class="separator">|</li>
                        <li class="settings pointer">
                            <i class="fa-solid fa-cog" title="Logout"></i>
                        </li>
                        <li class="logout pointer">
                            <i class="fa-solid fa-right-from-bracket" title="Logout"></i>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </header>
    <?php
    $userIsBlocked = isset($_SESSION["failed_login_attempts"]) && $_SESSION["failed_login_attempts"] >= 3;

    if ($userIsBlocked) {
        include 'pages/403.php';
    }

    if (!$userIsBlocked) {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsedUrl['path'];
        $query = $parsedUrl['query'] ?? null;

        if ($query) {
            parse_str($query, $parsedQuery);
        }

        switch ($path) {
            case '/':
                include 'pages/posts.php';
                break;
            case '/about':
                include 'pages/about.php';
                break;
            case '/' . $loginUrl:
                if (!$isLogged) {
                    include 'pages/login.php';
                    break;
                }
                include 'pages/posts.php';
                break;
            case '/' . $loginUrl . '/add-new-post':
                if ($isLogged) {
                    include 'pages/add-new-post.php';
                    break;
                }
                include 'pages/404.php';
                break;
            case '/' . $loginUrl . '/edit-post':
            case '/' . $loginUrl . '/edit-page':
                if ($isLogged) {
                    include 'pages/edit-post.php';
                    break;
                }
                include 'pages/404.php';
                break;
            case '/' . $loginUrl . '/settings':
                if ($isLogged) {
                    include 'pages/settings.php';
                    break;
                }
                include 'pages/404.php';
                break;
            default:
                include !file_exists(__DIR__ . '/posts' . str_replace('/post', '', $_SERVER['REQUEST_URI']) . '.md')
                    ? 'pages/404.php'
                    : 'pages/post.php';
                break;
        }
    }
    ?>
    <footer>
        <div class="socials">
            <a target="_blank" href="http://github.com/<?php echo $githubUser ?>"><i class="fa-brands fa-github"></i></a>
            <a target="_blank" href="http://linkedin.com/in/<?php echo $linkedinUser ?>"><i class="fa-brands fa-linkedin"></i></a>
        </div>
        <div>
            <p>Made with <a target="_blank" href="https://github.com/flads/spaghetti">Spaghetti</a></p>
        </div>
    </footer>

    <script src="/resources/main.js"></script>
    <script>
        const settingsButton = document.querySelector("li.settings");
        const logoutButton = document.querySelector("li.logout");

        if (logoutButton) {
            logoutButton.addEventListener("click", () => {
                fetch('/index.php?logout=true', {
                        method: 'GET'
                    })
                    .then(() => window.location.href = '/');
            });
        }

        if (settingsButton) {
            settingsButton.addEventListener("click", () => {
                const loginUrl = document.querySelector('html').getAttribute('data-login-url');

                window.location.href = `/${loginUrl}/settings`;
            });
        }
    </script>
</body>

</html>