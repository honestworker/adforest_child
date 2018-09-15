<?php

// Get modern search result
add_action('wp_ajax_sb_search_modern_description', 'adforest_search_modern_description');
add_action( 'wp_ajax_nopriv_sb_search_modern_description', 'adforest_search_modern_description' );
function adforest_search_modern_description() {
	$cat_id	= $_POST['cat_id'];
	$category_description = category_description($cat_id);
    wp_send_json($category_description);
	die();
}

// Get modern search result
add_action('wp_ajax_sb_search_modern', 'adforest_search_modern');
add_action( 'wp_ajax_nopriv_sb_search_modern', 'adforest_search_modern' );
function adforest_search_modern() {
	$cat_id	=	$_POST['cat_id'];
	$ad_cats	=	adforest_get_cats('ad_cats' , $cat_id );
	$res	=	'';

	$is_active	=	array(
		'key'     => '_adforest_ad_status_',
		'value'   => 'active',
		'compare' => '=',
	);
	
	$is_price = '';
	if( isset( $_POST['min_price'] ) && $_POST['min_price'] != "" ) {
		if( isset( $_POST['max_price'] ) && $_POST['max_price'] != "" ) {
		    $is_price	=	array(
		        'key'     => '_adforest_ad_price',
    			'value'   => array( $_POST['min_price'], $_POST['max_price'] ),
    			'type'    => 'numeric',
    			'compare' => 'BETWEEN',
    		);
		}
	}
	
	$paged = 1;
	if( isset( $_POST['paged'] ) && $_POST['paged'] != "" ) {
	    $paged = trim( $_POST['paged'] );
	}
	
	$is_location =	'';
	if( isset( $_POST['location'] ) && $_POST['location'] != "" ) {
		$is_location  =	array(
			'key'     => '_adforest_ad_location',
			'value'   => trim( $_POST['location'] ),
			'compare' => 'LIKE',
		);
	}
	
	$is_category = array(
		array(
		'taxonomy' => 'ad_cats',
		'field'    => 'term_id',
		'terms'    => $cat_id,
		),
	);
	
	//	$is_price, 	$is_location
	$args = array( 
	    'post_type' => 'ad_post',
	    'post_status' => 'publish',
		'meta_query' => array(
			$is_active,
		),
		'tax_query' => array(
			$is_category,
			$is_price,
			$is_location
		),
	    'paged' => $paged,
	);
	
    $results = new WP_Query( $args );
    $ads_html = "";
    while( $results->have_posts() ) {
        $results->the_post();
 	    $ads = new ads();
 		$ads_html .= $ads->adforest_search_layout_list( get_the_ID(), false );
    }
    
    $pagination_html = adforest_pagination_modern_search($results->max_num_pages, $paged);
    
    wp_send_json(array('ads' => $ads_html, 'pagination' => $pagination_html, 'page_num' => $results->max_num_pages));
    
	die();
}


// Update AD share message
add_action('wp_ajax_update_ad_share_message', 'adforest_ad_update_share_message');
add_action( 'wp_ajax_nopriv_update_ad_share_message', 'adforest_ad_update_share_message' );
function adforest_ad_update_share_message() {
    $uid = get_current_user_id();
    
	if( !is_super_admin( $uid ) ) {
		echo '0|' . __( "You are not logged in as administrator.", 'adforest' );
		
		die();
	} else {
 	    $message = "";
 	    $post_id  = "0";
 	    
 	    $message = $_POST[ 'message' ];
 	        
    	$params =  array(
    		'post_type' => 'ad_share_message'
    	);					
    		
    	$share_message_posts	=	get_posts( $params );
    	
    	if ( is_array( $share_message_posts ) ) {
    	    $post_id = $share_message_posts[0]->ID;
    	    
        	$share_message_post = array(
        	    'ID' => $post_id,
            	'post_title'   => sanitize_text_field( "AD Share Message" ),
            	'post_status'   => 'publish',
            	'post_type'   => 'ad_share_message',
            	'post_name'      => date("Y-m-d"),
            	'post_content'   => $message
        	);
        	
            $post_id = wp_update_post( $share_message_post );
    	}
    	
// 	    $ad_pid = $_POST['ad_pid'];
// 	    $message = $_POST['message'];
// 	    update_post_meta( $ad_pid, '_adforest_ad_share_message', $message );
// 	    update_post_meta( $ad_pid, '_adforest_ad_share_message_status', 1 );
// 	    update_post_meta( $ad_pid, '_adforest_ad_share_message_date', date("mdY") );
        
        echo '1|' . __( "Your share message has been updated.", 'adforest' );
        
		die();
	}
}