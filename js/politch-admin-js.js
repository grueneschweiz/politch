/**
 * jQuery wrapper
 */
( function( $ ) {
	var ShortCodeGen = new ShortCodeGen();
	
	/**
	 * handles all the functionality of the short code generator
	 */
	function ShortCodeGen() {
		
		var self = this;
		
		/**
		 * initiatelize
		 * 
		 * add chosen functionality
		 * add change events
		 * add submit event
		 */
		this.init = function init() {
			// add chosen functionality
			$( '.chosen-select' ).chosen( {
				disable_search_threshold: 7, 
				no_results_text: "Oops, nothing found!",
				width: '300px'
			} );
			
			// add change events
			$( 'select[name="politch-select-type"]' ).change( function() {
				// hide all selects execpt the type selector
				$( '.politch-shortcode-select' ).addClass( 'politch-shortcode-select-hidden' );
				// show the corresponding one to the type selection
				$( '#politch-select-' + $( this ).val() ).removeClass( 'politch-shortcode-select-hidden' );
			});
			
			$( '#politch-submit-shortcode' ).click( function( event ) {
				// dont submit
				event.preventDefault();
				
				// check if input is valid
				if ( false === self.isInputValid() ) {
					return; // BREAKPOINT
				}
				
				// create shortcode
				var shortcode = self.generateShortcode();
				
				// insert shortcode
				window.send_to_editor( shortcode );
				
				// close thickbox
				tb_remove();
			});
		};
		
		/**
		 * validate input
		 * 
		 * returns true if valid. else false and shows an error message.
		 * 
		 * @return    bool    true if valid, else false
		 */
		this.isInputValid = function isInputValid() {
			
			$( '#politch-short-code-message' ).hide();
			
			switch( $( 'select[name="politch-select-type"]' ).val() ) {
				case 'person':
					if ( -1 !== $( 'select[name="politch-select-person"]' ).val() ) {
						return true;
					} else {
						$( '#politch-short-code-message' ).text( 'Please select a person.' ).show();
						return false;
					}
					break;
				
				case 'group':
					if ( -1 !== $( 'select[name="politch-select-group"]' ).val() ) {
						return true;
					} else {
						$( '#politch-short-code-message' ).text( 'Please select a group.' ).show();
						return false;
					}
					break;
				
				case 'groups':
					if ( null !== $( 'select[name="politch-select-groups"]' ).val() ) {
						return true;
					} else {
						$( '#politch-short-code-message' ).text( 'Please select one or multiple groups.' ).show();
						return false;
					}
					break;
				
				default:
					return false;
			}
		};
		
		
		/**
		 * generate shortcode
		 * 
		 * @return    string    the shortcode
		 */
		this.generateShortcode = function generateShortcode() {
			var show_election_info = '';
			
			if ( 0 < $( 'input[name="politch-show_election_info"]:checked' ).length ) {
				show_election_info = ' show_election_info="1"';
			}
			
			switch( $( 'select[name="politch-select-type"]' ).val() ) {
				case 'person':
					return '[politch type="person" id="' + $( 'select[name="politch-select-person"]' ).val() + '"' + show_election_info +']';
					break;
				
				case 'group':
					return '[politch type="group" group_slug="' + $( 'select[name="politch-select-group"]' ).val() + '"' + show_election_info +']';
					break;
				
				case 'groups':
					return '[politch type="groups" group_slugs="' + $( 'select[name="politch-select-groups"]' ).val() + '"' + show_election_info +']';
					break;
				
				default:
					return false;
			}
		};
		
	}

	/**
	 * fires after DOM is loaded
	 */
	$( document ).ready(function() {
		ShortCodeGen.init();
		
	});
	
	/**
	 * fires on resizeing of the window
	 */
	jQuery( window ).resize( function() {
		
	});
	
} )( jQuery );