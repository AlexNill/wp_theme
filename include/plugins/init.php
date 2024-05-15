<?php

require_once 'TGM-Plugin.php';
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.0
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */
/**
 * Include the TGM_Plugin_Activation class.
 */
// require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
add_action('tgmpa_register', 'my_theme_register_required_plugins');
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function my_theme_register_required_plugins()
{
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name' => 'WP Migrate DB',
            'slug' => 'wp-migrate-db',
            'required' => false,
        ),
        array(
            'name' => 'Wp-scss',
            'slug' => 'wp-scss',
            'required' => false,
        ),
        array(
            'name' => 'Yoast SEO',
            'slug' => 'wordpress-seo',
            'required' => false,
        ),
        array(
            'name' => 'ACF Content Analysis for Yoast SEO',
            'slug' => 'acf-content-analysis-for-yoast-seo',
            'required' => false,
        ),
        array(
            'name' => 'TinyMCE Advanced',
            'slug' => 'tinymce-advanced',
            'required' => false,
        ),
        array(
            'name' => 'Change Case',
            'slug' => 'change-case-for-tinymce',
            'required' => false,
        ),
        array(
            'name' => 'AJAX Thumbnail Rebuild',
            'slug' => 'ajax-thumbnail-rebuild',
            'required' => false,
        ),
        array(
            'name' => 'Advanced Custom Fields: PRO',
            'slug' => 'advanced-custom-fields-pro',
            'source' => get_stylesheet_directory() . '/include/plugins/advanced-custom-fields-pro.zip',
            'required' => true,
            'version' => '',
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url' => 'http://www.advancedcustomfields.com/pro/', // If set, overrides default API URL and points to an external URL.
        ),
        array(
            'name' => 'Classic Editor',
            'slug' => 'classic-editor',
            'source' => get_stylesheet_directory() . '/include/plugins/classic-editor.zip',
            'required' => false,
            'version' => '',
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
        ),
    );
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id' => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'plugins.php',            // Parent menu slug.
        'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true,                    // Show admin notices or not.
        'dismissable' => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message' => '',                      // Message to output right before the plugins table.
    );
    tgmpa($plugins, $config);
}

//Show empty categories in category widget  TEST THIS
//function show_empty_categories_links($args) {
//    $args['hide_empty'] = 0;
//    return $args;
//}
//add_filter('widget_categories_args','show_empty_categories_links');
////remove empty title from widget
//function foo_widget_title($title) {
//    return $title == '&nbsp;' ? '' : $title;
//}
//add_filter('widget_title', 'foo_widget_title');