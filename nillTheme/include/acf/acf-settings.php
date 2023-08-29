<?php
/* ACF Google Api Key */
function my_acf_init() {
    acf_update_setting( 'google_api_key', 'AIzaSyDJDJXR-HrdxKzxVn0HhmRyjnLLvTZU4cU' );

}
add_action( 'acf/init', 'my_acf_init' );

/* Add Options Pages NEED TEST */
if ( function_exists( 'acf_add_options_page' ) ) {
    // add parent
    $parent = acf_add_options_page( array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'redirect'   => false
    ) );
    // add sub page
//    acf_add_options_sub_page( array(
//        'page_title'  => 'Global components',
//        'menu_title'  => 'Components',
//        'parent_slug' => $parent['menu_slug'],
//    ) );
}

/* ACF Repeater Styles */
function acf_repeater_even() {
    $scheme = get_user_option( 'admin_color' );
    $color = '';
    if($scheme == 'fresh') {
        $color = '#0073aa';
    } else if($scheme == 'light') {
        $color = '#d64e07';
    } else if($scheme == 'blue') {
        $color = '#52accc';
    } else if($scheme == 'coffee') {
        $color = '#59524c';
    } else if($scheme == 'ectoplasm') {
        $color = '#523f6d';
    } else if($scheme == 'midnight') {
        $color = '#e14d43';
    } else if($scheme == 'ocean') {
        $color = '#738e96';
    } else if($scheme == 'sunrise') {
        $color = '#dd823b';
    }
    echo '<style>.acf-repeater > table > tbody > tr:nth-child(even) > td.order {color: #fff !important;background-color: '.$color.' !important; text-shadow: none}.acf-fc-layout-handle {color: #fff !important;background-color: #23282d!important; text-shadow: none}</style>';
}
add_action('admin_footer', 'acf_repeater_even');

/*
/* ACF Local JSON load point   NEED TEST
   ========================================================================== */
//add_filter( 'acf/settings/load_json', 'my_acf_json_load_point' );
//function my_acf_json_load_point( $paths ) {
//    // remove original path (optional)
//    unset( $paths[0] );
//    // append path
//    $paths[] = get_stylesheet_directory() . '/include/acf/load_point';
//
//    // return
//    return $paths;
//}
//
//add_filter( 'acf/settings/save_json', 'my_acf_json_save_point' );
//function my_acf_json_save_point( $path ) {
//    // update path
//    $path = get_stylesheet_directory() . '/include/acf/save_point';
//
//    // return
//    return $path;
//}

//DEFAULT THEME SETTINGS FIELDS
//add_action( 'acf/include_fields', function() {
//    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
//        return;
//    }
//
//    acf_add_local_field_group( array(
//        'key' => 'group_64edf526ac540',
//        'title' => 'Theme Settings',
//        'fields' => array(
//            array(
//                'key' => 'field_64edf5502b984',
//                'label' => 'Header',
//                'name' => '',
//                'aria-label' => '',
//                'type' => 'accordion',
//                'instructions' => '',
//                'required' => 0,
//                'conditional_logic' => 0,
//                'wrapper' => array(
//                    'width' => '',
//                    'class' => '',
//                    'id' => '',
//                ),
//                'open' => 0,
//                'multi_expand' => 0,
//                'endpoint' => 0,
//            ),
//            array(
//                'key' => 'field_64edf56f2b985',
//                'label' => 'Logo',
//                'name' => 'logo_header',
//                'aria-label' => '',
//                'type' => 'image',
//                'instructions' => '',
//                'required' => 0,
//                'conditional_logic' => 0,
//                'wrapper' => array(
//                    'width' => '',
//                    'class' => '',
//                    'id' => '',
//                ),
//                'return_format' => 'id',
//                'library' => 'all',
//                'min_width' => '',
//                'min_height' => '',
//                'min_size' => '',
//                'max_width' => '',
//                'max_height' => '',
//                'max_size' => '',
//                'mime_types' => '',
//                'preview_size' => 'medium',
//            ),
//            array(
//                'key' => 'field_64edf5892b986',
//                'label' => 'Social Icons',
//                'name' => 'some',
//                'aria-label' => '',
//                'type' => 'repeater',
//                'instructions' => '',
//                'required' => 0,
//                'conditional_logic' => 0,
//                'wrapper' => array(
//                    'width' => '',
//                    'class' => '',
//                    'id' => '',
//                ),
//                'layout' => 'table',
//                'pagination' => 0,
//                'min' => 0,
//                'max' => 0,
//                'collapsed' => '',
//                'button_label' => 'Add Row',
//                'rows_per_page' => 20,
//                'sub_fields' => array(
//                    array(
//                        'key' => 'field_64edf5a32b987',
//                        'label' => 'Icon',
//                        'name' => 'icon',
//                        'aria-label' => '',
//                        'type' => 'text',
//                        'instructions' => '',
//                        'required' => 0,
//                        'conditional_logic' => 0,
//                        'wrapper' => array(
//                            'width' => '',
//                            'class' => '',
//                            'id' => '',
//                        ),
//                        'default_value' => '',
//                        'maxlength' => '',
//                        'placeholder' => '',
//                        'prepend' => '',
//                        'append' => '',
//                        'parent_repeater' => 'field_64edf5892b986',
//                    ),
//                    array(
//                        'key' => 'field_64edf5a92b988',
//                        'label' => 'Link',
//                        'name' => 'link',
//                        'aria-label' => '',
//                        'type' => 'text',
//                        'instructions' => '',
//                        'required' => 0,
//                        'conditional_logic' => 0,
//                        'wrapper' => array(
//                            'width' => '',
//                            'class' => '',
//                            'id' => '',
//                        ),
//                        'default_value' => '',
//                        'maxlength' => '',
//                        'placeholder' => '',
//                        'prepend' => '',
//                        'append' => '',
//                        'parent_repeater' => 'field_64edf5892b986',
//                    ),
//                ),
//            ),
//            array(
//                'key' => 'field_64ee0dc8794fb',
//                'label' => 'Footer',
//                'name' => '',
//                'aria-label' => '',
//                'type' => 'accordion',
//                'instructions' => '',
//                'required' => 0,
//                'conditional_logic' => 0,
//                'wrapper' => array(
//                    'width' => '',
//                    'class' => '',
//                    'id' => '',
//                ),
//                'open' => 0,
//                'multi_expand' => 0,
//                'endpoint' => 0,
//            ),
//            array(
//                'key' => 'field_64ee0de3794fc',
//                'label' => 'Logo',
//                'name' => 'logo_footer',
//                'aria-label' => '',
//                'type' => 'image',
//                'instructions' => '',
//                'required' => 0,
//                'conditional_logic' => 0,
//                'wrapper' => array(
//                    'width' => '',
//                    'class' => '',
//                    'id' => '',
//                ),
//                'return_format' => 'id',
//                'library' => 'all',
//                'min_width' => '',
//                'min_height' => '',
//                'min_size' => '',
//                'max_width' => '',
//                'max_height' => '',
//                'max_size' => '',
//                'mime_types' => '',
//                'preview_size' => 'medium',
//            ),
//        ),
//        'location' => array(
//            array(
//                array(
//                    'param' => 'options_page',
//                    'operator' => '==',
//                    'value' => 'acf-options-theme-settings',
//                ),
//            ),
//        ),
//        'menu_order' => 0,
//        'position' => 'normal',
//        'style' => 'default',
//        'label_placement' => 'top',
//        'instruction_placement' => 'label',
//        'hide_on_screen' => '',
//        'active' => true,
//        'description' => '',
//        'show_in_rest' => 0,
//    ) );
//} );

