<!DOCTYPE html>
<html lang="ru">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <?php
        include 'php/archive/loader.php';

        $page_obj = new archive\page\page();
        $page = $page_obj->get_page();
        ?>

        <title><?= ($page['meta']['title']) ?></title>

        <meta name="description" content="<?= ($page['meta']['description']) ?>">
        <meta name="keywords" content="<?= ($page['meta']['keywords']) ?>">
        <meta name="author" content="Ponomarenko Nikita">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#000000">

        <link rel="shortcut icon" type="images/png" href="img/favicon/logo.png">
        <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">

        <link rel="stylesheet" href="css/fonts.css">
        <link rel="stylesheet" href="css/core.css">
        <?= ($page['expansion']['css']) ?>   
</head>
<body>