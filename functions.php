<?php

// create a URL to the child theme
function get_template_directory_child() {
    $directory_template = get_template_directory(); 
    $directory_child = str_replace('adforest', '', $directory_template) . 'adforest-child/';

    return $directory_child;
}

// create a URL to the child theme
function get_template_directory_child_uri() {
    $directory_template = get_template_directory_uri(); 
    $directory_child = str_replace('adforest', '', $directory_template) . 'adforest-child/';

    return $directory_child;
}

/* ------------------------------------------------------------------------------------------------ */
/* function adforest_setup()
/* ------------------------------------------------------------------------------------------------ */

/* ------------------------------------------------ */
/* Theme Utilities */ 
/* ------------------------------------------------ */

require trailingslashit( get_template_directory_child () ) . 'inc/utilities.php';
					
/* ------------------------------------------------ */
/* Theme Shortcodes */ 
/* ------------------------------------------------ */

require trailingslashit( get_template_directory_child () ) . 'inc/theme_shortcodes/shortcodes.php';

// Override the functions and classes of parent theme here.
			
/* ------------------------------------------------ */
/* Theme Nav */ 
/* ------------------------------------------------ */

require trailingslashit( get_template_directory_child () ) . 'inc/nav.php';

/* ------------------------------------------------ */
/* Search Widgets */
/* ------------------------------------------------ */

require trailingslashit( get_template_directory_child () ) . 'inc/ads-widgets.php';

/* ------------------------------------------------------------------------------------------------ */
/* function adforest_scripts()
/* ------------------------------------------------------------------------------------------------ */

wp_register_script( 'adforest-search-modern', trailingslashit( get_template_directory_child_uri () ) . 'js/search-modern.js', false, false, true );

$data = get_option('GTranslate');
if (!empty($data['show_in_menu']))
{
    wp_enqueue_script( 'gtranslate-megemenu-js', trailingslashit( get_template_directory_uri () ) . '../../plugins/gtranslate/gtranslate-megamenu.js' , false, false, true);
}