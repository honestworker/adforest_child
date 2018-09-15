<?php get_header(); ?>
<?php global $adforest_theme; ?>
<?php wp_enqueue_script( 'adforest-search-modern', trailingslashit( get_template_directory_uri () ) . 'js/search-modern.js', false, false, true); wp_enqueue_script( 'google-map-callback' ); ?>

<?php $ad_sub_cats = adforest_get_cats( 'ad_cats',  get_queried_object_id() ); ?>

<div class="main-content-area clearfix">
    <!-- =-=-=-=-=-=-= Latest Ads =-=-=-=-=-=-= -->
    <section class="section-padding pattern_dots">
        <!-- Main Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">               
                <?php
                    $is_active	=	array(
                        'key'     => '_adforest_ad_status_',
                        'value'   => 'active',
                        'compare' => '=',
                    );
                    
                    $is_price = '';
                    $min_price = 1;
                    $max_price = 10000000;
                    if( isset( $_GET['min_price'] ) && $_GET['min_price'] != "" )
                    {
                        $is_price	=	array(
                            'key'     => '_adforest_ad_price',
                            'value'   => array( $_GET['min_price'], $_GET['max_price'] ),
                            'type'    => 'numeric',
                            'compare' => 'BETWEEN',
                        );
                        $min_price = $_GET['min_price'];
                        $max_price = $_GET['max_price'];
                    }

                    $is_location =	'';
                    if( isset( $_GET['location'] ) && $_GET['location'] != "" )
                    {
                        $is_location  =	array(
                            'key'     => '_adforest_ad_location',
                            'value'   => trim( $_GET['location'] ),
                            'compare' => 'LIKE',
                        );
                    }
                    
                    $category	=	array(
                        array(
                            'taxonomy' => 'ad_cats',
                            'field'    => 'term_id',
                            'terms'    => get_queried_object_id(),
                        ),
                    );
                    
                    $feature_args = array( 
                        'post_type' => 'ad_post',
                        'post_status' => 'publish',
                        'posts_per_page' => $adforest_theme['max_ads_feature'],
                        'tax_query' => array(
                            $category,
                        ),
                        'meta_query' => array(
                            array(
                                'key'     => '_adforest_is_feature',
                                'value'   => 1,
                                'compare' => '=',
                            ),
                            array(
                                'key'     => '_adforest_ad_status_',
                                'value'   => 'active',
                                'compare' => '=',
                            ),
                        ),
                        'orderby'        => 'rand',
                    );
                    
                    $page_args = array( 
                        'post_type' => 'ad_post',
                        'post_status' => 'publish',
                        'tax_query' => array(
                            $category,
                        ),
                        'meta_query' => array(
                            $is_active,
                            $is_location,
                            $is_price
                        ),
                        'paged' => $paged,
                    );

                    if( isset( $adforest_theme['feature_on_search'] ) && $adforest_theme['feature_on_search']  )
                    {
                        $ads = new ads();
                        echo ( $ads->adforest_get_ads_grid_slider( $feature_args, $adforest_theme['feature_ads_title'], 4, 'no-padding' ) );
                    }
                    ?>
                    <!-- Search -->
                    <form id="search-modern-form" action="<?php echo get_term_link( get_queried_object_id() )?>">
                        <div class="search-section">
                            <div id="form-panel">
                                <ul class="list-unstyled search-options">
                                    <li>
                                        <select class="category form-control" id="search-category">
                                            <option label="<?php echo __('เลือกประเภท', 'adforest')?>" value=""><?php echo __('ประเภท', 'adforest');?></option>
                                            <?php foreach( $ad_sub_cats as $ad_sub_cat ) {?>
                                            <option value="<? echo get_term_link( $ad_sub_cat->term_id )?>"><?php echo __($ad_sub_cat->name, 'adforest');?></option>
                                            <?php }?>
                                        </select>
                                    </li>
                                    <li>
                                        <input type="type" autocomplete="off" name="ad_location" id="ad_location" placeholder="<?php echo __('ที่ตั้ง', 'adforest')?>">
                                    </li>
                                    <li>
                                        <div class="siderbar">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default" id="search-price-button">
                                                    <!-- Heading -->
                                                    <div class="panel-heading" role="tablist" id="headingfour">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                                                                <?php echo __( 'ราคา', 'adforest' ); ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <!-- Content -->
                                                    <div id="collapsefour" class="panel-collapse collapse <?php echo esc_attr( $expand ); ?>" role="tabpanel" aria-labelledby="headingfour">
                                                        <div class="panel-body">
                                                            <span class="price-slider-value"><?php echo __( 'Price', 'adforest' ); ?>
                                                            (<?php echo esc_html( $adforest_theme['sb_currency'] ); ?>) 
                                                            <span id="price-min"></span>
                                                            - 
                                                            <span id="price-max"></span>
                                                            </span>
                                                            <div id="price-slider"></div>
                                                            <div class="input-group margin-top-10">
                                                                <input type="text" class="form-control" name="min_price" id="min_selected" value="<?php echo $min_price;?>" />
                                                                <span class="input-group-addon">-</span>
                                                                <input type="text" class="form-control" name="max_price" id="max_selected" value="<?php echo $max_price;?>" />
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="min_price_range" value="1" />
                                                        <input type="hidden" id="max_price_range" value="10000000" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <button type="submit" id="ad-search-btn" class="btn btn-danger btn-lg btn-block"><?php echo __('Search', 'adforest')?></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <script>
                        function adforest_location() {
                            var options = {
                                types: [],
                                componentRestrictions: {country: ["TH"]}
                            };
                            var input = document.getElementById('ad_location');
                            var action_on_complete	=	'';
                            var autocomplete = new google.maps.places.Autocomplete(input, options);
                            if( action_on_complete )
                            {
                                new google.maps.event.addListener(autocomplete, 'place_changed', function() {
                                    // document.getElementById('sb_loading').style.display	= 'block';
                                    var place = autocomplete.getPlace();
                                    document.getElementById('ad_map_lat').value = place.geometry.location.lat();
                                    document.getElementById('ad_map_long').value = place.geometry.location.lng();
                                    var markers = [
                                    {
                                        'title': '',
                                        'lat': place.geometry.location.lat(),
                                        'lng': place.geometry.location.lng(),
                                    }];
                                    my_g_map(markers);
                                    //document.getElementById('sb_loading').style.display	= 'none';
                                });
                            }
                        }
                    </script>
                    <?php
                    $ads = new ads();
                    echo ( $ads->adforest_modern_search_layout1( $page_args ) );
                    ?>
                </div>
            <!-- Row End -->
        </div>
        <!-- Main Container End -->
    </section>
<!-- =-=-=-=-=-=-= Ads Archives End =-=-=-=-=-=-= -->
</div>

<?php get_footer(); ?>