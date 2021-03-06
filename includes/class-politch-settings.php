<?php

/**
 * lock out script kiddies: die an direct call 
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( ! class_exists( 'Politch_Settings' ) ) {

	class Politch_Settings {
		
		public $plugin_table_prefix = 'politch_';
		
		public $network_options = array();
		public $single_blog_options = array();
		
		public $network_tables = array();
		public $single_blog_tables = array();
		
		public $roles = array();
		
		protected $scripts = array();
		protected $styles = array();
		
		public function __construct() {
			
			$this->network_options = array(); // put your default network options in here. 
			                                  // Ex.: $this->network_options = array( array( 'option_name' => 'default value') )
			$this->single_blog_options = array(
				'politch_field_visibility' => array(
					'politch_year_of_birth'    => 1,
					'politch_city'             => 1,
					'politch_mandates'         => 1,
					'politch_memberships'      => 1,
					'politch_slogan'           => 1,
					'politch_ticket_name'      => 1,
					'politch_ticket_number'    => 1,
					'politch_candidate_number' => 1,
					'politch_district'         => 1,
					'politch_smartvote'        => 1,
					'politch_smartspider'      => 1,
					'politch_additional_information_title' => 1,
					'politch_additional_information_body'  => 1,
				),
			); // put your default blog options in here.
			
			$this->scripts[] = array(
				'handle'     => 'politch-admin-js', // string
				'src'        => '/js/politch-admin-js.js', // string relative to plugin folder
				'deps'       => array( 'jquery', 'thickbox' ), // array
				'in_footer'  => true, // bool
				'scope'      => 'admin', // admin | frontend | shared
			);
			
			$this->scripts[] = array(
				'handle'     => 'chosen', // string
				'src'        => '/vendor/chosen_v1.4.2/chosen.jquery.min.js', // string relative to plugin folder
				'deps'       => array( 'jquery' ), // array
				'in_footer'  => true, // bool
				'scope'      => 'admin', // admin | frontend | shared
			);
			
			$this->scripts[] = array(
				'handle'     => 'politch-frontend-js', // string
				'src'        => '/js/politch-frontend-js.js', // string relative to plugin folder
				'deps'       => array( 'jquery', 'jquery-ui-dialog' ), // array
				'in_footer'  => true, // bool
				'scope'      => 'frontend', // admin | frontend | shared
			);
			
			$this->styles[] = array(
				'handle'    => 'politch-admin-css', // string
				'src'       => '/css/politch-admin-css.css', // string relative to plugin folder
				'deps'      => array( 'dashicons' ), // array
				'media'     => 'all', // css media tag
				'scope'     => 'admin', // admin | frontend | shared
			);
			
			$this->styles[] = array(
				'handle'    => 'chosen', // string
				'src'       => '/vendor/chosen_v1.4.2/chosen.min.css', // string relative to plugin folder
				'deps'      => array(  ), // array
				'media'     => 'all', // css media tag
				'scope'     => 'admin', // admin | frontend | shared
			);
			
			$this->styles[] = array(
				'handle'    => 'politch-frontend-css', // string
				'src'       => '/css/politch-frontend-css.css', // string relative to plugin folder
				'deps'      => array(), // array
				'media'     => 'all', // css media tag
				'scope'     => 'frontend', // admin | frontend | shared
			);
			
			$network_tables = array(); // put your table names in this array (whitout prefix and stuff)
			$single_blog_tables = array(); // put your table names in this array (whitout prefix and stuff)
			
			$this->set_network_tables( $network_tables );
			$this->set_single_blog_tables( $single_blog_tables );
		}
		
		/**
		 * loads the $network_tables array. the key will be the table name, the content will be the fully prefixed table name
		 */
		private function set_network_tables( $table_names ) {
			global $wpdb;
			
			$tables = array();
			
			foreach( $table_names as $table_name ) {
				$tables[ $table_name ] = $wpdb->base_prefix . $this->plugin_table_prefix . $table_name;
			}
			
			$this->network_tables = $tables;
		}
		
		/**
		 * loads the $single_blog_tables array. the key will be the table name, the content will be the table name with the plugin prefix.
		 * the blog prefix will NOT be set!
		 */
		private function set_single_blog_tables( $table_names ) {
			global $wpdb;
			
			$tables = array();
			
			foreach( $table_names as $table_name ) {
				$tables[ $table_name ] = $this->plugin_table_prefix . $table_name;
			}
			
			$this->single_blog_tables = $tables;
		}
	}
}