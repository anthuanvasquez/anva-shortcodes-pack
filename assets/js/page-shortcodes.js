jQuery( document ).ready( function( $ ) {

    'use strict';

    var AnvaShortcodes = {

        init: function() {
            var shortcodeEle = $( '#shortcodes-items li' ).first();
            AnvaShortcodes.showHide();
            AnvaShortcodes.set( shortcodeEle );
            $( '#shortcodes-items li' ).click( function() {
                AnvaShortcodes.set( $( this ) );
            });
        },

        set: function( target ) {
            var shortcodeEle = $( '#anva-shcg-section-' + target.data( 'value' ) ),
                shortcodeSec = $( '.anva-shcg-section' );
            shortcodeSec.removeClass( 'active' );
            shortcodeEle.addClass( 'active' );
            shortcodeEle.find( 'button' ).trigger( 'click' );
        },

        showHide: function() {
            $( '.button-shortcode' ).click( function() {
                var shortcodeEle  = $( this ).data( 'id' ),
                    shortcodeGen  = '',
                    shortcodeAttr = $( '#anva-shcg-attribute-group-' + shortcodeEle + ' .anva-shcg-attribute' ),
                    shortcodeCont = $( '#anva-shcg-content-' + shortcodeEle );

                // Init shortcode
                shortcodeGen += '[' + shortcodeEle;

                if ( shortcodeAttr.length > 0 ) {
                    shortcodeAttr.each( function() {
                        shortcodeGen += ' ' + $( this ).data( 'attr' ) + '="' + $( this ).val() + '"';
                    });
                }

                // End shortcode
                shortcodeGen += ']';

                if ( shortcodeCont.length > 0 ) {
                    shortcodeGen += shortcodeCont.val() + '[/' + shortcodeEle + ']';
                    shortcodeGen += '\n';

                    var shortcodeRepeat = $( '#anva-shcg-content-repeat-' + shortcodeEle ).val();

                    for ( var count = 1; count <= shortcodeRepeat; count = count + 1 ) {
                        if ( count < shortcodeRepeat ) {
                            shortcodeGen += '[' + shortcodeEle + ']';
                            shortcodeGen += shortcodeCont.val() + '[/' + shortcodeEle + ']';
                            shortcodeGen += '<br />\n';
                        } else {
                            shortcodeGen += '[' + shortcodeEle + '_last]';
                            shortcodeGen += shortcodeCont.val() + '[/' + shortcodeEle + '_last]';
                            shortcodeGen += '<br />\n';
                        }
                    }
                }
                $( '#anva-shcg-code-' + shortcodeEle ).html( shortcodeGen );

                return false;
            });
        }
    };

    AnvaShortcodes.init();
});
