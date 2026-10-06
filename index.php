<?php
    $example_directories = array_filter(
        scandir(__DIR__),
        fn($item) => is_dir(__DIR__ . '/' . $item) && !in_array($item, ['.', '..', '.idea', '.DS_Store', '.git', 'target'])
    );
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP voorbeelden</title>
</head>
<body>
<main>
    <h1>PHP met voorbeelden</h1>
    <nav>
        <ul>
            <?php foreach ($example_directories as $example) {
                echo "<li><a href='/". $example . "'>" . $example . "</a></li>";
            } ?>
        </ul>
    </nav>
</main>
</body>
</html>
