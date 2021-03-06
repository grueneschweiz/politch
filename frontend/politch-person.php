<?php
ob_start();
?>

<?php 
//prefix taxonomy (groups) slugs
$slugs = array();
foreach( $person['groups_slugs'] as $slug ) {
	$slugs[] = 'politch-group-' . $slug;
}
?>
<div id="politch-person-<?php echo $person['id']; ?>" class="politch-person-preview politch-person <?php echo implode( ' ', $slugs ); ?>">
	<header class="entry-header politch-entry-header">
		<div class="attachment-post-thumbnail attachment-default-post-thumbnail-wrapper <?php echo $this->has_more( $person ) ? 'politch-toggle-button' : ''; ?>" data-politch-id="<?php echo $person['id']; ?>">
			<?php echo $person['portrait']; ?>
		</div>
		
		<div class="politch-preson-preview-info">
			<h2 class="entry-title politch-entry-title <?php echo $this->has_more( $person ) ? 'politch-toggle-button' : ''; ?>" data-politch-id="<?php echo $person['id']; ?>">
				<?php echo $person['name']; ?>
			</h2>
			
			<h3 class="politch-yob-n-city">
				<?php 
					$yob_n_city = array();
					if ( $this->is_visible( 'year_of_birth' ) ) {
						if ( ! empty( $person[$prefix.'year_of_birth'][0] ) ) {
							array_push( $yob_n_city, $person[$prefix.'year_of_birth'][0] );
						}
					}
					if ( $this->is_visible( 'city' ) ) {
						if ( ! empty( $person[$prefix.'city'][0] ) ) {
							array_push( $yob_n_city, $person[$prefix.'city'][0] );
						}
					}
					if ( ! empty( $yob_n_city ) ) {
						$yob_n_city_string = implode( ', ', $yob_n_city );
						echo $yob_n_city_string;
					}
				?>
			</h3>
			
			<?php if ( $this->is_visible( 'roles' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'roles'][0] ) ) : ?>
			<div class="politch-person-role">
				<?php echo $person[$prefix.'roles'][0]; ?>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->is_visible( 'email' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'email'][0] ) ) : ?>
			<div class="politch-person-mail">
				<a href="mailto:<?php echo $person[$prefix.'email'][0]; ?>"><?php echo $person[$prefix.'email'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->is_visible( 'brief_cv' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'brief_cv'][0] ) ) : ?>
				<div class="politch-person-cv">
					<?php echo $person[$prefix.'brief_cv'][0]; ?>
				</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->has_more( $person ) ): ?>
				<div class="politch-read-more">
					<a class="politch-toggle-button read-more" data-politch-id="<?php echo $person['id']; ?>">
						<?php printf( _x( '%s Read more', 'Read more arrow', 'politch' ), '&raquo;'); ?>
					</a>
				</div>
			<?php endif; ?> 
			
		</div>
		<div class="clear"></div>
	</header>
	<div class="politch-person-fullpost">
		
		<div class="politch-contact">
			<?php if ( $this->is_visible( 'email' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'email'][0] ) ) : ?>
			<div class="politch-person-mail">
				<span class="politch-field-label"><?php _e( 'Mail:', 'politch' ); ?></span>
				<a href="mailto:<?php echo $person[$prefix.'email'][0]; ?>"><?php echo $person[$prefix.'email'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->is_visible( 'phone' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'phone'][0] ) ) : ?>
			<div class="politch-person-phone">
				<span class="politch-field-label"><?php _e( 'Phone:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'phone'][0]; ?>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->is_visible( 'mobile' ) ) : ?>
			<?php if ( ! empty( $person[$prefix.'mobile'][0] ) ) : ?>
			<div class="politch-person-mobile">
				<span class="politch-field-label"><?php _e( 'Mobile:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'mobile'][0]; ?>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $this->is_visible( 'website' ) ) : ?>
            <?php if ( ! empty( $person[$prefix.'website'][0] ) ) : ?>
                <div class="politch-person-website">
                    <span class="politch-field-label"><?php _e( 'Website:', 'politch' ); ?></span>
                    <a target="_blank" href="<?php echo $person[$prefix.'website'][0]; ?>" title="<?php _e( 'Website', 'politch' ); ?>">
                        <?php echo preg_replace('/^https?:\/\//','', preg_replace('/\/$/', '', $person[$prefix.'website'][0])); ?>
                    </a>
                </div>
            <?php endif; ?>
			<?php endif; ?>
   
			<div class="politch-social-buttons">
				<?php if ( $this->is_visible( 'facebook' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'facebook'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'facebook'][0]; ?>" title="<?php _e( 'Facebook', 'politch' ); ?>" class="politch-person-facebook politch-icon politch-icon-facebook">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Facebook', 'politch' ); ?>: <?php echo $person[$prefix.'facebook'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'twitter' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'twitter'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'twitter'][0]; ?>" title="<?php _e( 'Twitter', 'politch' ); ?>" class="politch-person-twitter politch-icon politch-icon-twitter">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Twitter', 'politch' ); ?>: <?php echo $person[$prefix.'twitter'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'linkedin' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'linkedin'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'linkedin'][0]; ?>" title="<?php _e( 'LinkedIn', 'politch' ); ?>" class="politch-person-linkedin politch-icon politch-icon-linkedin">
					<span class="politch-field-label screen-reader-text"><?php _e( 'LinkedIn', 'politch' ); ?>: <?php echo $person[$prefix.'linkedin'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'google_plus' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'google_plus'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'google_plus'][0]; ?>" title="<?php _e( 'Google Plus', 'politch' ); ?>" class="politch-person-google_plus politch-icon politch-icon-google_plus">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Google Plus', 'politch' ); ?>: <?php echo $person[$prefix.'google_plus'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'youtube' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'youtube'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'youtube'][0]; ?>" title="<?php _e( 'Youtube', 'politch' ); ?>" class="politch-person-youtube politch-icon politch-icon-youtube">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Youtube', 'politch' ); ?>: <?php echo $person[$prefix.'youtube'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'vimeo' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'vimeo'][0] ) ) : ?>
				<a href="<?php echo $person[$prefix.'vimeo'][0]; ?>" title="<?php _e( 'Vimeo', 'politch' ); ?>" class="politch-person-vimeo politch-icon politch-icon-vimeo">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Vimeo', 'politch' ); ?>: <?php echo $person[$prefix.'vimeo'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
			
				<?php if ( $this->is_visible( 'smartvote' ) ) : ?>
				<?php if ( ! empty( $person[$prefix.'smartvote'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'smartvote'][0]; ?>" title="<?php _e( 'Smartvote', 'politch' ); ?>" class="politch-person-smartvote politch-icon politch-icon-smartvote">
					<span class="politch-field-label screen-reader-text"><?php _e( 'Smartvote', 'politch' ); ?>: <?php echo $person[$prefix.'smartvote'][0]; ?></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>
				
				<div class="clear"></div>
			</div>
		</div>
		
		<?php if ( ( 
			( ! empty( $person[$prefix.'slogan'][0] ) && $this->is_visible( 'slogan' ) ) ||
			( ! empty( $person[$prefix.'ticket_name'][0] ) && $this->is_visible( 'ticket_name' ) ) ||
			( ! empty( $person[$prefix.'ticket_number'][0] ) && $this->is_visible( 'ticket_number' ) ) ||
			( ! empty( $person[$prefix.'candidate_number'][0] ) && $this->is_visible( 'candidate_number' ) ) ||
			( ! empty( $person[$prefix.'district'][0] ) && $this->is_visible( 'district' ) ) ||
			( ! empty( $person[$prefix.'smartspider'][0] ) && $this->is_visible( 'smartspider' ) ) ||
			( ! empty( $person[$prefix.'mandates'][0] ) && $this->is_visible( 'mandates' ) ) ||
			( ! empty( $person[$prefix.'memberships'][0] ) && $this->is_visible( 'memberships' ) )
		 ) ) : ?>
			<h2><?php _e( 'Election info', 'politch' ); ?></h2>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'slogan' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'slogan'][0] ) ) : ?>
		<div class="politch-person-slogan">
			<blockquote><?php echo $person[$prefix.'slogan'][0]; ?></blockquote>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'ticket_name' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'ticket_name'][0] ) ) : ?>
		<div class="politch-person-ticket_name">
			<span class="politch-field-label"><?php _e( 'Ticket name:', 'politch' ); ?></span>
			<?php echo $person[$prefix.'ticket_name'][0]; ?>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'ticket_number' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'ticket_number'][0] ) ) : ?>
		<div class="politch-person-ticket_number">
			<span class="politch-field-label"><?php _e( 'Ticket number:', 'politch' ); ?></span>
			<?php echo $person[$prefix.'ticket_number'][0]; ?>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'candidate_number' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'candidate_number'][0] ) ) : ?>
		<div class="politch-person-candidate_number">
			<span class="politch-field-label"><?php _e( 'Candidate number:', 'politch' ); ?></span>
			<?php echo $person[$prefix.'candidate_number'][0]; ?>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'district' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'district'][0] ) ) : ?>
		<div class="politch-person-district">
			<span class="politch-field-label"><?php _e( 'District:', 'politch' ); ?></span>
			<?php echo $person[$prefix.'district'][0]; ?>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'smartspider' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'smartspider'][0] ) ) : ?>
		<div class="politch-person-smartspider">
			<h3><?php _e( 'Smartvote', 'politch' ); ?></h3>
			<?php if ( ! empty( $person[$prefix.'smartvote'][0] ) ) : ?>
				<a target="_blank" href="<?php echo $person[$prefix.'smartvote'][0]; ?>" title="<?php _e( 'Smartvote', 'politch' ); ?>" alt="Smartvote">
			<?php endif; ?>
				<?php echo wp_get_attachment_image( 
					(int) $person[$prefix.'smartspider'][0], 
					apply_filters( 'politch_fullpost_smartspider_image_size', 'large' ) 
				); ?>
			<?php if ( ! empty( $person[$prefix.'smartvote'][0] ) ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'mandates' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'mandates'][0] ) ) : ?>
		<div class="politch-person-mandates">
			<h3><?php _e( 'Mandates', 'politch' ); ?></h3>
			<ul>
			<?php $mandates = explode( "\n", $person[$prefix.'mandates'][0] );
				foreach( $mandates as $mandate ) {
					$m = preg_replace('/^\s+/', '', $mandate );
					if ( ! empty( $m ) ) {
						echo "<li>$m</li>";
					}
				}
			?>
			</ul>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
		
		<?php if ( $this->is_visible( 'memberships' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'memberships'][0] ) ) : ?>
		<div class="politch-person-memberships">
			<h3><?php _e( 'Memberships', 'politch' ); ?></h3>
			<ul>
			<?php $memberships = explode( "\n", $person[$prefix.'memberships'][0] );
				foreach( $memberships as $membership ) {
					$m = preg_replace('/^\s+/', '', $membership );
					if ( ! empty( $m ) ) {
						echo "<li>$m</li>";
					}
				}
			?>
			</ul>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
			
		<?php if ( $this->is_visible( 'additional_information_title' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'additional_information_title'][0] ) ) : ?>
		<div class="politch-person-additional_information_title">
			<h2><?php echo $person[$prefix.'additional_information_title'][0]; ?></h2>
		</div>
		<?php endif; ?>
		<?php endif; ?> 
			
		<?php if ( $this->is_visible( 'additional_information_body' ) ) : ?>
		<?php if ( ! empty( $person[$prefix.'additional_information_body'][0] ) ) : ?>
		<div class="politch-person-additional_information_body">
			<?php echo do_shortcode( $person[$prefix.'additional_information_body'][0] ); ?>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		
	</div>
</div>

<?php
$buffer = ob_get_clean();
