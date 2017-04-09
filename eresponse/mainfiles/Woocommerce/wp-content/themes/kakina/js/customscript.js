// menu dropdown link clickable
jQuery( document ).ready( function ( $ ) {
    $( '.navbar .dropdown > a, .dropdown-menu > li > a, .navbar .dropdown-submenu > a, .widget-menu .dropdown > a, .widget-menu .dropdown-submenu > a' ).click( function () {
        location.href = this.href;
    } );
} );

// scroll to top button
jQuery( document ).ready( function ( $ ) {
    $( "#back-top" ).hide();
    $( function () {
        $( window ).scroll( function () {
            if ( $( this ).scrollTop() > 100 ) {
                $( '#back-top' ).fadeIn();
            } else {
                $( '#back-top' ).fadeOut();
            }
        } );

        // scroll body to 0px on click
        $( '#back-top a' ).click( function () {
            $( 'body,html' ).animate( {
                scrollTop: 0
            }, 800 );
            return false;
        } );
    } );
} );
// Tooltip
jQuery( document ).ready( function ( $ ) {
    $( function () {
        $( '[data-toggle="tooltip"]' ).tooltip()
    } )
} );
// Tooltip to compare
jQuery( document ).ready( function ( $ ) {
    $( ".compare.button" ).attr( 'data-toggle', 'tooltip' );
    $( ".compare.button" ).attr( 'title', objectL10n.compare );
} );
// Popover
jQuery( document ).ready( function ( $ ) {
    $( function () {
        $( '[data-toggle="popover"]' ).popover( { html: true } )
    } )
} );
// Wishlist count ajax update
jQuery( document ).ready( function ( $ ) {
    $( 'body' ).on( 'added_to_wishlist', function () {
        $( '.top-wishlist .count' ).load( yith_wcwl_plugin_ajax_web_url + ' .top-wishlist span', { action: 'yith_wcwl_update_single_product_list' } );
    } );
} );

// FlexSlider homepage
jQuery( document ).ready( function ( $ ) {
    $( '.homepage-slider' ).flexslider( {
        animation: "slide",
        itemWidth: 855,
        controlNav: false,
        animationLoop: false,
        slideshow: true,
    } );
} );

// Shop by category menu
jQuery( document ).ready( function ( $ ) {
    function mobileViewUpdate() {
        var viewportWidth = $( window ).width();
        if ( viewportWidth < 991 ) {
            $( '#collapseOne.opened' ).removeClass( 'in' );
            $( '#collapseOne.opened' ).removeClass( 'mobile-display' );
        } else {
            $( '#collapseOne.opened' ).addClass( 'in' );
            $( '#collapseOne.opened' ).removeClass( 'mobile-display' );
        }
    }
    ;
    $( window ).load( mobileViewUpdate );
    // $( window ).resize( mobileViewUpdate );
} );

// Add to cart button
jQuery( document ).ready( function ( $ ) {
    var width = $( '.woocommerce ul.products li.product' ).innerWidth();
    if ( width < 180 ) {
        $( '.woocommerce ul.products li.product .button' ).addClass( 'shopping-button' );
    } else {
        $( '.woocommerce ul.products li.product .button' ).removeClass( 'shopping-button' );
    }
} );


