<?php

/**
 * Plugin Name: Politch
 * Plugin URI: https://github.com/grueneschweiz/politch
 * Version: 1.4.6
 * Description: Plugin to display politicians profiles. Especially designed for swiss needs.
 * Author: Cyrill Bolliger
 * Text Domain: politch
 * Domain Path: languages
 * GitHub Plugin URI: grueneschweiz/politch
 * License: GPL 2.
 */
 
/**
 * Copyright 2015  Cyrill Bolliger  (email : bolliger@gmx.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 

/**
 * lock out script kiddies: die an direct call
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * abspath to plugins directory
 */
define( 'POLITCH_PLUGIN_PATH', dirname( __FILE__ ) );

/**
 * version number (dont forget to change it also in the header)
 */
define( 'POLITCH_VERSION', '1.4.6' );

/**
 * plugin prefix
 */
define( 'POLITCH_PLUGIN_PREFIX', 'politch_' );

/**
 * load settings class
 */
require_once( POLITCH_PLUGIN_PATH . '/includes/class-politch-settings.php' );


if ( ! class_exists( 'Politch_Main' ) ) {
	
	class Politch_Main extends Politch_Settings {
		
		public $tmp = ''; // output buffer
		
		/*
		 * Construct the plugin object
		 */
		public function __construct() {
			
			parent::__construct();
			
			register_activation_hook( __FILE__, array( &$this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
			
			add_action( 'init', array( &$this, 'init' ), 9 ); // plugin has to load before the meta-box plugin
			add_action( 'init', array( &$this, 'fe_init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
			add_action( 'plugins_loaded', array( &$this, 'i18n' ) );
			add_action( 'plugins_loaded', array( &$this, 'upgrade' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_resources' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_resources' ) );
			add_action( 'tgmpa_register', array( &$this, 'register_required_plugins' ) );
			add_action( 'media_buttons', array( &$this, 'add_media_button' ), 15 );
			add_action( 'admin_footer', array( $this, 'add_short_code_generator_html' ), 15 );
		}
		
		/**
		 * Activate the plugin
		 */
		public function activate() {
			$this->set_version_number();
			$this->add_roles_on_plugin_activation();
			$this->add_capabilities_on_plugin_activation();
			$this->create_tables_on_plugin_activation();
			$this->create_default_options_on_first_plugin_activation();
		}
		
		/**
		 * Deactivate the plugin
		 */
		public function deactivate() {
			$this->remove_capabilities_on_plugin_deactivation();
			$this->remove_roles_on_plugin_deactivation();
		}
		
		/**
		 * Hook into WP's init action hook.
		 */
		public function init() {
			$this->load_custom_post_type();
		}
		
		/**
		 * Hook into WP's init action hook for frontend pages
		 */
		public function fe_init() {
			if ( ! is_admin() ) {
				add_shortcode( 'politch', array( &$this, 'short_code_handler' ) );
			}
		}
		
		/**
		 * Hook into WP's admin_init action hook
		 */
		public function admin_init() {
			$this->init_options();
			$this->load_tgm_plugin_activation_class();
		}
		
		/**
		 * write version number to db
		 *
		 * @since     1.3.1
		 */
		public function set_version_number() {
			update_option( POLITCH_PLUGIN_PREFIX.'version_number', POLITCH_VERSION );
		}
		
		/**
		 * upgrade db
		 *
		 * if the plugin was just updated proceed with an upgrade routine
		 * - set default option values if the last version was smaller than 1.3.0
		 * - update the version option. on every update.
		 *
		 * @since     1.3.1
		 */
		public function upgrade() {
			$current_version = get_option( POLITCH_PLUGIN_PREFIX.'version_number' );
			
			// if everything is up to date stop here
			if ( POLITCH_VERSION == $current_version ) {
				return; // BREAKPOINT
			}
			
			// run the upgrade routine for versions smaller 1.3.0
			if ( -1 == version_compare( $current_version, '1.3.0' ) ) {
				$this->create_default_options_on_first_plugin_activation();
			}
			
			// set the current version number
			$this->set_version_number();
		}
		
		/**
		 * Initialize some custom settings
		 */
		public function init_options() {
			register_setting( 'politch_options', 'politch_field_visibility' );
			
			add_settings_section(
				'politch_visibility_options',
				__( 'Visibility options', 'politch' ),
				array( &$this, 'visibility_options_callback' ),
				'politch_options'
			);
			
			$fields = array(
				'year_of_birth'    => __( 'Year of birth', 'politch' ),
				'city'             => _x( 'City', 'Place of residence' ,'politch' ),
				'roles'            => __( 'Role','politch' ),
				'brief_cv'         => __( 'Brief CV', 'politch' ),
				'mandates'         => __( 'Mandates', 'politch' ),
				'memberships'      => __( 'Memberships', 'politch' ),
				'slogan'           => __( 'Slogan', 'politch' ),
				'ticket_name'      => __( 'Ticket name', 'politch' ),
				'ticket_number'    => __( 'Ticket number', 'politch' ),
				'candidate_number' => __( 'Candidate number', 'politch' ),
				'district'         => __( 'District', 'politch' ),
				'smartvote'        => __( 'Smartvote', 'politch' ),
				'smartspider'      => __( 'Smartspider', 'politch' ),
				'email'            => __( 'Mail', 'politch' ),
				'phone'            => __( 'Phone', 'politch' ),
				'mobile'           => __( 'Mobile', 'politch' ),
				'website'          => __( 'Website', 'politch' ),
				'facebook'         => __( 'Facebook', 'politch' ),
				'twitter'          => __( 'Twitter', 'politch' ),
				'linkedin'         => __( 'LinkedIn', 'politch' ),
				'google_plus'      => __( 'Google+', 'politch' ),
				'youtube'          => __( 'Youtube', 'politch' ),
				'vimeo'            => __( 'Vimeo', 'politch' ),
				'additional_information_title' => __( 'Additional Information Title', 'politch' ),
				'additional_information_body'  => __( 'Additional Information Body', 'politch' ),
			);
			
			foreach( $fields as $key => $caption ) {
				add_settings_field(
					'politch_' . $key,
					$caption,
					array( &$this, 'render_options_checkbox' ),
					'politch_options',
					'politch_visibility_options',
					array( 'id' => 'politch_' . $key )
				);
			}
		}
		
		/**
		 * The description of the visibility section of the options page
		 */
		public function visibility_options_callback () {
			echo __( 'Tick the checkbox, if the field should only be visible for election profiles.', 'politch' );
		}
		
		/**
		 * Render the html for options checkboxes
		 */
		public function render_options_checkbox( $args ) {
			$options = get_option( 'politch_field_visibility' );
			
			if ( isset( $options[ $args['id'] ] ) ) {
				$checked = checked( $options[ $args['id'] ], 1, false );
			} else {
				$checked = '';
			}
			echo "<input type='checkbox' name='politch_field_visibility[{$args['id']}]' $checked value='1'>";
			
		}
		
		
		/**
		 * Add a menu
		 */
		public function add_menu() {
			add_options_page( __( 'People Options', 'politch' ), __( 'People Options', 'politch' ), 'manage_options', 'politch_options', array( &$this, 'plugin_options_page' ) );
		}

		/**
		 * Menu Callback
		 */
		public function plugin_options_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'politch' ) );
			}
			
			// Render the settings template
			include POLITCH_PLUGIN_PATH . '/admin/options.php';
		}
		
		/**
		 * I18n.
		 *
		 * Put the translation in the languages folder in the plugins directory
		 * Name the translation files like "nameofplugin-lanugage_COUUNTRY.po". Ex: "politch-fr_FR.po"
		 */
		public function i18n() {
			$path = dirname( plugin_basename(__FILE__) ) . '/languages';
			load_plugin_textdomain( 'politch', false, $path );
		}
		
		/**
		 * Add roles on plugin activation
		 */
		public function add_roles_on_plugin_activation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
				if ( ! empty( $blogs_list ) ) {
					foreach ($blogs_list as $blog) {
						switch_to_blog($blog['blog_id']);
						$this->add_roles_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_roles_for_sigle_blog();
			}
		}
		
		/**
		 * actually adds the roles
		 */
		private function add_roles_for_sigle_blog() {
			foreach( $this->roles as $role ) {
				add_role( $role[0], $role[1], $role[2] );
			}
		}
		
		/**
		 * Remove roles on plugin deactivation
		 */
		public function remove_roles_on_plugin_deactivation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
				if ( ! empty( $blogs_list ) ) {
					foreach ($blogs_list as $blog) {
						switch_to_blog($blog['blog_id']);
						$this->remove_roles_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->remove_roles_for_sigle_blog();
			}
		}
		
		/**
		 * actually removes the roles
		 */
		private function remove_roles_for_sigle_blog() {
			foreach( $this->roles as $role ) {
				remove_role( $role[0] );
			}
		}
		
		/**
		 * Add capabilities on plugin activation
		 */
		public function add_capabilities_on_plugin_activation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
				if ( ! empty( $blogs_list ) ) {
					foreach ($blogs_list as $blog) {
						switch_to_blog($blog['blog_id']);
						$this->add_capabilities_for_single_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_capabilities_for_single_blog();
			}
		}
		
		
		/**
		 * Actually add capabilities
		 */
		private function add_capabilities_for_single_blog() {
			$capabilities = array(
				'politch_edit_person',
			);
			$this->add_plugin_capabilities_for( 'editor', $capabilities[0] );
			$this->add_plugin_capabilities_for( 'administrator' , $capabilities );
		}
		
		
		/**
		 * Remove capabilities on plugin deactivation
		 */
		public function remove_capabilities_on_plugin_deactivation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
				if ( ! empty( $blogs_list ) ) {
					foreach ($blogs_list as $blog) {
						switch_to_blog($blog['blog_id']);
						$this->remove_capabilities_for_single_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->remove_capabilities_for_single_blog();
			}
		}
		
		
		/**
		 * Actually remove capabilities
		 */
		private function remove_capabilities_for_single_blog() {
			$capabilities = array(
				'politch_edit_person',
			);
			$this->remove_plugin_capabilities_for( 'editor', $capabilities[0] );
			$this->remove_plugin_capabilities_for( 'administrator' , $capabilities );
			
		}
		
		/**
		 * Add capabilities
		 *
		 * @var string			$role_name		subject
		 * @var string|array 	$capabilities	caps to add
		 */
		public function add_plugin_capabilities_for( $role_name, $capabilities ) {
			$role = get_role( $role_name );
			foreach ( (array) $capabilities as $capability ) {
				$role->add_cap( $capability );
			}
		}
		
		/**
		 * Remove capabilities
		 *
		 * @var string			$role_name		subject
		 * @var string|array 	$capabilities	caps to remove
		 */
		public function remove_plugin_capabilities_for( $role_name, $capabilities ) {
			$role = get_role( $role_name );
			foreach ( (array) $capabilities as $capability ) {
				$role->remove_cap( $capability );
			}
		}
		
		/**
		 * Add tables on plugin activation if they dont exist yet
		 */
		public function create_tables_on_plugin_activation() {
			// dont forget to check if tables dont exist yet
			// dont forget to use $this->network_tables and $this->single_blog_tables (with $wpdb->prefix) as table names
		}
		
		/**
		 * Create options on plugin activation it they dont exist yet. Nothing will be overwritten.
		 */
		public function create_default_options_on_first_plugin_activation() {
			// single blog options
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
				if ( ! empty( $blogs_list ) ) {
					foreach ($blogs_list as $blog) {
						switch_to_blog($blog['blog_id']);
						$this->add_options_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_options_for_sigle_blog();
			}
			
			// options for all blogs (network options)
			$this->add_site_options();
		}
		
		/**
		 * Actually adds the options. If the option already exists it will simply be skiped.
		 * So nothing will be overwritten. This function will only add single blog options.
		 */
		private function add_options_for_sigle_blog() {
			foreach( $this->single_blog_options as $option_name => $option_data ) {
				add_option( $option_name, $option_data );
			}
		}
		
		/**
		 * Actually adds the options. If the option already exists it will simply be skiped.
		 * So nothing will be overwritten. This function will only add network options.
		 */
		private function add_site_options() {
			foreach( $this->network_options as $option_name => $option_data ) {
				add_site_option( $option_name, $option_data );
			}
		}
		
		/**
		 * handle short code
		 *
		 * @var		array	$atts	provided from WP's add_shortcode() function
		 * @return	string
		 */
		public function short_code_handler( $atts ) {
			$frontend = new Politch_Frontend();
			return $frontend->process_short_code( $atts );
		}
		
		/**
		 * load ressources (js, css)
		 */
		public function load_resources() {
			
			foreach ( $this->styles as $style ) {
				if ( is_admin() && $style['scope'] == ( 'admin' || 'shared' ) ) {
					if ( ! wp_style_is( $style['handle'], 'enqueued' ) ) {
						$this->register_style( $style );
						wp_enqueue_style( $style['handle'] );
					}
				}
				if ( ! is_admin() && $style['scope'] == ( 'frontend' || 'shared' ) ) {
					if ( ! wp_style_is( $style['handle'], 'enqueued' ) ) {
						$this->register_style( $style );
						wp_enqueue_style( $style['handle'] );
					}
				}
			}
			
			foreach ( $this->scripts as $script ) {
				if ( is_admin() && $script['scope'] == ( 'admin' || 'shared' ) ) {
					if ( ! wp_script_is( $script['handle'], 'enqueued' ) ) {
						$this->register_script( $script );
						wp_enqueue_script( $script['handle'] );
					}
				}
				if ( ! is_admin() && $script['scope'] == ( 'frontend' || 'shared' ) ) {
					if ( ! wp_script_is( $script['handle'], 'enqueued' ) ) {
						$this->register_script( $script );
						wp_enqueue_script( $script['handle'] );
					}
				}
			}
		}
		
		/**
		 * register script
		 *
		 * @var array 	$script		for params see __construct in Politch_Settings
		 */
		public function register_script( $script ) {
			wp_register_script(
				$script['handle'],
				plugins_url( $script['src'], __FILE__ ),
				$script['deps'],
				POLITCH_VERSION,
				$script['in_footer']
			);
		}
		
		/**
		 * register style
		 *
		 * @var array 	$style		for params see __construct in Politch_Settings
		 */
		public function register_style( $style ) {
			wp_register_style(
				$style['handle'],
				plugins_url( $style['src'], __FILE__ ),
				$style['deps'],
				POLITCH_VERSION,
				$style['media']
			);
		}
		
		
		/**
		 * Load TGM plugin
		 *
		 * Will only be loaded for single site blogs (MU isn't supportet yet. Check https://github.com/TGMPA/TGM-Plugin-Activation for
		 * more information. Most problably in v3 it will be supported.)
		 *
		 * @package    WP Team Manager Extended
		 * @package    TGM-Plugin-Activation
		 * @uses       /vendor/class-tgm-plugin-activation.php
		 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
		 *
		 * @todo       update the TGM Plugin and remove the 'if not is_multisite()' condition as soon as the TGM Plugin supports MU.
		 *
		 */
		private function load_tgm_plugin_activation_class() {
			/**
			 * exit if multisite. The TGM Plugin doesent support MU blogs yet.
			 *
			 * @todo   update the TGM Plugin and remove the 'if not is_multisite()' condition as soon as the TGM Plugin supports MU.
			 */
			if ( is_multisite() ) {
				return; // BREAKPOINT
			}
			
			// Include the TGM_Plugin_Activation class.
			require_once( POLITCH_PLUGIN_PATH . '/vendor/class-tgm-plugin-activation.php' );
		}
		
		
		/**
		 * Register the required plugins for this theme.
		 *
		 * The variable passed to tgmpa_register_plugins() should be an array of plugin
		 * arrays.
		 *
		 * This function is hooked into tgmpa_init, which is fired within the
		 * TGM_Plugin_Activation class constructor.
		 *
		 * @package    TGM-Plugin-Activation
		 * @uses       /vendor/class-tgm-plugin-activation.php
		 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
		 *
		 * @todo       update the TGM Plugin and remove the 'if not is_multisite()' condition as soon as the TGM Plugin supports MU.
		 */
		public function register_required_plugins() {
			/**
			 * exit if multisite. The TGM Plugin doesent support MU blogs yet.
			 *
			 * @todo   update the TGM Plugin and remove the 'if not is_multisite()' condition as soon as the TGM Plugin supports MU.
			 */
			if ( is_multisite() ) {
				return; // BREAKPOINT
			}
			
			/**
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$plugins = array(
				// REQUIRED PLUGIN from Github to allow automatic Updates of the Theme itself, that is hosted on github
				array(
					'name'               => 'GitHub updater', // The plugin name.
					'slug'               => 'github-updater', // The plugin slug (typically the folder name).
					'source'             => get_stylesheet_directory() . '/vendor/plugins/github-updater.zip', // The plugin source.
					'required'           => true, // If false, the plugin is only 'recommended' instead of required.
					'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
					'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
					'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
					'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				),
				// REQUIRED PLUGINS from the WordPress Plugin Repository.
				array(
					'name'               => 'Meta Box',
					'slug'               => 'meta-box',
					'required'           => true,
					'force_activation'   => true,
					'force_deactivation' => false,
				),
			);
			
			/**
			 * Array of configuration settings. Amend each line as needed.
			 * If you want the default strings to be available under your own theme domain,
			 * leave the strings uncommented.
			 * Some of the strings are added into a sprintf, so see the comments at the
			 * end of each line for what each argument will be.
			 */
			$config = array(
				'default_path' => '',                      // Default absolute path to pre-packaged plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => __( 'Install Required Plugins', 'politch' ),
					'menu_title'                      => __( 'Install Plugins', 'politch' ),
					'installing'                      => __( 'Installing Plugin: %s', 'politch' ), // %s = plugin name.
					'oops'                            => __( 'Something went wrong with the plugin API.', 'politch' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'politch' ), // %1$s = plugin name(s).
					'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'politch' ), // %1$s = plugin name(s).
					'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'politch' ), // %1$s = plugin name(s).
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'politch' ), // %1$s = plugin name(s).
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'politch' ), // %1$s = plugin name(s).
					'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'politch' ), // %1$s = plugin name(s).
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'politch' ), // %1$s = plugin name(s).
					'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'politch' ), // %1$s = plugin name(s).
					'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'politch' ),
					'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'politch' ),
					'return'                          => __( 'Return to Required Plugins Installer', 'politch' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'politch' ),
					'complete'                        => __( 'All plugins installed and activated successfully. %s', 'politch' ), // %s = dashboard link.
					'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				)
			);
			
			tgmpa( $plugins, $config );
		
		}
		
		/**
		 * load the plugin specific post type
		 */
		private function load_custom_post_type() {
			// load politch post type
			require_once( POLITCH_PLUGIN_PATH . '/includes/class-politch-post-type.php' );
			$post_type = new Politch_Post_Type();
			
			// register post type and taxonomy
			$post_type->register_post_type();
			
			// add meta boxes
			add_filter( 'rwmb_meta_boxes', array( $post_type, 'add_meta_boxes' ) );
   
			// register custom overview
			$post_type->register_overview();
               
               // tweak post update messages -> remove any preview
               add_filter( 'post_updated_messages', array( $post_type, 'remove_post_update_message_links' ) );
               
               // remove quick edit link
               add_filter( 'post_row_actions', array( $post_type, 'remove_quickedit_link' ) );
               
               /**
                * set template for single post
                *
                * @since 1.4.0
                */
               add_filter( 'single_template', array( $post_type, 'set_politch_single_template' ) );
		}
		
		
		/**
		 * Add a media button to the post & page edit pages to insert shortcode easly
		 *
		 * @param    string    $context    given from 'media_buttons_context'-filter
		 * @return   string
		 *
		 * @see                            http://de.wpseek.com/function/media_buttons/
		 */
		public function add_media_button( $context ) {
			global $typenow;
			// check user permissions
			if ( ! current_user_can( 'politch_edit_person' ) ) {
				return; // BREAKPOINT
			}
			
			// verify the post type
			if( ! in_array( $typenow, array( 'post', 'page', 'tribe_events' ) ) ) {
				return; // BREAKPOINT
			}
			
			// make sure the thickbox script is loaded
			add_thickbox();
			
			// add media button
			echo '<a href="#TB_inline?&inlineId=politch-short-code-generator" class="thickbox button" ' .
				'title="' . esc_attr__( "Insert people", "politch" ) . '">' .
				'<span class="wp-media-buttons-icon dashicons dashicons-groups"></span> ' .
				__( "Add people", "politch" ) . '</a>';
		}
		
		/**
		 * print out shortcode generator html
		 */
		public function add_short_code_generator_html() {
			global $typenow;
			// check user permissions
			if ( ! current_user_can( 'politch_edit_person' ) ) {
				return; // BREAKPOINT
			}
			
			// verify the post type
			if( ! in_array( $typenow, array( 'post', 'page', 'tribe_events' ) ) ) {
				return; // BREAKPOINT
			}
			
			// get people
			$args = array(
				'nopaging'     => true,
				'post_type'    => 'politch',
				'orderby'      => 'title',
				'order'        => 'ASC',
			);
			$people = get_posts( $args );
			
			// get groups
			$groups = get_terms( 'politch_groups' );
			
			// include thickbox content
			include POLITCH_PLUGIN_PATH . '/admin/short-code-generator.php';
		}
		
	} // END class Politch_Main
} // END if ( ! class_exists( 'Politch_Main' ) )

if ( class_exists( 'Politch_Main' ) ) {
	
	if ( ! is_admin() ) {
		require_once( POLITCH_PLUGIN_PATH . '/includes/class-politch-frontend.php' );
	}
	
	$politch_main = new Politch_Main();
}
