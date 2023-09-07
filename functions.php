<?php
define('HOME_PAGE_ID', get_option('page_on_front'));
define('BLOG_ID', get_option('page_for_posts'));
define('POSTS_PER_PAGE', get_option('posts_per_page'));

/* CUSTOM FUNCTIONS */
require_once 'include/plugins/init.php';
require_once 'include/acf/acf-settings.php';
require_once 'include/core.php';
//require_once 'include/woocommerce.php';
//require_once('include/cpt.php');


//register menus
register_nav_menus(array(
    'main_menu' => 'Main menu',
));

show_admin_bar(false);





















