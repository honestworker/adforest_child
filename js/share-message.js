/*
Template: AdForest | Modern Search Classifieds ADs
Author: Honestworker
Version: 1.0
Designed and Development by: Honestworker
*/
(function($) {
    "use strict";
	var adforest_ajax_url = $('#adforest_ajax_url').val();
    
    $('#ad_share_message').attr('disabled', true);
    $(document).on('click', '#ad_share_btn', function()
    {
        if ($('#ad_share_message').attr('disabled')) {
            $('#ad_share_message').attr('disabled', false);
            $('#ad_share_btn').text('Save');
        } else {
            // $.post(adforest_ajax_url, { action : 'update_ad_share_message', ad_pid: $('#ad_pid').val(), message: $('#ad_share_message').val() }).done( function(response)
            // {
            // });
            $.post(adforest_ajax_url, { action : 'update_ad_share_message', message: $('#ad_share_message').val() }).done( function(response)
            {
            });
            $('#ad_share_message').attr('disabled', true);
            $('#ad_share_btn').text('Edit');
        }
    });
    
    // $(document).on('click', '#ad_share_cancel_btn', function()
    // {
    //     $.post(adforest_ajax_url, { action : 'cancel_ad_share_message', ad_pid: $('#ad_pid').val() }).done( function(response)
    //     {
            
    //     });
    //     $('#share_message_section').addClass('hidden');
    // });
})(jQuery);