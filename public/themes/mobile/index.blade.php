<?php
header('Content-type:text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ru">
    <title>
        @section('title')
            {{ Setting::get('title') }}
        @show
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="image_src" href="/assets/img/images/icon.png"/>
    <?= include_style() ?>
    <link rel="stylesheet" href="/themes/mobile/css/style.css"/>
    <link rel="alternate" href="/news/rss" title="RSS News" type="application/rss+xml"/>
    <?= include_javascript() ?>
    <meta name="keywords" content="%KEYWORDS%"/>
    <meta name="description" content="%DESCRIPTION%"/>
    <meta name="generator" content="RotorCMS <?= env('VERSION') ?>"/>
</head>
<body>
<!--Design by Vantuz (http://pizdec.ru)-->

<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td width="10" height="10" style="background-image:url(/themes/mobile/img/border_top_left.gif);"></td>
        <td width="580" height="10" style="background-image:url(/themes/mobile/img/border_top.gif);"></td>
        <td width="10" height="10" style="background-image:url(/themes/mobile/img/border_top_right.gif);"></td>
    </tr>
    <tr valign="top">
        <td width="10" style="background-image:url(/themes/mobile/img/border_left.gif);"></td>
        <td width="580">

            <div class="a" id="up">
                <!-- <a href="/"><span class="logotype"><?= Setting::get('title') ?></span></a><br> -->
                <a href="/"><img src="<?= Setting::get('logotip') ?>" alt="<?= Setting::get('title') ?>"/></a><br/>
                <?= Setting::get('logos') ?>
            </div>

            <?php App::view('app/_menu'); ?>

            <div class="site">
<?= App::view('includes/note'); /*Временно пока шаблоны подключаются напрямую*/ ?>
