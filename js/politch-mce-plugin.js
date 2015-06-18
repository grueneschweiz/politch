/**
 * specify mce button
 */
(function() {
	tinymce.PluginManager.add( 'politch_shortcode_button', function( editor, url ) {
		editor.addButton( 'politch_shortcode_button', {
			text: editor.getLang( 'politch_shortcode_button.button_text' ),
			title: editor.getLang( 'politch_shortcode_button.button_title' ),
			icon: 'wp_groups',
			onclick: function() {
				editor.insertContent( editor.getLang( 'politch_shortcode_button.msg' ) );
			}
		});
	});
})();