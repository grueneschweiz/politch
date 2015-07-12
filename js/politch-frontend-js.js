/**
 * jQuery wrapper
 */
( function( $ ) {
	var Profile = new Profile();
	
	/**
	 * handels all show and hideing stuff
	 */
	function Profile() {
		
		var self = this;
		
		/**
		 * initiatelize sliders
		 */
		this.init = function init() {
			$( 'div.politch-person-fullpost' ).hide();
			$( 'div.politch-preson-preview-info div.politch-person-cv' ).hide();
			
			$( "div[id^='politch-person-']" ).each( function( index, element ) {
				$( element ).find( '.politch-toggle-button' ).click( function( event ) {
					event.preventDefault();
					$( element ).find( 'div.politch-person-fullpost' ).slideToggle();
					$( element ).find( 'div.politch-preson-preview-info div.politch-person-mail' ).slideToggle();
					$( element ).find( 'div.politch-preson-preview-info div.politch-person-cv' ).slideToggle();
					$( element ).find( 'div.politch-read-more' ).slideToggle();
				} );
			} );
		};
	}

	/**
	 * fires after DOM is loaded
	 */
	$( document ).ready(function() {
		Profile.init();
		
	});
	
	/**
	 * fires on resizeing of the window
	 */
	jQuery( window ).resize( function() {
		
	});
	
} )( jQuery );