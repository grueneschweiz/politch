<div id="politch-short-code-generator" style="display: none;">
	<div class="wrapper">
		
		<div id="politch-short-code-message" class="notice-warning"></div>
		
		<form action="#" method="post">
			<p id="politch-select-type">
				<select name="politch-select-type" class="chosen-select" data-placeholder="<?php esc_attr_e( 'Chose type', 'politch' ); ?>">
					<option value="person"><?php _e( 'Single person', 'politch' ); ?></option>
					<?php /*<option value="group"><?php _e( 'Single Group', 'politch' ); ?></option>
					<option value="groups"><?php _e( 'Multiple Groups', 'politch' ); ?></option> */ ?>
				</select>
			</p>
			
			<p id="politch-select-person" class="politch-shortcode-select">
				<?php if ( empty( $people ) ) : ?>
					<?php _e( 'No people available.', 'politch' ); ?>
				<?php else : ?>
					<select name="politch-select-person" class="chosen-select" data-placeholder="<?php esc_attr_e( 'Chose person', 'politch' ); ?>">
						<option value="-1" selected="selected"><?php _e( 'Chose person', 'politch' ); ?></option>
						<?php foreach( $people as $person ) : ?>
							<option value="<?php echo esc_attr( $person->ID ) ?>"><?php echo $person->post_title ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</p>
			
			<p id="politch-select-group" class="politch-shortcode-select politch-shortcode-select-hidden">
				<?php if ( empty( $groups ) ) : ?>
					<?php _e( 'No groups available.', 'politch' ); ?>
				<?php else : ?>
					<select name="politch-select-group" class="chosen-select" data-placeholder="<?php esc_attr_e( 'Chose group', 'politch' ); ?>">
						<option value="-1" selected="selected"><?php _e( 'Chose group', 'politch' ); ?></option>
						<?php foreach( $groups as $group ) : ?>
							<option value="<?php echo esc_attr( $group->slug ) ?>"><?php echo $group->name ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</p>
			
			<p id="politch-select-groups" class="politch-shortcode-select politch-shortcode-select-hidden">
				<?php if ( empty( $groups ) ) : ?>
					<?php _e( 'No groups available.', 'politch' ); ?>
				<?php else : ?>
					<select name="politch-select-groups" class="chosen-select" multiple data-placeholder="<?php esc_attr_e( 'Chose group', 'politch' ); ?>">
						<?php foreach( $groups as $group ) : ?>
							<option value="<?php echo esc_attr( $group->slug ) ?>"><?php echo $group->name ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</p>
			
			<p>
				<input type="checkbox" name="politch-show_election_info" value="1">
				<label for="show_election_info"><?php _e( 'Show election info', 'politch' ); ?></label>
			</p>
			
			<input id="politch-submit-shortcode" type="submit" value="<?php esc_attr_e( 'Insert shortcode', 'politch' ); ?>" class="button button-primary button-large">
		</form>
	</div>
</div>
