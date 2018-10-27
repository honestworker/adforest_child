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
    function gtranslate_menu_item_x($items, $args) {
        $data = get_option('GTranslate');
        //GTranslate::load_defaults($data);
       
        $enabled_languages = array();
        $enabled_languages = $data['fincl_langs'];
        
        $wp_plugin_url = preg_replace('/^https?:/i', '', plugins_url() . '/gtranslate');
        
        //adding enabled languages
        $gt_lang_array = array("af"=>"Afrikaans","sq"=>"Albanian","am"=>"Amharic","ar"=>"Arabic","hy"=>"Armenian","az"=>"Azerbaijani","eu"=>"Basque","be"=>"Belarusian","bn"=>"Bengali","bs"=>"Bosnian","bg"=>"Bulgarian","ca"=>"Catalan","ceb"=>"Cebuano","ny"=>"Chichewa","zh-CN"=>"Chinese (Simplified)","zh-TW"=>"Chinese (Traditional)","co"=>"Corsican","hr"=>"Croatian","cs"=>"Czech","da"=>"Danish","nl"=>"Dutch","en"=>"English","eo"=>"Esperanto","et"=>"Estonian","tl"=>"Filipino","fi"=>"Finnish","fr"=>"French","fy"=>"Frisian","gl"=>"Galician","ka"=>"Georgian","de"=>"German","el"=>"Greek","gu"=>"Gujarati","ht"=>"Haitian Creole","ha"=>"Hausa","haw"=>"Hawaiian","iw"=>"Hebrew","hi"=>"Hindi","hmn"=>"Hmong","hu"=>"Hungarian","is"=>"Icelandic","ig"=>"Igbo","id"=>"Indonesian","ga"=>"Irish","it"=>"Italian","ja"=>"Japanese","jw"=>"Javanese","kn"=>"Kannada","kk"=>"Kazakh","km"=>"Khmer","ko"=>"Korean","ku"=>"Kurdish (Kurmanji)","ky"=>"Kyrgyz","lo"=>"Lao","la"=>"Latin","lv"=>"Latvian","lt"=>"Lithuanian","lb"=>"Luxembourgish","mk"=>"Macedonian","mg"=>"Malagasy","ms"=>"Malay","ml"=>"Malayalam","mt"=>"Maltese","mi"=>"Maori","mr"=>"Marathi","mn"=>"Mongolian","my"=>"Myanmar (Burmese)","ne"=>"Nepali","no"=>"Norwegian","ps"=>"Pashto","fa"=>"Persian","pl"=>"Polish","pt"=>"Portuguese","pa"=>"Punjabi","ro"=>"Romanian","ru"=>"Russian","sm"=>"Samoan","gd"=>"Scottish Gaelic","sr"=>"Serbian","st"=>"Sesotho","sn"=>"Shona","sd"=>"Sindhi","si"=>"Sinhala","sk"=>"Slovak","sl"=>"Slovenian","so"=>"Somali","es"=>"Spanish","su"=>"Sudanese","sw"=>"Swahili","sv"=>"Swedish","tg"=>"Tajik","ta"=>"Tamil","te"=>"Telugu","th"=>"Thai","tr"=>"Turkish","uk"=>"Ukrainian","ur"=>"Urdu","uz"=>"Uzbek","vi"=>"Vietnamese","cy"=>"Welsh","xh"=>"Xhosa","yi"=>"Yiddish","yo"=>"Yoruba","zu"=>"Zulu");
        
        $items .= '<li class="hoverTrigger main">';
        if ( count( $enabled_languages > 1 ) ) {
            $items .= '<a id="main-lang" href="#" title="' . $gt_lang_array[$data["default_language"]] . '"><img height="16" width="16" alt="en" src="'. $wp_plugin_url .'/flags/16/'. $data["default_language"] .'.png">'
            . $gt_lang_array[$data["default_language"]] . '<i class="fa fa-angle-down fa-indicator"></i></a>';
            $items .= '<ul class="drop-down-multilevel grid-col-12 effect-expand-top" style="transition: all 400ms ease;">';
        }
        
        foreach( $enabled_languages as $lang ) {
            $items .= '<li class="hoverTrigger switcher"><a href="#" base_url=\'' . plugins_url() . '/gtranslate\' onclick="doGTranslate(\''. $data['default_language'] . '|' . $lang .'\'); doChangeMain(jQuery(this).attr(\'base_url\'), \''. $lang . '\', \'' . $gt_lang_array[$lang] .'\')" title="' . $gt_lang_array[$lang] . '">';
            $items .= '<img data-gt-lazy-src="'. $wp_plugin_url .'/flags/16/'. $lang .'.png" height="16" width="16" alt="en" src="'. $wp_plugin_url .'/flags/16/'. $lang .'.png"> ' . $gt_lang_array[$lang] . '</a>';
            $items .= '</li>';
        }
        if ( count( $enabled_languages ) > 1 ) {
            $items .= '</ul>';
        }
        
        $items .= '<div>
                <div id="google_translate_element2"></div>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
                </div>';
        $items .= '</li>';
        
        return $items;
    }
    add_filter('adforest_nav_menu_items', 'gtranslate_menu_item_x', 10, 2);
    
    wp_enqueue_script( 'gtranslate-megemenu-js', trailingslashit( get_template_directory_child_uri () ) . 'js/gtranslate/gtranslate-megamenu.js' , false, false, true);
    
    wp_enqueue_style( 'gtranslate-megemenu-css', trailingslashit( get_template_directory_child_uri () )  . 'css/gtranslate/gtranslate-megamenu.css' , false, false, true);
}