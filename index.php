<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spaghetti</title>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/resources/main.css">
</head>

<body>
    <header>
        <div class="container">
            <a class="logo prevent-select" href="/">Spaghetti</a>
            <nav>
                <ul>
                    <li class="prevent-select"><a href="/about">About me</a></li>
                    <li class="prevent-select"><a href="/contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
    $uri = $_SERVER['REQUEST_URI'];

    switch ($uri) {
        case '/':
            include 'pages/posts.php';
            break;
        case '/about':
            include 'pages/about.php';
            break;
        default:
            include !file(__DIR__ . '/posts' . str_replace('/post', '', $uri) . '.md')
                ? 'pages/404.php'
                : 'pages/post.php';
            break;
    }
    ?>
    <footer>
        <div>
            <i class="fa-brands fa-github"></i>
            <i class="fa-brands fa-linkedin"></i>
        </div>
        <div>
            <p>Made with <a target="_blank" href="https://spaghetti.rest">Spaghetti</a></p>
        </div>
    </footer>
</body>

</html>