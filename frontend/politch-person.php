<?php
ob_start();
?>

<?php 
//prefix taxonomy (groups) slugs
foreach( $person['groups_slugs'] as $slug ) {
	$slugs[] = 'politch-group-' . $slug;
}
?>
<div id="politch-person-<?php echo $person['id']; ?>" class="politch-person-preview politch-person <?php echo implode( ' ', $slugs ); ?>">
	<header class="entry-header politch-entry-header">
		<a rel="bookmark" href="#" data-politch-id="<?php echo $person['id']; ?>">
			<div class="attachment-post-thumbnail attachment-default-post-thumbnail-wrapper">
				<?php echo $person['portrait']; ?>
			</div>
		</a>
		<h1 class="entry-title politch-entry-title">
			<a rel="bookmark" href="#" data-politch-id="<?php echo $person['id']; ?>">
				<?php echo $person['name']; ?>
			</a>
		</h1>
		<?php if( $show_election_info ) : ?>
			<h2>
				<?php 
					$yob_n_city = array();
					if ( ! empty( $person[$prefix.'year_of_birth'][0] ) ) {
						array_push( $yob_n_city, $person[$prefix.'year_of_birth'][0] );
					}
					if ( ! empty( $person[$prefix.'city'][0] ) ) {
						array_push( $yob_n_city, $person[$prefix.'city'][0] );
					}
					if ( ! empty( $yob_n_city ) ) {
						$yob_n_city_string = implode( ', ', $yob_n_city );
						echo $yob_n_city_string;
					}
				?>
			</h2>
		<?php endif; ?>
		<div class="politch-preson-preview-info">
			<?php if ( ! empty( $person[$prefix.'roles'][0] ) ) : ?>
			<div class="politch-person-role">
				<?php echo $person[$prefix.'roles'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'email'][0] ) ) : ?>
			<div class="politch-person-mail">
				<a href="mailto:<?php echo $person[$prefix.'email'][0]; ?>"><?php echo $person[$prefix.'email'][0]; ?></a>
			</div>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</header>
	<div class="politch-person-fullpost">
		
		<?php if ( ! empty( $person[$prefix.'brief_cv'][0] ) ) : ?>
		<div class="politch-person-cv">
			<?php echo $person[$prefix.'brief_cv'][0]; ?>
		</div>
		<?php endif; ?>
		
		
		<div class="politch-contact">
			<?php if ( ! empty( $person[$prefix.'email'][0] ) ) : ?>
			<div class="politch-person-mail">
				<span class="politch-field-label"><?php _e( 'Mail:', 'politch' ); ?></span>
				<a href="mailto:<?php echo $person[$prefix.'email'][0]; ?>"><?php echo $person[$prefix.'email'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'phone'][0] ) ) : ?>
			<div class="politch-person-phone">
				<span class="politch-field-label"><?php _e( 'Phone:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'phone'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'mobile'][0] ) ) : ?>
			<div class="politch-person-mobile">
				<span class="politch-field-label"><?php _e( 'Mobile:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'mobile'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'website'][0] ) ) : ?>
			<div class="politch-person-website">
				<span class="politch-field-label"><?php _e( 'Website:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'website'][0]; ?>"><?php echo $person[$prefix.'website'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'facebook'][0] ) ) : ?>
			<div class="politch-person-facebook">
				<span class="politch-field-label"><?php _e( 'Facebook:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'facebook'][0]; ?>"><?php echo $person[$prefix.'facebook'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'twitter'][0] ) ) : ?>
			<div class="politch-person-twitter">
				<span class="politch-field-label"><?php _e( 'Twitter:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'twitter'][0]; ?>"><?php echo $person[$prefix.'twitter'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'linkedin'][0] ) ) : ?>
			<div class="politch-person-linkedin">
				<span class="politch-field-label"><?php _e( 'LinkedIn:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'linkedin'][0]; ?>"><?php echo $person[$prefix.'linkedin'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'google_plus'][0] ) ) : ?>
			<div class="politch-person-google_plus">
				<span class="politch-field-label"><?php _e( 'Google Plus:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'google_plus'][0]; ?>"><?php echo $person[$prefix.'google_plus'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'youtube'][0] ) ) : ?>
			<div class="politch-person-youtube">
				<span class="politch-field-label"><?php _e( 'Youtube:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'youtube'][0]; ?>"><?php echo $person[$prefix.'youtube'][0]; ?></a>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'vimeo'][0] ) ) : ?>
			<div class="politch-person-vimeo">
				<span class="politch-field-label"><?php _e( 'Vimeo:', 'politch' ); ?></span>
				<a href="<?php echo $person[$prefix.'vimeo'][0]; ?>"><?php echo $person[$prefix.'vimeo'][0]; ?>
			</div>
			<?php endif; ?>
		</div>
		
		<?php if( $show_election_info ) : ?>
			<h2><?php _e( 'Election info', 'politch' ); ?></h2> 
			
			<?php if ( ! empty( $person[$prefix.'ticket_name'][0] ) ) : ?>
			<div class="politch-person-ticket_name">
				<span class="politch-field-label"><?php _e( 'Ticket name:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'ticket_name'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'ticket_number'][0] ) ) : ?>
			<div class="politch-person-ticket_number">
				<span class="politch-field-label"><?php _e( 'Ticket number:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'ticket_number'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'candidate_number'][0] ) ) : ?>
			<div class="politch-person-candidate_number">
				<span class="politch-field-label"><?php _e( 'Candidate number:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'candidate_number'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'district'][0] ) ) : ?>
			<div class="politch-person-district">
				<span class="politch-field-label"><?php _e( 'District:', 'politch' ); ?></span>
				<?php echo $person[$prefix.'district'][0]; ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'smartspider'][0] ) ) : ?>
			<div class="politch-person-smartspider">
				<h3><?php _e( 'Smartvote', 'politch' ); ?></h3>
				<?php echo wp_get_attachment_image( 
					(int) $person[$prefix.'smartspider'][0], 
					apply_filters( 'politch_fullpost_smartspider_image_size', 'large' ) 
				); ?>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'mandates'][0] ) ) : ?>
			<div class="politch-person-mandates">
				<h3><?php _e( 'Mandates', 'politch' ); ?></h3>
				<ul>
				<?php $mandates = explode( "\n", $person[$prefix.'mandates'][0] );
					foreach( $mandates as $mandate ) {
						echo "<li>$mandate</li>";
					}
				?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if ( ! empty( $person[$prefix.'memberships'][0] ) ) : ?>
			<div class="politch-person-memberships">
				<h3><?php _e( 'Memberships', 'politch' ); ?></h3>
				<ul>
				<?php $memberships = explode( "\n", $person[$prefix.'memberships'][0] );
					foreach( $memberships as $membership ) {
						echo "<li>$membership</li>";
					}
				?>
				</ul>
			</div>
			<?php endif; ?>
			
		<?php endif; ?>
		
	</div>
</div>

<?php
$buffer = ob_get_clean();
