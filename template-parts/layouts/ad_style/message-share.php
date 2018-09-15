<?php
$ad_id = get_the_ID();
$uid = get_current_user_id();
global $adforest_theme;
$media  = adforest_get_ad_images($ad_id);

$share_message = "";
$share_message_date_str = "";
$share_message_date_str = "";

$params =  array(
	'post_type' => 'ad_share_message'
);					
$share_message_posts	=	get_posts( $params );

wp_register_script( 'adforest-search-modern', trailingslashit( get_template_directory_child_uri () ) . 'js/share-message.js', false, false, true );

if ( is_array( $share_message_posts ) ) {
    $share_message = $share_message_posts[0]->post_content;
    $share_message_date = $share_message_posts[0]->post_name;
    
    $share_message_date_month = 1;
    
    $share_message_date_month = substr($share_message_date, 5, 2);
    $month_str_arry = array("January", "Feburary", "March", "April", "May", "June", "July", "Auguest", "September", "October", "November", "December");
    $share_message_date_str = __($month_str_arry[$share_message_date_month - 1], 'adforest' ).' '.substr($share_message_date, 2, 2).', '.substr($share_message_date, 0, 4);
}

if( is_super_admin( $uid ) )
{
?>
<div role="share" class="alert alert-info alert-outline" style="padding-top: 5px; padding-bottom: 5px;">
    <div class="row">
        <div class="col-md-8">
            <input class="form-control" type="text" name="ad_share_message" id="ad_share_message" placeholder="<?php echo __('Add the share message.', 'adforest' );?>" value="<?php echo __($share_message, 'adforest' )?>">
        </div>
        <div class="col-md-1" style="padding-top: 5px">
            <a href="javascript:void(0);" class="btn btn-sucess btn-no-effect" role="button" id="ad_share_btn" style="border-color: #999; color: #000;"><?php echo __('Edit', 'adforest' );?></a>
        </div>
        <div class="col-md-3" style="padding-top: 10px;">
            <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                <a class="a2a_button_facebook"></a>
                <a class="a2a_button_twitter"></a>
                <a class="a2a_button_google_plus"></a>
                <a class="a2a_button_pinterest"></a>
                <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
            </div>
        </div>
    </div>
    <input class="form-control" type="hidden" name="ad_pid" id="ad_pid" value="<?php echo $ad_id;?>">
</div>
<?php
} else {
    if ( $uid ) {
        if ( $share_message != "" ) {
?>
            <div calss="row" id="share_message_section">
                <div role="alert" class="alert alert-info alert-dismissible <?php echo adforest_alert_type(); ?>">
                    <button aria-label="Close" id="ad_share_cancel_btn" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&#10005;</span></button>
                    <?php echo $share_message; ?>
                </div>
                <div class="descs-box">
                    <div class="modern-version-block-info">
                        <div class="pull-left post-author">
                            <?php echo __('By', 'adforest'); ?>
                            <a><?php echo __('Administrator', 'adforest'); ?></a>
                            <?php echo __('Sent', 'adforest'); ?>
                            <a><?php echo $share_message_date_str ?></a>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
?>