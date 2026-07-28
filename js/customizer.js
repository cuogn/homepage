/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );

	// ─── FOOTER PREVIEW ───

	// Slogan (3 ngôn ngữ → cùng 1 element)
	wp.customize( 'footer_slogan_vi', function( value ) {
		value.bind( function( to ) {
			$( '.footer-slogan' ).text( to );
		} );
	} );
	wp.customize( 'footer_slogan_en', function( value ) {
		value.bind( function( to ) {
			$( '.footer-slogan' ).text( to );
		} );
	} );
	wp.customize( 'footer_slogan_ja', function( value ) {
		value.bind( function( to ) {
			$( '.footer-slogan' ).text( to );
		} );
	} );

	// Bản quyền
	wp.customize( 'footer_copyright_vi', function( value ) {
		value.bind( function( to ) {
			var year = new Date().getFullYear();
			$( '.site-info p' ).html( to.replace( '{year}', year ) );
		} );
	} );
	wp.customize( 'footer_copyright_en', function( value ) {
		value.bind( function( to ) {
			var year = new Date().getFullYear();
			$( '.site-info p' ).html( to.replace( '{year}', year ) );
		} );
	} );
	wp.customize( 'footer_copyright_ja', function( value ) {
		value.bind( function( to ) {
			var year = new Date().getFullYear();
			$( '.site-info p' ).html( to.replace( '{year}', year ) );
		} );
	} );

	// Logo - refresh để cập nhật ảnh
	wp.customize( 'footer_logo', function( value ) {
		value.bind( function( to ) {
			$( '.footer-logo img' ).attr( 'src', to );
		} );
	} );

	// Hiển/ẩn socials
	wp.customize( 'footer_show_socials', function( value ) {
		value.bind( function( to ) {
			$( '.footer-socials' ).toggle( !! to );
		} );
	} );
}( jQuery ) );
