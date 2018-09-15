/*
Template: AdForest | Modern Search Classifieds ADs
Author: Honestworker
Version: 1.0
Designed and Development by: Honestworker
*/
(function($) {
    "use strict";
	var adforest_ajax_url = $('#adforest_ajax_url').val();
	var ads_height = $('#search-result').height;
	var page_no = 1;
	var page_num = 1;
	
    function morden_search_ads_description()
    {
		var main_category = $('#ad_cat_id').val();
        $.post(adforest_ajax_url, {action : 'sb_search_modern_description', cat_id: main_category }).done( function(response)
        {
            $('#header-description').html(response);
        });
    }
    
	/*==========  Price Range Slider  ==========*/
    var min_price =	$('#min_price').val();
	var max_price =	$('#max_price').val();
    $('document').ready(function() {
    	if( $('#min_price').length > 0 )
    	{
            $('#price-slider').noUiSlider({
                connect: true,
                behaviour: 'tap',
                start: [$('#min_selected').val(), $('#max_selected').val()],
                step: 0,
                range: {
                    'min': parseInt(min_price),
                    'max': parseInt(max_price)
                }
            });
    		$('#price-slider').Link('lower').to($('#price-min'), null, wNumb({
    			decimals: 0
    		}));
    		$('#price-slider').Link('lower').to($('#min_selected'), null, wNumb({
    			decimals: 0
    		}));
    		$('#price-slider').Link('upper').to($('#price-max'), null, wNumb({
    			decimals: 0
    		}));
    		$('#price-slider').Link('upper').to($('#max_selected'), null, wNumb({
    			decimals: 0
    		}));
    	}
        morden_search_ads_description();
    });
    
    function morden_search_ads()
    {
		var search_category = $('#search-category').val();
		
		if ( !search_category ) {
		    search_category = $('#ad_cat_id').val();
		}
		
		if ( ads_height )
		{ 
		    $('#search-result').height(ads_height);
		}
		
	    $('#search-result').html("");
	    
		$('#sb_loading').show();
	    $.post(adforest_ajax_url, {action : 'sb_search_modern', cat_id: search_category, location: $('#sb_user_address').val(), max_price: $('#max_selected').val(), min_price: $('#min_selected').val(), paged: page_no }).done( function(response)
	    {
            if( $.trim(response) == 'submit' )
            {
            }
            else
            {
                $('#search-result').html(response['ads']);
                $('#search-pagination').html(response['pagination']);
                page_num = response['page_num'];
		        $('#sb_loading').hide();
		        $('#search-result').parent().css({ 'position': 'initial' });
            }
	    });
    }
    
	$(document).on('click', '#ad-search-btn', function()
	{
	    morden_search_ads();
	});
	
	$(document).on('click', '#next-page', function()
	{
	    if (page_no < page_num)
	    {
	        page_no = page_no + 1;
	        morden_search_ads();
	    }
	});
	
	$(document).on('click', '#privious-page', function()
	{
	    if (page_no > 1)
	    {
	        page_no = page_no - 1;
	        morden_search_ads();
	    }
	});
	
	morden_search_ads();
	
	$(document).on('click', '.search-page-num', function()
	{
	    page_no = this.text;
	    morden_search_ads();
	});
	
})(jQuery);

