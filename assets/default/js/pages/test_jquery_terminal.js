import 'jquery.terminal/js/jquery.terminal.js';
require( 'jquery.terminal/css/jquery.terminal.css' );

/**
 * MANUAL
 *=======
 * https://www.npmjs.com/package/jquery.terminal
 * https://terminal.jcubic.pl/
 * https://stackoverflow.com/questions/45975717/why-does-this-jqueryterminal-cmd-function-not-work
 *
 * https://leash.jcubic.pl/
 * https://github.com/jcubic/leash/
 */

function testGreetings()
{
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
    
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
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
    
    //$( '#projectInstallContainer' ).terminal( 'service.php', {login: true} );
    $( '#projectInstallContainer' ).terminal( '/projects/third-party-install', {login: true} );
}

function testCmd()
{
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
    
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

/*
 * AJAX EXAMPLES
 *==============
 * https://terminal.jcubic.pl/examples.php#simple_ajax
 */
function testAjax()
{
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
    
    $( '#projectInstallContainer' ).terminal( function( command, term ) {
        var finish  = false;
        var msg     = "Wait I'm executing ajax call";
        
        term.set_prompt( '> ' );
        typed_message( term, msg, 200, function() {
            finish = true;
        });
            
        $.post( '/projects/test-terminal' ).then( function( response ) {
            console.log( response );
            //term.echo( response ).resume();
            //term.echo( response );
            
            ( function wait() {
                if ( finish ) {
                    term.echo( response );
                } else {
                    setTimeout( wait, 500 );
                }
            })();
            
        });
    }, {
        greetings: null,
    });
}

function testPrompt()
{
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );

    $( '#projectInstallContainer' ).terminal( function( cmd, term ) {
        var finish = false;
        var msg = "Wait I'm executing ajax call";
        term.set_prompt( '> ' );
        typed_message( term, msg, 200, function() {
            finish = true;
        });
        var args = {command: cmd};
        
        
        term.pause();
        $.get( '/projects/test-terminal', args, function( result ) {
            ( function wait() {
                if ( finish ) {
                    term.echo( result );
                } else {
                    setTimeout( wait, 500 );
                }
            })();
        });
        
        
    }, {
        name: 'xxx',
        greetings: null,
        width: 500,
        height: 300,
        onInit: function( term ) {
            // first question
            var msg = "Wellcome to my terminal";
            typed_message( term, msg, 200, function() {
                typed_prompt( term, "what's your name:", 100 );
            });
        },
        keydown: function(e) {
            //disable keyboard when animating
            if ( anim ) {
                return false;
            }
        }
    });
}

$( function()
{
    $( '#btnTestJqueryTerminal' ).on( 'click', function( e )
    {
        //testGreetings();
        //testLogin();
        //testCmd();
        testAjax();
        //testPrompt();
    });

});





var anim = false;
    
function typed( finish_typing ) {
    return function( term, message, delay, finish ) {
        anim = true;
        var prompt = term.get_prompt();
        var c = 0;
        if ( message.length > 0 ) {
            term.set_prompt( '' );
            var new_prompt = '';
            var interval = setInterval( function() {
                var chr = $.terminal.substring( message, c, c + 1 );
                new_prompt += chr;
                term.set_prompt( new_prompt );
                c++;
                if ( c == length( message ) ) {
                    clearInterval( interval );
                    // execute in next interval
                    setTimeout( function() {
                        // swap command with prompt
                        finish_typing( term, message, prompt );
                        anim = false
                        finish && finish();
                    }, delay);
                }
            }, delay);
        }
    };
}

function length( string ) {
    string = $.terminal.strip( string );
    return $( '<span>' + string + '</span>' ).text().length;
}

var typed_prompt = typed( function( term, message, prompt ) {
    term.set_prompt( message + ' ' );
});

var typed_message = typed( function( term, message, prompt ) {
    term.echo( message )
    term.set_prompt( prompt );
});
    
    
