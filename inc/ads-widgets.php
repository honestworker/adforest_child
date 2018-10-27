<?php

// Featured Ads
class adforest_search_featured_ad extends WP_Widget {
    use adforest_reuse_functions;
    /**
     * Register widget with WordPress.
    */
	function __construct() {
		$widget_ops = array( 
			'classname' => 'adforest_search_featured_ad',
			'description' => __('Only for search and single ad sidebar.', 'adforest'),
		);
		// Instantiate the parent object
		parent::__construct( false, __('Ad Featured', 'adforest' ), $widget_ops );
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$max_ads	=	$instance['max_ads'];
		global $adforest_theme;
		if( isset( $adforest_theme['design_type'] ) && $adforest_theme['design_type'] == 'modern' ) {
    		if( !is_singular( 'ad_post' ) && isset( $adforest_theme['search_design'] ) ) {
    			if( is_page_template( 'page-search.php' ) && ($adforest_theme['search_design'] == 'map' ||  $adforest_theme['search_design'] == 'topbar' ) ) 
    				return;
    		}
		}
		/*
		
        <div class="ad-info row">
            <ul>
               <?php 
                    if(function_exists("adforestCustomFieldsHTML")) {
                        echo adforestCustomFieldsHTML(get_the_ID(), 6, 1);
                    }
                ?>
            </ul>
        </div>
        */
        ?>
        <div class="panel panel-default">
            <!-- Heading -->
            <div class="panel-heading" >
                <h4 class="panel-title">
                    <a>
                    <?php echo esc_html( $instance['title'] ); ?>
                    </a>
                </h4>
            </div>
            <!-- Content -->
            <div class="panel-collapse">
                <div class="panel-body recent-ads">
                    <div class="featured-slider-3 owl-carousel owl-theme owl-responsive-1000 owl-loaded">
                    <!-- Featured Ads -->
                    <?php
    					$f_args =  array( 
        					'post_type' => 'ad_post',
        					'post_status' => 'publish',
        					'posts_per_page' => $max_ads,
        					'meta_query' => array(
        					    array(
                					'key'     => '_adforest_is_feature',
                					'value'   => 1,
                					'compare' => '=',
    					        ),
    					    ),
    					    'orderby'        => 'rand',
    					);
        				$f_ads = new WP_Query( $f_args );
        				if ( $f_ads->have_posts() ) {
        					$number	= 0;
        					while ( $f_ads->have_posts() ) {
        						$f_ads->the_post();
        						$pid	=	get_the_ID();
        						$author_id = get_post_field( 'post_author', $pid );;
        						$author = get_user_by( 'ID', $author_id );
        						
        					    $img	=	$adforest_theme['default_related_image']['url']; 
        						$media	=	 adforest_get_ad_images($pid);
        						$total_imgs	=	count( $media );
        						if( count( $media ) > 0 ) {
        							foreach( $media as $m ) {
        								$mid	=	'';
        								if ( isset( $m->ID ) )
        									$mid	= 	$m->ID;
        								else
        									$mid	=	$m;
        								$image  = wp_get_attachment_image_src( $mid, 'adforest-ad-related');
        								$img	=	$image[0];
        								break;
        							}
        						} 
        						
    							$timer_html	= '';
    							$bid_end_date	=	get_post_meta($pid, '_adforest_ad_bidding_date', true );
    							if( $bid_end_date != "" &&  date('Y-m-d H:i:s') < $bid_end_date ) {
    								$timer_html	.=	'<div class="listing-bidding">' . adforest_timer_html($bid_end_date, false) . '</div>';
    							}
        			        ?>
                        <div class="item">
                            <div class="col-md-12 col-xs-12 col-sm-12 no-padding">
                                <!-- Ad Box -->
                                <div class="category-grid-box">
                                    <!-- Ad Img -->
                                    <div class="category-grid-img">
                                    <?php echo $timer_html; ?>
                                    <img class="img-responsive" alt="<?php echo get_the_title(); ?>" src="<?php echo esc_url( $img ); ?>">
                                    <!-- Ad Status -->
                                    <!-- User Review -->
                                    <?php echo adforest_video_icon(); ?>
                                    <div class="user-preview">
                                        <a href="<?php echo get_author_posts_url( $author_id ); ?>?type=ads">
                                            <img src="<?php echo adforest_get_user_dp( $author_id ); ?>" class="avatar avatar-small" alt="<?php echo get_the_title(); ?>">
                                        </a>
                                    </div>
                                    <!-- View Details -->
                                    <a href="<?php echo get_the_permalink(); ?>" class="view-details">
                                        <?php echo __('View Details', 'adforest' ); ?>
                                    </a>
                                </div>
                                <!-- Ad Img End -->
                                <div class="short-description">
                                    <!-- Ad Category -->
                                    <div class="category-title">
                                        <?php echo adforest_display_cats( get_the_ID() ); ?>
                                    </div>
                                    <!-- Ad Title -->
                                    <h3><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <!-- Price -->
                                    <div class="price">
                                        <?php echo(adforest_adPrice(get_the_ID())); ?> 
                                    </div>
                                </div>
                                <!-- Addition Info -->
                                <div class="ad-info">
                                    <ul>
                                      <li><i class="fa fa-map-marker"></i><?php echo get_post_meta(get_the_ID(), '_adforest_ad_location', true ); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Ad Box End -->
                        </div>
                    </div>
                    <?php
        				}
        			}
        			wp_reset_postdata();
        			?>
                   <!-- Featured Ads -->
                    </div>
                </div>
            </div>
        </div>
        <?php
	}
	/**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
    public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else  {
			$title = esc_html__( 'Featured Ads', 'adforest' );
		}
		if ( isset( $instance[ 'max_ads' ] ) ) {
			$max_ads = $instance[ 'max_ads' ];
		} else  {
			$max_ads = 5;
		}
	    $this->adforect_widget_open($instance);
	    ?>
	    <p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" >
                <?php echo esc_html__( 'Title:', 'adforest' ); ?>
            </label> 
		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	    </p>
	    <p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'max_ads' ) ); ?>" >
                <?php echo esc_html__( 'Max # of Ads:', 'adforest' ); ?>
            </label> 
		    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_ads' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_ads' ) ); ?>" type="text" value="<?php echo esc_attr( $max_ads ); ?>">
	    </p>
	    <?php 
    }
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['max_ads'] = ( ! empty( $new_instance['max_ads'] ) ) ? strip_tags( $new_instance['max_ads'] ) : '';
		$instance['open_widget'] = ( ! empty( $new_instance['open_widget'] ) ) ? strip_tags( $new_instance['open_widget'] ) : '';
		return $instance;
	}
}

// Recent Ads Widget
class adforest_search_recent_ad extends WP_Widget {
	use adforest_reuse_functions;
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array( 
			'classname' => 'adforest_search_recent_ad',
			'description' => __('Only for search and single ad sidebar.', 'adforest'),
		);
		// Instantiate the parent object
		parent::__construct( false, __('Ads Recent', 'adforest' ), $widget_ops );
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	 /*
    <?php 
        if(function_exists("adforestCustomFieldsHTML")) {
            echo adforestCustomFieldsHTML(get_the_ID(), 12, 1);
        }
    ?>
    */
	public function widget( $args, $instance ) {
		$max_ads	=	$instance['max_ads'];
		global $adforest_theme;
		if( isset( $adforest_theme['design_type'] ) && $adforest_theme['design_type'] == 'modern' ) {
			if( !is_singular( 'ad_post' ) && is_page_template( 'page-search.php' ) )
				return;	
		}
	    ?>
        <div class="panel panel-default">
            <!-- Heading -->
            <div class="panel-heading" >
                <h4 class="panel-title">
                    <a>
                        <?php echo esc_html( $instance['title'] ); ?>
                    </a>
                </h4>
            </div>
            <!-- Content -->
            <div class="panel-collapse">
                <div class="panel-body recent-ads">
        	        <?php
                    $f_args =  array( 
                        'post_type' => 'ad_post',
                        'posts_per_page' => $max_ads,
            			'meta_query' => array(
        				    array(
            					'key'     => '_adforest_ad_status_',
            					'value'   => 'active',
            					'compare' => '=',
        				    ),
        			    ),
                        'post_status' => 'publish',
                        'orderby'        => 'date',
                        'order' => 'DESC',
                    );
                    $f_ads = new WP_Query( $f_args );
                    if ( $f_ads->have_posts() ) {
                        $number	= 0;
                        while ( $f_ads->have_posts() ) {
                            $f_ads->the_post();
                            $pid	=	get_the_ID();
                            $author_id = get_post_field( 'post_author', $pid );;
                            $author = get_user_by( 'ID', $author_id );
                            
                            $img	=	$adforest_theme['default_related_image']['url']; 
                            $media	=	 adforest_get_ad_images($pid);
                            $total_imgs	=	count( $media );
                            if( count( $media ) > 0 ) {
                                foreach( $media as $m ) {
            						$mid	=	'';
            						if ( isset( $m->ID ) )
            							$mid	= 	$m->ID;
            						else
            							$mid	=	$m;	
            						
                                    $image  = wp_get_attachment_image_src( $mid, 'adforest-ad-related' );
                                    $img	=	$image[0];
                                    break;
                                }
                            }
                        ?>
                    <div class="recent-ads-list">
                        <div class="recent-ads-container">
                            <div class="recent-ads-list-image">
                                <a href="<?php the_permalink(); ?>" class="recent-ads-list-image-inner">
                                    <img alt="<?php echo get_the_title(); ?>" src="<?php echo esc_url( $img ); ?>">
                                </a><!-- /.recent-ads-list-image-inner -->
                            </div>
                            <!-- /.recent-ads-list-image -->
                            <div class="recent-ads-list-content">
                                <h3 class="recent-ads-list-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <ul class="recent-ads-list-location">
                                    <li><a href="javascript:void(0);"><?php echo get_post_meta(get_the_ID(), '_adforest_ad_location', true ); ?></a></li>
                                </ul>
                                <ul class="recent-ads-list-location row" style="font-size: 12px">
                                </ul>
                                <div class="recent-ads-list-price">
                                    <?php echo(adforest_adPrice(get_the_ID())); ?> 
                                </div>
                                <!-- /.recent-ads-list-price -->
                            </div>
                            <!-- /.recent-ads-list-content -->
                        </div>
                        <!-- /.recent-ads-container -->
                    </div>
        	    <?php
                    }
                }
                wp_reset_postdata();
                ?>
                </div>
            </div>
        </div>               
        <?php
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else  {
			$title = esc_html__( 'Recent Ads', 'adforest' );
		}
		if ( isset( $instance[ 'max_ads' ] ) ) {
			$max_ads = $instance[ 'max_ads' ];
		} else  {
			$max_ads = 5;
		}
		$this->adforect_widget_open($instance);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" >
                <?php echo esc_html__( 'Title:', 'adforest' ); ?>
            </label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'max_ads' ) ); ?>" >
                <?php echo esc_html__( 'Max # of Ads:', 'adforest' ); ?>
            </label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_ads' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_ads' ) ); ?>" type="text" value="<?php echo esc_attr( $max_ads ); ?>">
		</p>
		<?php 
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['max_ads'] = ( ! empty( $new_instance['max_ads'] ) ) ? strip_tags( $new_instance['max_ads'] ) : '';
		$instance['open_widget'] = ( ! empty( $new_instance['open_widget'] ) ) ? strip_tags( $new_instance['open_widget'] ) : '';
		return $instance;
	}
} // Recent Ads