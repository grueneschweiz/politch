var $jwptm = jQuery.noConflict();
$jwptm(function(){

	var selector = $jwptm('#shortcode_output_box'),
		catslug = $jwptm( "#tm_cat" ).val(),
		orderby = $jwptm( "#tm_orderby" ).val(),
		tm_limit = $jwptm( "#tm_limit" ).val(),
		tm_show_id = $jwptm( "#tm_show_id" ).val(),
		tm_remove_id = $jwptm( "#tm_remove_id" ).val(),
		tm_layout = $jwptm( "#tm_layout" ).val(),
		tm_image_layout = $jwptm( "#tm_image_layout" ).val();
		tm_image_size = $jwptm( "#tm_image_size" ).val();

		
	$jwptm( '#tm_cat' ).on( "keyup keydown change", function() {

		catslug = $jwptm(this).val();

	});
	$jwptm( '#tm_orderby' ).on( "keyup keydown change", function() {

		orderby = $jwptm(this).val();
	});
	$jwptm( '#tm_limit' ).on( "keyup keydown change", function() {

		tm_limit = $jwptm(this).val();
	});	
	$jwptm( '#tm_show_id' ).on( "keyup keydown change", function() {

		tm_show_id = $jwptm(this).val();
	});		
	$jwptm( '#tm_remove_id' ).on( "keyup keydown change", function() {

		tm_remove_id = $jwptm(this).val();
	});		
	$jwptm( '#tm_layout' ).on( "keyup keydown change", function() {

		tm_layout = $jwptm(this).val();
	});	
	$jwptm( '#tm_image_layout' ).on( "keyup keydown change", function() {

		tm_image_layout = $jwptm(this).val();
	});		

	$jwptm( '#tm_image_size' ).on( "keyup keydown change", function() {

		tm_image_size = $jwptm(this).val();
 	});	
 	
	$jwptm('#tm_short_code :input').on( "keyup keydown change", function() {

		var shortcodegenerated = 
		"[team_manager category='"+catslug+"' orderby='"+orderby+"' limit='"+tm_limit+"' post__in='"+tm_show_id+"' exclude='"+tm_remove_id+"' layout='"+tm_layout+"' image_layout='"+tm_image_layout+"' image_size='"+tm_image_size+"']";

		selector.empty().append(shortcodegenerated);

	});


});
