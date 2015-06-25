<?php

// die on direct call
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( ! class_exists( 'Politch_Frontend' ) ) {

	class Politch_Frontend {
		
		/**
		 * Process short code
		 * 
		 * @var     array    $atts    provided from WP's add_shortcode() function
		 * @return  string            html with the content to be displayed
		 */
		public function process_short_code( $atts ) {
			$error_msg = __( 'Unknown shortcode or invalid arguments.', 'politch' );
			
			// the type is crutial. exit if it wasn't set
			if ( ! isset( $atts['type'] ) ) {
				return $error_msg; // BREAKPOINT
			}
			
			// dont show election info if wasn't explicitly set
			if ( ! isset( $atts['show_election_info'] ) ) {
				$atts['show_election_info'] = false;
			}
			
			// choose by type
			switch( $atts['type'] ) {
				case 'person':
					$query_args = $this->get_person_query( $atts );
					$output = $this->get_content_html( $query_args, $atts['show_election_info'] );
					break;
				
				case 'group':
					$query_args = $this->get_group_query( $atts, $atts['show_election_info'] );
					$output = $this->get_content_html( $query_args );
					break;
				
				case 'groups':
					
					// check if slugs were given
					if ( empty( $atts['groups_slugs'] ) ) {
						// no slugs given
						$output = -1;
						break;
					} else {
						// we do have slugs
						
						$slugs_string = str_replace( ' ', '', $atts['groups_slugs'] );// clean out whitespace
						$slugs = explode( ',', $slugs_string ); // split slugs-string into array by ','
						
						$output = '';
						
						foreach( $slugs as $slug ) {
							$atts['group_slug'] = $slug;
							$query_args = $this->get_group_query( $atts );
							$buffer = $this->get_content_html( $query_args, $atts['show_election_info'] );
							if ( -1 == $buffer ) {
								$output = -1;
								break;
							} else {
								$output .= $buffer;
							}
						}
					}
					break;
				
				default:
					// if no type matched
					return $error_msg;
			}
			
			// if invalid args were supplied
			if ( -1 == $output ) {
				return $error_msg; // BREAKPOINT
			}
			
			return $output;
		}
		
		/**
		 * Prepare the WP_Query args to get a single person
		 * 
		 * @var    array    $atts   provided from WP's add_shortcode() function
		 * @return array|int        the arguments to get a single person using the WP_Query object
		 *                          in case the given $atts are incomplete return -1 
		 */
		private function get_person_query( $atts ) {
			// return -1 if no id was given 
			if ( empty( $atts['id'] ) ) {
				return -1; // BREAKPOINT
			}
			
			return array(
				'post_type' => 'politch', // be sure we only show people
				'p'         => (int) $atts['id'],
			);
		}
		
		/**
		 * Prepare the WP_Query args to get a single person
		 * 
		 * @var    array    $atts   provided from WP's add_shortcode() function
		 * @return array|int        the arguments to get a single person using the WP_Query object
		 *                          in case the given $atts are incomplete return -1 
		 */
		private function get_group_query( $atts ) {
			// return -1 if no group was given
			if ( empty( $atts['group_slug'] ) ) {
				return -1; // BREAKPOINT
			}
			
			return array(
				'post_type' => 'politch', // be sure we only show people
				'tax_query' => array(
					array(
						'taxonomy' => 'politch_groups',
						'field'    => 'slug',
						'terms'    => (string) $atts['group_slug'],
					),
				),
			);
		}
		
		/**
		 * generates the content html and returns is
		 * contains the people loop
		 * 
		 * @var      array    $query_args          the arguments for the WP_Query constructor
		 * @var      mixed    $show_election_info  if value can be convertet to true, election info will be shown
		 * @return   string|int                    the final html output
		 *                                         -1 on incomplete query_args
		 */
		private function get_content_html( $query_args, $show_election_info = false ) {
			// return -1 if no id was given 
			if ( -1 == $query_args ) {
				return -1; // BREAKPOINT
			}
			
			$show_election_info = 'true' == (string) $show_election_info || '1' == (string) $show_election_info ? true : false;
			
			$prefix = POLITCH_PLUGIN_PREFIX;
			$output = '';
			
			// the query
			$query = new WP_Query( $query_args );
			
			// the loop
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					
					$post_id = get_the_ID();
					
					$person                = get_post_meta( $post_id );
					$person['id']          = $post_id;
					$person['name']        = get_the_title();
					$person['portrait']    = $this->get_the_portrait( $post_id );
					$person['group_names'] = wp_get_post_terms( $post_id, 'politch_groups', array( 'fields' => 'names' ) );
					$person['groups_slugs'] = wp_get_post_terms( $post_id, 'politch_groups', array( 'fields' => 'slugs' ) );
					
					include POLITCH_PLUGIN_PATH . '/frontend/politch-person.php';
					$output .= $buffer;
				}
			} else {
				// no posts found
				
				//get group name
				$group = get_term_by( 'slug', $query_args['tax_query'][0]['terms'], 'politch_groups' );
				
				// if no group was found use the given group slug
				if ( empty( $group ) ){
					$group_name = $query_args['tax_query'][0]['terms'];
				} else {
					$group_name = $group->name;
				}
				
				// return error message
				return sprintf( __( 'No people found in the group "%s".', 'politch' ), $group_name ); // BREAKPOINT
			}
			
			// Restore original Post Data
			wp_reset_postdata();
			
			return $output;
		}
		
		/**
		 * get the portrait or an blank avatar (full html)
		 * 
		 * @var      int    $post_id    the post id of the person
		 * @return   string             the html
		 */
		private function get_the_portrait( $post_id ) {
			if ( has_post_thumbnail( $post_id ) ) {
				// the given thumbnail
				return get_the_post_thumbnail( $post_id, apply_filters( 'politch_portrait_thumbnail_size', 'thumbnail' ) );
			} else {
				// if thumbnail is missing
				// return a blank avatar
				$width      = get_option( 'thumbnail_size_w' );
				$height     = get_option( 'thumbnail_size_h' );
				$avatar_url = plugin_dir_url( POLITCH_PLUGIN_PATH . '/politch.php' ) . 'img/blank-avatar.png';
				return '<img class="attachment-post-thumbnail politch-blank-avatar wp-post-image" width="'.$width.'" height="'.$height.'" alt="'.__( 'Blank avatar', 'politch' ).'" src="'.$avatar_url.'">';
			}
		}
	}
}
	