import 'jquery.terminal/js/jquery.terminal.js';
require( 'jquery.terminal/css/jquery.terminal.css' );

/**
 * MANUAL
 *=======
 * https://www.npmjs.com/package/jquery.terminal
 * https://terminal.jcubic.pl/
 * https://stackoverflow.com/questions/45975717/why-does-this-jqueryterminal-cmd-function-not-work
 */
$( function()
{
    $( '#btnTestJqueryTerminal' ).on( 'click', function( e )
    {
        $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
        
        $( '#projectInstallContainer' ).terminal( '/projects/third-party-install', {login: true} );
        
        /*
        $( '#projectInstallContainer' ).cmd({
            prompt: '> ',
            width: '100%',
            keymap: function( command ) {
            },
            commands: function( command ) {
                console.log( command );
            }
        });
        */
    });
    
    
});
