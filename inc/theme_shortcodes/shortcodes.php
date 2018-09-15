<?php if ( in_array( 'sb_framework/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
    //require trailingslashit( get_template_directory () )  . 'inc/theme_shortcodes/icons/icons.php';
	/* ------------------------------------------------ */
	/* Common Shortcode */
	/* ------------------------------------------------ */
	if ( class_exists ( 'Redux' )) {
    	if( Redux::getOption('adforest_theme', 'design_type') == 'modern' )
    	{
     		require trailingslashit( get_template_directory_child () )  . 'inc/theme_shortcodes/shortcodes/modern/search_modern.php';
    	}
    	else
    	{
    	}
    	require trailingslashit( get_template_directory_child () )  . 'inc/theme_shortcodes/classes/ads.php';
     	require trailingslashit( get_template_directory_child () )  . 'inc/theme_shortcodes/classes/ad_post.php';
	}
}