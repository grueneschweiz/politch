<div class="wrap">
	<h2><?php _e( 'People options', 'politch' ); ?></h2>
	
	<form action="options.php" method="post">
			
			<?php
				settings_fields( 'politch_options' );
				do_settings_sections( 'politch_options' );
				submit_button();
			?>
			
	</form>
</div>