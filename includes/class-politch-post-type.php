<?php

/**
 * lock out script kiddies: die an direct call 
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'Politch_Post_Type' ) ) {
	
	/**
	 * contains the methodes to register the politch post type
	 * adds the needed metaboxes to the post type
	 * modifies the overview table to suit the post type
	 * 
	 * @uses    the Meta Box plugin. see http://metabox.io/
	 */
	class Politch_Post_Type {
		
		/**
		 * registers the custom post type
		 */
		public function register_post_type() {
			//register post type
			$labels = array( 
				'name'               => __( 'People', 'politch' ),
				'singular_name'      => __( 'Person', 'politch' ),
				'add_new'            => __( 'Add New Person', 'politch' ),
				'add_new_item'       => __( 'Add New', 'politch' ),
				'edit_item'          => __( 'Edit Person', 'politch' ),
				'new_item'           => __( 'New Person', 'politch' ),
				'view_item'          => __( 'View Person', 'politch' ),
				'search_items'       => __( 'Search Person', 'politch' ),
				'not_found'          => __( 'Not found any People', 'politch' ),
				'not_found_in_trash' => __( 'No Person found in Trash', 'politch' ),
				'parent_item_colon'  => __( 'Parent Person:', 'politch' ),
				'menu_name'          => __( 'People', 'politch' ),
			);
			$args = array(
				'label'               => __( 'People', 'politch' ),
				'labels'              => $labels,
				'hierarchical'        => false,
				'supports'            => array( 'title', 'thumbnail' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => true,
				'query_var'           => true,
				'can_export'          => true,
				'capability_type'     => 'post',
				'menu_icon'           => 'dashicons-groups',
				'rewrite'             => array( 'slug' => 'people' ),
			);
			register_post_type( 'politch', $args );
			
			// register custom category
			$labels = array(
				'name'                       => __( 'Groups', 'politch' ),
				'singular_name'              => __( 'Group', 'politch' ),
				'search_items'               => __( 'Search Groups', 'politch' ),
				'popular_items'              => __( 'Popular Groups', 'politch' ),
				'all_items'                  => __( 'All Groups', 'politch' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Group', 'politch' ),
				'update_item'                => __( 'Update Group', 'politch' ),
				'add_new_item'               => __( 'Add New Group', 'politch' ),
				'new_item_name'              => __( 'New Group Name', 'politch' ),
				'separate_items_with_commas' => __( 'Separate Groups with commas', 'politch' ),
				'add_or_remove_items'        => __( 'Add or remove Groups', 'politch' ),
				'choose_from_most_used'      => __( 'Choose from the most used Groups', 'politch' ),
				'not_found'                  => __( 'No Groups found.', 'politch' ),
				'menu_name'                  => __( 'Groups', 'politch' ),
			);
			
			$args = array(
				'hierarchical'          => true,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'people_groups' ),
			);
			
			register_taxonomy( 'politch_groups', 'politch', $args );
		}
		
		
		/**
		 * Add meta boxes to the politch post type
		 * 
		 * @uses    the Meta Box plugin. see http://metabox.io/
		 */
		public function add_meta_boxes( $meta_boxes ) {
			$prefix = POLITCH_PLUGIN_PREFIX;
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'personal_information',
				'title'      => __( 'Personal information', 'politch' ),
				'pages'      => array( 'politch' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Year of birth','politch' ),
						'desc' => __('Year of birth. Ex: 1970','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'year_of_birth',
						'type' => 'number',
						'min'  => 1900,
						'max'  => 2050,
						'step' => 1,
						'std'  => ''
					),
					array(
						'name' => _x('City', 'Place of residence' ,'politch' ),
						'desc' => _x('Hometown', 'Place of residence','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'city',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Role','politch' ),
						'desc' => __('List of the current roles of this person. Use a comma to separate them.','politch' ),
						'id'   => $prefix . 'roles',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Brief CV','politch' ),
						'desc' => __('Very short curivulum vitae (2-3 lines).','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'brief_cv',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 3
					),
					array(
						'name' => __('Mandates','politch' ),
						'desc' => __('List of organizations where this person has mandates. Use one line per organization.','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'mandates',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 5
					),
					array(
						'name' => __('Memberships','politch' ),
						'desc' => __('List of organizations where this person is a member of. Use one line per organization.','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'memberships',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 5
					),
				)
			);
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'election_information',
				'title'      => __( 'Election information', 'politch' ),
				'pages'      => array( 'politch' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Ticket name','politch' ),
						'desc' => __('Name of the electoral list.','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'ticket_name',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Ticket number','politch' ),
						'desc' => __('Number of the electoral list.','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'ticket_number',
						'type' => 'number',
						'std'  => ''
					),
					array(
						'name' => __('Candidate number','politch' ),
						'desc' => __('Number of the candidate on the ticket','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'candidate_number',
						'type' => 'number',
						'std'  => ''
					),
					array(
						'name' => __('District','politch' ),
						'desc' => __('Electoral district','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'district',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Smartvote Link','politch' ),
						'desc' => __('Link to the smartvote profile','politch' ),
						'id'   => $prefix . 'smartvote',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Smartspider','politch' ),
						'desc' => __('Please upload smartspider','politch' ) . ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' ),
						'id'   => $prefix . 'smartspider',
						'type' => 'image_advanced',
						'std'  => ''
					),
				)
			);
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'contact_information',
				'title'      => __( 'Contact information', 'politch' ),
				'pages'      => array( 'politch') ,
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Email','politch' ),
						'desc' => __('Email address','politch' ),
						'id'   => $prefix . 'email',
						'type' => 'email',
						'std'  => ''
					),
					array(
						'name' => __('Phone','politch' ),
						'desc' => __('Phone number','politch' ),
						'id'   => $prefix . 'phone',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Mobile','politch' ),
						'desc' => __('Mobilephone number','politch' ),
						'id'   => $prefix . 'mobile',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Website','politch' ),
						'desc' => __('Website URL','politch' ),
						'id'   => $prefix . 'website',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Facebook','politch' ),
						'desc' => __('Facebook profile or page link.','politch' ),
						'id'   => $prefix . 'facebook',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Twitter','politch' ),
						'desc' => __('Twitter profile link.','politch' ),
						'id'   => $prefix . 'twitter',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('LinkedIn','politch' ),
						'desc' => __('LinkedIn profile link.','politch' ),
						'id'   => $prefix . 'linkedin',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Google Plus','politch' ),
						'desc' => __('Google Plus profile link.','politch' ),
						'id'   => $prefix . 'google_plus',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Youtube','politch' ),
						'desc' => __('Youtube profile link.','politch' ),
						'id'   => $prefix . 'youtube',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Vimeo','politch' ),
						'desc' => __('Vimeo profile link.','politch' ),
						'id'   => $prefix . 'vimeo',
						'type' => 'url',
						'std'  => ''
					),
				),
			);
			
			return $meta_boxes;
		}
		
		/**
		 * register the custom post overview 
		 */
		public function register_overview() {
			add_filter( 'manage_politch_posts_columns', array( &$this, 'overview_columns_head' ) );
			add_action( 'manage_politch_posts_custom_column', array( &$this,'overview_columns_content' ), 10, 2 );
		}
		
		/**
		 * display a description for our custom column in the overview table
		 * 
		 * @access    must be public, else wp can't call the function
		 */
		public function overview_columns_head( $defaults ) {
			$defaults['politch_featured_image'] = __( 'Portrait', 'politch' );
			$defaults['politch_post_id']    = __( 'ID', 'politch' );
			return $defaults;
		}
		
		/**
		 * display the portrait in the overview table
		 * 
		 * @access    must be public, else wp can't call the function
		 */
		public function overview_columns_content( $column_name, $post_ID ) {
			if ( $column_name == 'politch_featured_image' ) {
				$post_featured_image = $this->get_featured_image( $post_ID );
				if ( $post_featured_image ) {
					echo '<img src="' . $post_featured_image . '" />';
				}
			}
			if ( $column_name == 'politch_post_id' ) {
				echo $post_ID;
			}
		}
		
		/**
		 * get featured image of the politch post type
		 */
		private function get_featured_image( $post_ID ) {
			$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
			if ( $post_thumbnail_id ) {
				$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
				return $post_thumbnail_img[0];
			}
		}
	}
}