<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
    <title><?php seo_title(); ?></title>
    <meta name="MobileOptimized" content="width" />
    <meta name="HandheldFriendly" content="True"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php /* favicon */ ?>
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo theme(); ?>/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo theme(); ?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo theme(); ?>/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo theme(); ?>/img/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#000">
    <?php /* end favicon */ ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="page_id_<?php the_ID() ?>" >
<div id="main">
    <header>
        <div class="wrapper alc">
            <a id="logo" href="<?php echo get_option('home') ?>">
                <img src="<?php echo theme('img/logo.png'); ?>" alt="logo">
            </a>
            <div id="menuOpen"><p>Menu</p><span></span></div>
            <nav id="mainMenu">
                <?php wp_nav_menu(array('container' => false, 'items_wrap' => '<ul id="%1$s">%3$s</ul>', 'theme_location'  => 'main_menu')); ?>
            </nav>
        </div>
    </header>