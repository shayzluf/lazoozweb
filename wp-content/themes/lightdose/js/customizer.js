/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {	
        
        wp.customize( 'light_dose_theme_options[font]', function( value ) {
		value.bind( function( newval  ) {
			$( 'body' ).text( newval  );
		} );
	} );
        
        // Logo.
        wp.customize( 'light_dose_theme_options[logo]', function( value ) {
		value.bind( function( newval  ) {
			$( '.logo' ).text( newval  );
		} );
	} );
        
        wp.customize( 'light_dose_theme_options[logo_text]', function( value ) {
		value.bind( function( newval  ) {
			$( '.logo' ).text( newval  );
		} );
	} );
        
        wp.customize( 'light_dose_theme_options[font_size]', function( value ) {	            
                value.bind('change', function( newval  ) {
                    $( '.font_size_display_result' ).text( newval  );	
                    console.log(newval);
		} );
	} );
        
        $('input[type="range"]').change(function() {
            console.log($(this).val());
        });
        
} )( jQuery );
