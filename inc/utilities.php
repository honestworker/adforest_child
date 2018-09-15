<?php

trait adforest_reuse_functions {
	function adforect_widget_open( $instance )
	{
		global $adforest_theme;
		if( isset( $adforest_theme['search_design'] ) && $adforest_theme['search_design'] == 'sidebar' ) {
			$open_widget	=	0;
			if ( isset( $instance[ 'open_widget' ] ) ) {
			    $open_widget = $instance[ 'open_widget' ];
			}
			
			$open_selected	= $close_selected	=	 '';
			if( $open_widget == '1' )
				$open_selected	=	'selected="selected"';
			else
				$close_selected	=	'selected="selected"';
				
		    echo $open_html = '<p>
		        <label for="'.esc_attr( $this->get_field_id( 'open_widget' ) ).'" >
		            '.esc_html__( 'Widget behaviour:', 'adforest' ) .'
		        </label> 
		        <select  class="widefat" id="'.esc_attr( $this->get_field_id( 'open_widget' ) ).'" name="'.esc_attr( $this->get_field_name( 'open_widget' ) ).'">
			        <option value="1"'. esc_attr($open_selected).'>'.__( 'Open', 'adforest' ).'</option>
			        <option value="0"'.esc_attr($close_selected).'>'.__( 'Close', 'adforest' ).'</option>
		        </select>
	        </p>';
		}
	}
}

function adforest_adExtraInfo($id = '', $class = 'negotiable', $row = 6) {
    global $wpdb;
    $rows = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = '$pid' AND meta_key LIKE '_sb_extra_%'" );
    foreach( $rows as $row ) {
        $caption	=	explode( '_', $row->meta_key );
        if( $row->meta_value == "" ) {
            continue;
        }
        ?>
        <div class="col-sm-<?php echo esc_attr($feature_col); ?> col-md-<?php echo esc_attr($feature_col); ?> col-xs-12 no-padding">
            <span><strong><?php echo esc_html( ucfirst( $caption[3] ) ); ?></strong> :</span>
            <?php echo esc_html( $row->meta_value ); ?>
        </div>
        <?php		
    }
}

function adforest_pagination_modern_search($max_num_pages, $paged) {
	if( $max_num_pages <= 1 )
		return;
	
	$pagination_html = '';
	/** Stop execution if there's only 1 page */
	if( $max_num_pages <= 1 )
		return;

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max_num_pages ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	$pagination_html .= '<ul class="pagination pagination-large">' . "\n";

	if ( $paged > 1 )
		$pagination_html .= '<li><a id="privious-page">'.__('Previous Page', 'adforest').'</a></li>' . "\n";
	
	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';
        $pagination_html .= '<li '. $class .'><a class="search-page-num" href="javascript:void(0)" value="1">1</a></li>' . "\n";

	    if ( ! in_array( 2, $links ) )
            $pagination_html .= '<li '. $class .'><a class="search-page-num" href="javascript:void(0)">...</a></li>' . "\n";
    }

    /**	Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link )
    {
	    $class = $paged == $link ? ' class="active"' : '';
        $pagination_html .= '<li '. $class .'><a class="search-page-num" href="javascript:void(0)" value="'. $link .'">'. $link .'</a></li>' . "\n";
    }

    /**	Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max_num_pages, $links ) ) 
    {
	    if ( ! in_array( $max_num_pages - 1, $links ) )
		    $pagination_html .= '<li><a class="search-page-num" href="javascript:void(0);">...</a></li>' . "\n";
		    
	    $class = $paged == $max_num_pages ? ' class="active"' : '';
        $pagination_html .= '<li '. $class .'><a class="search-page-num" href="javascript:void(0)" value="'. $max_num_pages .'">'. $max_num_pages .'</a></li>' . "\n";
    }

    if ( $paged < $max_num_pages )
		$pagination_html .= '<li><a id="next-page">'.__('Next Page &raquo;', 'adforest').'</a></li>' . "\n";
    $pagination_html .= '</ul>' . "\n";
	
	return $pagination_html;
}

/* ------------------------------------------------ */
/* Pagination Search Modern*/
/* ------------------------------------------------ */
function adforest_pagination_modern_search1($query) {
    if( is_singular() )
        return;
    
    $pagination_html = '';

    /** Stop execution if there's only 1 page */
    if( $query->max_num_pages <= 1 )
        return;

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $query->max_num_pages );

        /**	Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;
    
        /**	Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
    
        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
    
        $pagination_html .= '<ul class="pagination pagination-large">' . "\n";
    
        if ( get_previous_posts_link() )
            $pagination_html .= '<li>' . get_previous_posts_link() . '</li>' . "\n";
        
        /**	Link to first page, plus ellipses if necessary */
        if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class="active"' : '';
    
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url( get_pagenum_link( 1 )) . '">1</a></li>' . "\n";

        if ( ! in_array( 2, $links ) )
        $pagination_html .=  '<li><a href="javascript:void(0);">...</a></li>';
    }

    /**	Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) 
    {
        $class = $paged == $link ? ' class="active"' : '';
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url( get_pagenum_link( $link )) . '">' . $link . '</a></li>' . "\n";
    }

    /**	Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) 
    {
        if ( ! in_array( $max - 1, $links ) )
        $pagination_html .=  '<li><a href="javascript:void(0);">...</a></li>' . "\n";
        $class = $paged == $max ? ' class="active"' : '';
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url( get_pagenum_link( $max )) . '">' . $max . '</a></li>' . "\n";
    }

    if ( get_next_posts_link() )
        $pagination_html .= '<li>' . get_next_posts_link() . '</li>' . "\n";
        
    $pagination_html .=  '</ul>' . "\n";
    
    return $pagination_html;
}

function adforest_adPrice($id = '', $class = 'negotiable')
{
	if( get_post_meta($id, '_adforest_ad_price', true ) == "" && get_post_meta($id, '_adforest_ad_price_type', true ) == "on_call" )
	{
		return __("Price On Call", 'adforest');
	}
	if( get_post_meta($id, '_adforest_ad_price', true ) == "" && get_post_meta($id, '_adforest_ad_price_type', true ) == "free" )
	{
		return __("Free", 'adforest');
	}

	if( get_post_meta($id, '_adforest_ad_price', true ) == "" || get_post_meta($id, '_adforest_ad_price_type', true ) == "no_price" )
	{
		return '';	
	}
	
	$price  = 0;
	global $adforest_theme;
	$thousands_sep = ",";
	if( isset( $adforest_theme['sb_price_separator'] ) && $adforest_theme['sb_price_separator'] != "" )
	{
		$thousands_sep = $adforest_theme['sb_price_separator'];
	}
	$decimals = 0;
	if( isset( $adforest_theme['sb_price_decimals'] ) && $adforest_theme['sb_price_decimals'] != "" )
	{
		$decimals = $adforest_theme['sb_price_decimals'];
	}
	$decimals_separator = ".";
	if( isset( $adforest_theme['sb_price_decimals_separator'] ) && $adforest_theme['sb_price_decimals_separator'] != "" )
	{
		$decimals_separator = $adforest_theme['sb_price_decimals_separator'];
	}
	$curreny = $adforest_theme['sb_currency'];
	if( get_post_meta($id, '_adforest_ad_currency', true ) != "" )
	{
		$curreny = get_post_meta($id, '_adforest_ad_currency', true );
	}
	
	if($id != "")
	{
		if (is_numeric(get_post_meta($id, '_adforest_ad_price', true ))) {
			$price  = number_format( get_post_meta($id, '_adforest_ad_price', true ), $decimals, $decimals_separator, $thousands_sep  );
		}
		
		$price  = ( isset( $price ) && $price != "") ? $price : 0;	
		
		if( isset($adforest_theme['sb_price_direction']) && $adforest_theme['sb_price_direction'] == 'right' )
		{
			$price =  $price . $curreny;
		}	
		else if( isset($adforest_theme['sb_price_direction']) && $adforest_theme['sb_price_direction'] == 'right_with_space' )
		{
			$price =  $price . " " . $curreny;
		}	
		else if( isset($adforest_theme['sb_price_direction']) && $adforest_theme['sb_price_direction'] == 'left' )
		{
			$price =  $curreny . $price;
		}	
		else if( isset($adforest_theme['sb_price_direction']) && $adforest_theme['sb_price_direction'] == 'left_with_space' )
		{
			$price =  $curreny . " " . $price;
		}	
		else
		{
			$price =  $curreny . $price;	
		}
		
	}
	// Price type fixed or ...
	$price_type_html = '';
	if( get_post_meta($id, '_adforest_ad_price_type', true ) != "" && isset( $adforest_theme['allow_price_type'] ) && $adforest_theme['allow_price_type'] )
	{
		$price_type = '';
		if( get_post_meta($id, '_adforest_ad_price_type', true ) == 'Fixed' )
		{
			$price_type	=	__('Fixed','adforest');	
		}
		else if( get_post_meta($id, '_adforest_ad_price_type', true ) == 'Negotiable' )
		{
			$price_type	=	__('Negotiable','adforest');
		}
		else if( get_post_meta($id, '_adforest_ad_price_type', true ) == 'auction' )
		{
			$price_type	=	__('Auction','adforest');
		}
		else
		{
			$price_type	=	get_post_meta($id, '_adforest_ad_price_type', true );
		}
			$price_type_html	=	'<span class="'.esc_attr($class).'">('.$price_type.')</span>';
	}
	
	return $price;
}