<?php
if(!isset($title)) {
    $title = "Standaard title";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <style>
        header {
            background: antiquewhite;
        }
        main {
            background: #a0e0a0;
        }
        footer {
            background: crimson;
        }
    </style>
</head>
<body>
<header>
    <h1><?php echo $title ?></h1>
    <nav>
        <a href="/">home</a>
        <a href="/contact">contact</a>
        <a href="/over-ons">Over ons</a>
    </nav>
</header>
<main>
