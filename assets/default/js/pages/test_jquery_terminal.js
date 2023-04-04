import 'jquery.terminal/js/jquery.terminal.js';
require( 'jquery.terminal/css/jquery.terminal.css' );

/**
 * MANUAL
 *=======
 * https://www.npmjs.com/package/jquery.terminal
 * https://terminal.jcubic.pl/
 * https://stackoverflow.com/questions/45975717/why-does-this-jqueryterminal-cmd-function-not-work
 */

function testGreetings()
{
    $( '#projectInstallContainer' ).terminal( function( command )
    {
        if ( command !== '' ) {
            var result = window.eval( command );
            if ( result != undefined ) {
                this.echo( String( result ) );
            }
        }
    }, {
        greetings: 'Javascript Interpreter',
        name: 'js_demo',
        height: 200,
        width: 450,
        prompt: 'js> '
    });
}

function testLogin()
{
    //$( '#projectInstallContainer' ).terminal( 'service.php', {login: true} );
    $( '#projectInstallContainer' ).terminal( '/projects/third-party-install', {login: true} );
}

function testCmd()
{
    $( '#projectInstallContainer' ).cmd({
        prompt: '> ',
        width: '100%',
        keymap: function( command ) {
        },
        commands: function( command ) {
            console.log( command );
        }
    });
}

$( function()
{
    $( '#btnTestJqueryTerminal' ).on( 'click', function( e )
    {
        $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
        
        testGreetings();
        //testLogin();
        //testCmd();
    });
    
    
});
