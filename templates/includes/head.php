<!DOCTYPE html>
<html lang="ru-RU" style="height: 100%;">
    <head>
        <title><?php echo htmlspecialchars(App::i()->_seo()->getSeoTitle()); ?></title>
        <meta charset="utf-8">
        <meta name="description" content="<?php echo htmlspecialchars(App::i()->_seo()->getSeoDescription()); ?>">
        <meta name="keywords" content="<?php echo htmlspecialchars(App::i()->_seo()->getSeoKeywords()); ?>">
        <meta http-equiv="x-ua-compatible" content="IE=edge">
        <meta property="og:site_name" content="">
        <meta property="og:url" content="">
        <meta property="og:title" content="">
        <meta property="og:description" content="">
        <meta property="og:type" content="">
        <meta property="og:image" content="">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-ipad-retina.png">
        <?php foreach (App::i()->_responseConfiguration()->getSetting('css') as $css) { ?>
            <link rel="stylesheet" href="<?php echo $css; ?>"/>
            <?php
        }
        foreach (App::i()->_responseConfiguration()->getSetting('js') as $js) {
            ?>
            <script src="<?php echo $js; ?>" ></script>
        <?php }
        ?>
    </head>
