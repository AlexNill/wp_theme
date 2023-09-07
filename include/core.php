<?php
/*REGISTER CSS AND JS FILES*/
function style_js()
{
    wp_enqueue_script('lib', get_template_directory_uri() . '/js/lib.js', array('jquery'), '1.0', true);
    wp_enqueue_script('logic', get_template_directory_uri() . '/js/logic.js', array('jquery'), '1.0', true);
    wp_enqueue_style('fonts', get_template_directory_uri() . '/style/fonts.css');
    wp_enqueue_style('outer', get_template_directory_uri() . '/style/outer.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/style/style.css');

}
add_action('wp_enqueue_scripts', 'style_js');

/*REGISTER SIDEBARS*/
$reg_sidebars = array (
    'page_sidebar'     => 'Page Sidebar',
    'blog_sidebar'     => 'Blog Sidebar',
    'footer_sidebar'   => 'Footer Area'
);
foreach ( $reg_sidebars as $id => $name ) {
    register_sidebar(
        array (
            'name'          => __( $name ),
            'id'            => $id,
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgetTitle">',
            'after_title'   => '</h2>',
        )
    );
}

/*POST THUMBNAIL SUPPORT*/
add_theme_support( 'post-thumbnails' );

/*IMAGE SIZES*/
add_image_size( 'free', '1920', '', true );

/* SVG SUPPORT */
function wpa_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}

add_filter('upload_mimes', 'wpa_mime_types');
function wpa_fix_svg_thumb()
{
    echo '<style>td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {width: 100% !important;height: auto !important}</style>';
}

add_action('admin_head', 'wpa_fix_svg_thumb');

/*BYPASS XML CHECK FOR SVG*/
add_filter('wp_check_filetype_and_ext', 'ignore_upload_ext', 10, 4);
function ignore_upload_ext($checked, $file, $filename, $mimes)
{
    //we only need to worry if WP failed the first pass
    if (!$checked['type']) {
        //rebuild the type info
        $wp_filetype = wp_check_filetype($filename, $mimes);
        $ext = $wp_filetype['ext'];
        $type = $wp_filetype['type'];
        $proper_filename = $filename;
        //preserve failure for non-svg images
        if ($type && 0 === strpos($type, 'image/') && $ext !== 'svg') {
            $ext = $type = false;
        }
        //everything else gets an OK, so e.g. we've disabled the error-prone finfo-related checks WP just went through. whether or not the upload will be allowed depends on the <code>upload_mimes</code>, etc.
        $checked = compact('ext', 'type', 'proper_filename');
    }
    return $checked;
}

/*
Plugin Name: Cyr to Lat enhanced
Plugin URI: http://wordpress.org/plugins/cyr3lat/
Description: Converts Cyrillic, European and Georgian characters in post, term slugs and media file names to Latin characters. Useful for creating human-readable URLs. Based on the original plugin by Anton Skorobogatov.
Author: Sol, Sergey Biryukov, Nikolay Karev, Dmitri Gogelia
Author URI: http://karevn.com/
Version: 3.5
 */
function ctl_sanitize_title($title)
{
    global $wpdb;
    $iso9_table = array(
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G',
        'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
        'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
        'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
        'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
        'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '',
        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
        'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
        'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
        'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
        'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
        'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '',
        'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    );
    $geo2lat = array(
        'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
        'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
        'ნ' => 'n', 'ო' => 'o', 'პ' => 'p', 'ჟ' => 'zh', 'რ' => 'r', 'ს' => 's',
        'ტ' => 't', 'უ' => 'u', 'ფ' => 'ph', 'ქ' => 'q', 'ღ' => 'gh', 'ყ' => 'qh',
        'შ' => 'sh', 'ჩ' => 'ch', 'ც' => 'ts', 'ძ' => 'dz', 'წ' => 'ts', 'ჭ' => 'tch',
        'ხ' => 'kh', 'ჯ' => 'j', 'ჰ' => 'h',
    );
    $iso9_table = array_merge($iso9_table, $geo2lat);
    $locale = get_locale();
    switch ($locale) {
        case 'bg_BG':
            $iso9_table['Щ'] = 'SHT';
            $iso9_table['щ'] = 'sht';
            $iso9_table['Ъ'] = 'A';
            $iso9_table['ъ'] = 'a';
            break;
        case 'uk':
            $iso9_table['И'] = 'Y';
            $iso9_table['и'] = 'y';
            break;
        case 'uk_ua':
            $iso9_table['И'] = 'Y';
            $iso9_table['и'] = 'y';
            break;
    }
    $is_term = false;
    $backtrace = debug_backtrace();
    foreach ($backtrace as $backtrace_entry) {
        if ($backtrace_entry['function'] == 'wp_insert_term') {
            $is_term = true;
            break;
        }
    }
    $term = $is_term ? $wpdb->get_var("SELECT slug FROM {$wpdb->terms} WHERE name = '$title'") : '';
    if (empty($term)) {
        $title = strtr($title, apply_filters('ctl_table', $iso9_table));
        if (function_exists('iconv')) {
            $title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
        }
        $title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
        $title = preg_replace('/\-+/', '-', $title);
        $title = preg_replace('/^-+/', '', $title);
        $title = preg_replace('/-+$/', '', $title);
    } else {
        $title = $term;
    }
    return $title;
}

add_filter('sanitize_title', 'ctl_sanitize_title', 9);
add_filter('sanitize_file_name', 'ctl_sanitize_title');
function ctl_convert_existing_slugs()
{
    global $wpdb;
    $posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')");
    foreach ((array)$posts as $post) {
        $sanitized_name = ctl_sanitize_title(urldecode($post->post_name));
        if ($post->post_name != $sanitized_name) {
            add_post_meta($post->ID, '_wp_old_slug', $post->post_name);
            $wpdb->update($wpdb->posts, array('post_name' => $sanitized_name), array('ID' => $post->ID));
        }
    }
    $terms = $wpdb->get_results("SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^A-Za-z0-9\-]+') ");
    foreach ((array)$terms as $term) {
        $sanitized_slug = ctl_sanitize_title(urldecode($term->slug));
        if ($term->slug != $sanitized_slug) {
            $wpdb->update($wpdb->terms, array('slug' => $sanitized_slug), array('term_id' => $term->term_id));
        }
    }
}

function ctl_schedule_conversion()
{
    add_action('shutdown', 'ctl_convert_existing_slugs');
}

register_activation_hook(__FILE__, 'ctl_schedule_conversion');

/* MAKE PERMALINK STRUCTURE TO POSTNAME */
function update_permalink_structure()
{
    //Set permalink settings
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('%postname%');
}

add_action('after_switch_theme', 'update_permalink_structure');

/* Remove Unnecessary Code from wp_head */
remove_action('wp_head', 'rsd_link'); // Really Simple Discovery
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_generator'); // WordPress Generator
remove_action('wp_head', 'rel_canonical'); // canonical tag meta
// Post Relational Links
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
// Emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
function remove_json_api()
{
    // Remove the REST API lines from the HTML Header
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');
    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    // Remove all embeds rewrite rules.
    // remove HTML meta tag
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    // remove HTTP header
    remove_action('template_redirect', 'wp_shortlink_header', 11);
}

add_action('after_setup_theme', 'remove_json_api');

/* Remove wp version param from any enqueued scripts */
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

/* Remove dashboard wigets NEED TEST */
function remove_dash_widgets()
{
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
}

add_action('admin_init', 'remove_dash_widgets');

/* Contact form 7 remove AUTOTOP */
if(defined('WPCF7_VERSION')) {
    function maybe_reset_autop( $form ) {
        $form_instance = WPCF7_ContactForm::get_current();
        $manager = WPCF7_ShortcodeManager::get_instance();
        $form_meta = get_post_meta( $form_instance->id(), '_form', true );
        $form = $manager->do_shortcode( $form_meta );
        $form_instance->set_properties( array(
            'form' => $form
        ) );
        return $form;
    }
    add_filter( 'wpcf7_form_elements', 'maybe_reset_autop' );
}

/* ===Theme Custom Functions=== */

/* New Body Classes NEED TEST */
//function new_body_classes( $classes ){
//    if( is_page() ){
//        global $post;
//        $temp = get_page_template();
//        if ( $temp != null ) {
//            $path = pathinfo($temp);
//            $tmp = $path['filename'] . "." . $path['extension'];
//            $tn= str_replace(".php", "", $tmp);
//            $classes[] = $tn;
//        }
//        if (is_active_sidebar('sidebar')) {
//            $classes[] = 'with_sidebar';
//        }
//        foreach($classes as $k => $v) {
//            if(
//                $v == 'page-template' ||
//                $v == 'page-id-'.$post->ID ||
//                $v == 'page-template-default' ||
//                $v == 'woocommerce-page' ||
//                ($temp != null?($v == 'page-template-'.$tn.'-php' || $v == 'page-template-'.$tn):'')) unset($classes[$k]);
//        }
//    }
//    if( is_single() ){
//        global $post;
//        $f = get_post_format( $post->ID );
//        foreach($classes as $k => $v) {
//            if($v == 'postid-'.$post->ID || $v == 'single-format-'.(!$f?'standard':$f)) unset($classes[$k]);
//        }
//    }
//    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
//    $browser = $_SERVER[ 'HTTP_USER_AGENT' ];
//    // Mac, PC ...or Linux
//    if ( preg_match( "/Mac/", $browser ) ){
//        $classes[] = 'macos';
//    } elseif ( preg_match( "/Windows/", $browser ) ){
//        $classes[] = 'windows';
//    } elseif ( preg_match( "/Linux/", $browser ) ) {
//        $classes[] = 'linux';
//    } else {
//        $classes[] = 'unknown-os';
//    }
//    // Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
//    if ( preg_match( "/Chrome/", $browser ) ) {
//        $classes[] = 'chrome';
//        preg_match( "/Chrome\/(\d.\d)/si", $browser, $matches);
//        @$classesh_version = 'ch' . str_replace( '.', '-', $matches[1] );
//        $classes[] = $classesh_version;
//    } elseif ( preg_match( "/Safari/", $browser ) ) {
//        $classes[] = 'safari';
//        preg_match( "/Version\/(\d.\d)/si", $browser, $matches);
//        $sf_version = 'sf' . str_replace( '.', '-', $matches[1] );
//        $classes[] = $sf_version;
//    } elseif ( preg_match( "/Opera/", $browser ) ) {
//        $classes[] = 'opera';
//        preg_match( "/Opera\/(\d.\d)/si", $browser, $matches);
//        $op_version = 'op' . str_replace( '.', '-', $matches[1] );
//        $classes[] = $op_version;
//    } elseif ( preg_match( "/MSIE/", $browser ) ) {
//        $classes[] = 'msie';
//        if( preg_match( "/MSIE 6.0/", $browser ) ) {
//            $classes[] = 'ie6';
//        } elseif ( preg_match( "/MSIE 7.0/", $browser ) ){
//            $classes[] = 'ie7';
//        } elseif ( preg_match( "/MSIE 8.0/", $browser ) ){
//            $classes[] = 'ie8';
//        } elseif ( preg_match( "/MSIE 9.0/", $browser ) ){
//            $classes[] = 'ie9';
//        }
//    } elseif ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
//        $classes[] = 'firefox';
//        preg_match( "/Firefox\/(\d)/si", $browser, $matches);
//        $ff_version = 'ff' . str_replace( '.', '-', $matches[1] );
//        $classes[] = $ff_version;
//    } else {
//        $classes[] = 'unknown-browser';
//    }
//    //qtranslate classes
//    if(defined('QTX_VERSION')) {
//        $classes[] = 'qtrans-' . qtranxf_getLanguage();
//    }
//    return $classes;
//}
//add_filter( 'body_class', 'new_body_classes' );

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
function tt_wp_duplicate_posts(){
    global $wpdb;
    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'tt_wp_duplicate_posts' == $_REQUEST['action'] ) ) ) {
        wp_die('No post to duplicate has been supplied!');
    }

    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if (isset( $post ) && $post != null) {

        /*
         * new post data array
         */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => $post->post_status,
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post( $args );

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        /*
         * duplicate all post meta just in two SQL queries
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }


        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}
add_action( 'admin_action_tt_wp_duplicate_posts', 'tt_wp_duplicate_posts' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function tt_wp_duplicate_post_link( $actions, $post ) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="admin.php?action=tt_wp_duplicate_posts&amp;post=' . $post->ID . '" rel="permalink"><span class="dashicons dashicons-arrow-left-alt2" style="font-size: 8px;vertical-align: baseline"></span>Duplicate<span class="dashicons dashicons-arrow-right-alt2" style="font-size: 8px;vertical-align: baseline"></span></a>';
    }
    return $actions;
}

add_filter('post_row_actions', 'tt_wp_duplicate_post_link', 10, 2 );
add_filter('page_row_actions', 'tt_wp_duplicate_post_link', 10, 2);

/* custom SEO title */
function seo_title(){
    global $post;
    if(!defined('WPSEO_VERSION')) {
        if(is_404()) {
            echo '404 Page not found - ';
        } elseif((is_single() || is_page()) && $post->post_parent) {
            $parent_title = get_the_title($post->post_parent);
            echo wp_title('-', true, 'right') . $parent_title.' - ';
        } elseif(class_exists('Woocommerce') && is_shop()) {
            echo get_the_title(SHOP_ID) . ' - ';
        } else {
            wp_title('-', true, 'right');
        }
        bloginfo('name');
    } else {
        wp_title();
    }
}

/* Custom theme url */
function theme($filepath = NULL){
    return preg_replace( '(https?://)', '//', get_stylesheet_directory_uri() . ($filepath?'/' . $filepath:'') );
}

/* Button Shortcode */
function content_btn($atts,$content){
    extract(shortcode_atts(array(
        'text' => 'Learn More',
        'link' => site_url(),
        'class' => false,
        'target' => false
    ), $atts ));
    return '<a href="' . $link . '" class="button'.($class?' '.$class:'').'" '.($target?'target="'.$target.'"':'').'>' . $text . '</a>';
}
add_shortcode("button", "content_btn");

/* ajax_url */
function ajax_url()
{
    echo '<script type="text/javascript">const ajax_url="' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('wp_head', 'ajax_url');

/* Social Media Links */
function some() {
    $some = get_field('some', 'option');
    $soc = '';
    if($some) {
        $soc .= '<div class="some">';
        foreach($some as $sm) {
            $soc .= '<a class="icon-'.$sm['icon'].'" target="_blank" href="'.$sm['link'].'" rel="nofollow"></a>';
        }
        $soc .= '</div>';
    }
    return $soc;
}
add_shortcode("social", "some");