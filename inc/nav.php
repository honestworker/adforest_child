<?php
function adforest_themeMenu($theme_location)
{
    $menu_html = '';
    if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
	
	    $menu = get_term( $locations[$theme_location], 'nav_menu' );	
    	if( isset($menu->term_id) )	{
    		$menu_items = wp_get_nav_menu_items($menu->term_id);
    		foreach( $menu_items as $item )	{
    			if( $item->menu_item_parent == 0 ) {	
    				$level1 = 1;
    				$menuItems1  = adforest_getMenuItemsData($item->ID, $item, 1);
    				$menu_html .= '<li>';
    				$menu_html .= 	$menuItems1['achor'];	
    				$lvlHTMLClose = '';
    				foreach( $menu_items as $sub_item ) {
    					if( $item->ID == $sub_item->menu_item_parent ) {
    						$mainMenuClass  =  $menuItems1['class'];
    						$mainMenuClass2 = $menuItems1['cols'];
    		
    						/* For Mega Menu  && Has Parent Starts*/		
    						if($mainMenuClass == 'drop-down' || $mainMenuClass == 'drop-down-multilevel') {
    							$menuItems2 = adforest_getMenuItemsData($sub_item->ID, $sub_item, 2);
    							if( $level1 == 1 ) {	
    								$menu_html .= $menuItems1['wrapHTMLStarts'];
    								$lvlHTMLClose = $menuItems1['wrapHTMLEnds'];
    							}
    							if( $mainMenuClass == 'drop-down-multilevel' ) {		
    								$menu_html .= '<li class="hoverTrigger">';
    								$menu_html .= 	$menuItems2['achor'];
    							}
    				
    							$mega_menu_html = '';
    							$megamenu_cols = ($menuItems2['cols'] != "") ? $menuItems2['cols'] : 'grid-col-4';
    							$level2 = 1;
    							$closeHTML = 'no';
    							//megamenu_open_side
    							$openSide = $menuItems2['megamenu_open_side'];
    							foreach( $menu_items as $sub_sub_item ) {
    								if( $sub_item->ID == $sub_sub_item->menu_item_parent )	 {
    									if( $level2 == 1 ) {
    									    $isShowTitle = '<h4>'.esc_html($menuItems2['megamenu_menu_title']).'</h4>';
    									    if( $menuItems2['megamenu_menu_title'] == '{HideMe}' ) { $isShowTitle = ''; }	
    						                $mega_menu_html .= ($mainMenuClass == 'drop-down-multilevel') ? '<ul class="drop-down-multilevel '.$megamenu_cols.' '. $openSide. '">' : '<div class="'.esc_attr($megamenu_cols).'">'.$isShowTitle.'<ul>';
    									}	
    									$show_indicator = ($mainMenuClass == 'drop-down-multilevel') ? false : true;	
    									$menuItems3 = adforest_getMenuItemsData($sub_sub_item->ID, $sub_sub_item, 3, $show_indicator);
    									$mega_menu_html .= '<li>';	
    									$mega_menu_html .= $menuItems3['achor'];
    									$mega_menu_html .= '</li>';														
    									$closeHTML = 'yes';
    									$level2++;
    								}						
    							}
    							if( $closeHTML == 'yes' )  { 
    								$mega_menu_html .= ($mainMenuClass == 'drop-down-multilevel') ? '</ul>' : '</ul></div>'; 
    								$closeHTML == 'no'; 
    							}
    							$menu_html .= $mega_menu_html;
    							$level1++;
    						}
    						/* For Mega Menu  && Has Parent Ends*/
    					}			
    					
    				}
    				if( $lvlHTMLClose != "" ){ $menu_html .= $lvlHTMLClose; $lvlHTMLClose = ''; }
    				$menu_html .= '</li>';
    			}	
    		}
	        $menu_html = apply_filters( "adforest_nav_menu_items", $menu_html, null );
    	} else {
    	 $menu_html .= '<li><a href="'.esc_url( home_url() ).'">'.__("Home", "adforest").'</a></li>'; 
    	}
    } else {
        $menu_html .= '<li><a href="'.esc_url( home_url() ).'">'.__("Home", "adforest").'</a></li>'; 
    }
    echo($menu_html);
}