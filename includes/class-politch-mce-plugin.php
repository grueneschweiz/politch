<?php
/**
 * lock out script kiddies: die an direct call 
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( ! class_exists( 'Politch_Mce_Plugin' ) ) {
	
	/**
	 * MCE plugin to add a shortcode-button to the editor
	 * a popup window shows the shortcode-settings
	 * after selecting the settings, the shortcode will be inserted
	 * 
	 * add button
	 * load js
	 * configure a popup
	 * insert shortcode
	 */
	class Politch_Mce_Plugin {
		/**
		 * add mce plugin js
		 */
		public function add_mce_plugin( $plugin_array ) {
			$plugin_array['politch_shortcode_button'] = plugins_url( '../js/politch-mce-plugin.js', __FILE__ );
			return $plugin_array;
		}
		
		/**
		 * add button to mce
		 */
		public function register_mce_button( $buttons ) {
			array_push( $buttons, 'politch_shortcode_button');
			return $buttons;
		}
		
		/**
		 * load translations for the mce plugin
		 */
		public function load_mce_plugin_translations() {
			$locales['politch_shortcode_button'] = $this->get_mce_plugin_translations();
			return $locales;
		}
		
		/**
		 * do the translation stuff
		 * 
		 * @return    json    the translations
		 */
		public function get_mce_plugin_translations() {
			// load the tinymce class, if not already loaded
			if ( ! class_exists( '_WP_Editors' ) ) {
				require_once( ABSPATH . WPINC . '/class-wp-editor.php' );
			}
			
			$strings = array(
				'button_text'      => __( 'Add People – asdf', 'politch' ),
				'button_title'     => __( 'Add People – asdf', 'politch' ),
				'msg'              => __('Hello World!!!!', 'politch')
			);
			
			$locale = _WP_Editors::$mce_locale;
			$translated = 'tinyMCE.addI18n("' . $locale . '.politch_shortcode_button", ' . json_encode( $strings ) . ");\n";
			
			return $translated;
		}
	}
}