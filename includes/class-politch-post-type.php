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
		 * holds the visibility options
		 */
		private $visibility_options;
		
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
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => false,
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
			
			$only_election_notice = ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' );
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'personal_information',
				'title'      => __( 'Personal information', 'politch' ),
				'post_types' => array( 'politch' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Year of birth','politch' ),
						'desc' => __('Year of birth. Ex: 1970','politch' ) . $this->get_visibility_notice( 'year_of_birth' ),
						'id'   => $prefix . 'year_of_birth',
						'type' => 'number',
						'min'  => 1900,
						'max'  => 2050,
						'step' => 1,
						'std'  => ''
					),
					array(
						'name' => _x('City', 'Place of residence' ,'politch' ),
						'desc' => _x('Hometown', 'Place of residence','politch' ) . $this->get_visibility_notice( 'city' ),
						'id'   => $prefix . 'city',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Role','politch' ),
						'desc' => __('List of the current roles of this person. Use a comma to separate them.','politch' ) . $this->get_visibility_notice( 'roles' ),
						'id'   => $prefix . 'roles',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Brief CV','politch' ),
						'desc' => __('Very short curivulum vitae (2-3 lines).','politch' ) . $this->get_visibility_notice( 'brief_cv' ),
						'id'   => $prefix . 'brief_cv',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 3
					),
					array(
						'name' => __('Mandates','politch' ),
						'desc' => __('List of organizations where this person has mandates. Use one line per organization.','politch' ) . $this->get_visibility_notice( 'mandates' ),
						'id'   => $prefix . 'mandates',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 5
					),
					array(
						'name' => __('Memberships','politch' ),
						'desc' => __('List of organizations where this person is a member of. Use one line per organization.','politch' ) . $this->get_visibility_notice( 'memberships' ),
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
				'post_types' => array( 'politch' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Slogan','politch' ),
						'desc' => __('The slogan of the candidate','politch' ) . $this->get_visibility_notice( 'slogan' ),
						'id'   => $prefix . 'slogan',
						'type' => 'textarea',
						'std'  => '',
						'rows' => 5
					),
					array(
						'name' => __('Ticket name','politch' ),
						'desc' => __('Name of the electoral list.','politch' ) . $this->get_visibility_notice( 'ticket_name' ),
						'id'   => $prefix . 'ticket_name',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Ticket number','politch' ),
						'desc' => __('Number of the electoral list.','politch' ) . $this->get_visibility_notice( 'ticket_number' ),
						'id'   => $prefix . 'ticket_number',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Candidate number','politch' ),
						'desc' => __('Number of the candidate on the ticket','politch' ) . $this->get_visibility_notice( 'candidate_number' ),
						'id'   => $prefix . 'candidate_number',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('District','politch' ),
						'desc' => __('Electoral district','politch' ) . $this->get_visibility_notice( 'district' ),
						'id'   => $prefix . 'district',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Smartvote Link','politch' ),
						'desc' => __('Link to the smartvote profile','politch' ) . $this->get_visibility_notice( 'smartvote' ),
						'id'   => $prefix . 'smartvote',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Smartspider','politch' ),
						'desc' => __('Please upload smartspider','politch' ) . $this->get_visibility_notice( 'smartspider' ),
						'id'   => $prefix . 'smartspider',
						'type' => 'image_advanced',
						'std'  => ''
					),
				)
			);
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'contact_information',
				'title'      => __( 'Contact information', 'politch' ),
				'post_types' => array( 'politch') ,
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Email','politch' ),
						'desc' => __('Email address','politch' ) . $this->get_visibility_notice( 'email' ),
						'id'   => $prefix . 'email',
						'type' => 'email',
						'std'  => ''
					),
					array(
						'name' => __('Phone','politch' ),
						'desc' => __('Phone number','politch' ) . $this->get_visibility_notice( 'phone' ),
						'id'   => $prefix . 'phone',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Mobile','politch' ),
						'desc' => __('Mobilephone number','politch' ) . $this->get_visibility_notice( 'mobile' ),
						'id'   => $prefix . 'mobile',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __('Website','politch' ),
						'desc' => __('Website URL','politch' ) . $this->get_visibility_notice( 'website' ),
						'id'   => $prefix . 'website',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Facebook','politch' ),
						'desc' => __('Facebook profile or page link.','politch' ) . $this->get_visibility_notice( 'facebopok' ),
						'id'   => $prefix . 'facebook',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Twitter','politch' ),
						'desc' => __('Twitter profile link.','politch' ) . $this->get_visibility_notice( 'twitter' ),
						'id'   => $prefix . 'twitter',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('LinkedIn','politch' ),
						'desc' => __('LinkedIn profile link.','politch' ) . $this->get_visibility_notice( 'linkedin' ),
						'id'   => $prefix . 'linkedin',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Google Plus','politch' ),
						'desc' => __('Google Plus profile link.','politch' ) . $this->get_visibility_notice( 'google_plus' ),
						'id'   => $prefix . 'google_plus',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Youtube','politch' ),
						'desc' => __('Youtube profile link.','politch' ) . $this->get_visibility_notice( 'youtube' ),
						'id'   => $prefix . 'youtube',
						'type' => 'url',
						'std'  => ''
					),
					array(
						'name' => __('Vimeo','politch' ),
						'desc' => __('Vimeo profile link.','politch' ) . $this->get_visibility_notice( 'vimeo' ),
						'id'   => $prefix . 'vimeo',
						'type' => 'url',
						'std'  => ''
					),
				),
			);
			
			$meta_boxes[] = array(
				'id'         => $prefix . 'additional_information',
				'title'      => __( 'Additional information', 'politch' ),
				'post_types' => array( 'politch' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => array(
					array(
						'name' => __('Additional Information Title','politch' ),
						'desc' => __('Title of the additional inforamtion. Leave blank for no title.','politch' ) . $this->get_visibility_notice( 'additional_information_title' ),
						'id'   => $prefix . 'additional_information_title',
						'type' => 'text',
						'std'  => __( '', 'politch' ),
					),
					array(
						'name' => __('Additional Information Body','politch' ),
						'desc' => __('Add additional inforamtion here. Leave blank for no additional information.','politch' ) . $this->get_visibility_notice( 'additional_information_body' ),
						'id'   => $prefix . 'additional_information_body',
						'type' => 'wysiwyg',
						'std'  => __( '', 'politch' ),
					),
				)
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
		
		/**
		 * get visibility notice
		 * 
		 * if the field is only visible for election profiles return a string which says exactly this ;)
		 * else NULL will be returned.
		 * 
		 * @param    string    $id    the option id (whitout prefix)
		 * @return   string           the notice HTML or NULL
		 */
		private function get_visibility_notice( $id ) {
			
			if ( empty( $this->visibility_options ) ) {
				$this->visibility_options = get_option( POLITCH_PLUGIN_PREFIX . 'field_visibility', array() );
			}
			
			if ( isset( $this->visibility_options[ POLITCH_PLUGIN_PREFIX . $id ] ) ) {
				return ' <span class="politch-mark">*</span>' . __( 'Election only', 'politch' );
			} else {
				return null;
			}
		}
          
          /**
           * Removes the links in the post update messages for politch
           * 
           * Get rid off all previewing and direct links
           * 
           * @since 1.3.6
           */
          public function remove_post_update_message_links( $messages ) {
               if ( 'politch' == get_post_type() ) {
                    $messages['post'][1] = __( 'Post updated.', 'politch' );
                    $messages['post'][6] = __( 'Post published.', 'politch' );
                    $messages['post'][8] = __( 'Post submitted.', 'politch' );
                    $messages['post'][10] = __( 'Post draft updated.', 'politch' );
               }
               return $messages;
          }
          
          /**
           * Remove the quick edit link in the posts table
           * 
           * @since 1.3.6
           */
          public function remove_quickedit_link( $action ) {
               if ( 'politch' == get_post_type() ) {
                    unset( $action['inline hide-if-no-js'] );
               }
               return $action;
          }
          
          /**
           * Set template for direct single person view
           * 
           * Usually people will be displayed by shortcode. But for better
           * CEO we also want single pages of every person. This method sets
           * the template for this single persons.
           * 
           * @since 1.4.0
           */
          public function set_politch_single_template( $single_template ) {
               global $post;
               
               if ( $post->post_type == 'politch' ) {
                    $single_template = POLITCH_PLUGIN_PATH . '/frontend/single-politch.php';
               }
               return $single_template;
          }
          
	}
}