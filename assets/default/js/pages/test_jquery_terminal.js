import * as spinnerService from '../includes/spinner';

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
function testAjax( form )
{
    var service = form.attr( 'action' );
    var data    = form.serializeArray();
    
    $( '#projectInstallContainer' ).terminal( function( command, term ) {
        term.pause();
        $.post( service , data ).then( function( response ) {
            term.echo( response ).resume();
        });
    }, {
        greetings: 'Simple php example'
    });
}

$( function()
{
    /*
    $( '#btnTestJqueryTerminal' ).on( 'click', function( e )
    {
        testGreetings();
        //testLogin();
        //testCmd();
    });
    */
    
    $( '#btnTestJqueryTerminal' ).on( 'click', function( e )
    {
        $( '#formProjectThirdPartyContainer' ).html( spinnerService.spinner );
        
        $.ajax({
            type: "GET",
            url: "/third-party-projects/edit/0",
            success: function( response )
            {
                $( '#formProjectThirdPartyContainer' ).html( response );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '#create-project-third-party-modal' ).on( 'click', '#btnInstallProjectThirdParty', function( e )
    {
        var form            = $( '#formProjectThirdParty' );
        var predefinedType  = $( '#third_party_project_predefinedType' ).val();
        
        testAjax( form );
    });
    
    
    
    
    
    
    
    
    
    $( '#formProjectThirdPartyContainer' ).on( 'change', '#third_party_project_predefinedType', function( e )
    {
        var type    = $( this ).val();
        if ( predefinedProjects[type] == undefined ) {
            alert( "Project type '" + type + "' is undefined!" );
        }
        
//      $( '#project_sourceType' ).val( predefinedProjects[type].sourceType );
//      $( '#project_repository' ).val( predefinedProjects[type].sourceUrl );
//      $( '#project_branch' ).val( predefinedProjects[type].branch );
        
        $.ajax({
            type: "GET",
            url: '/predefined_project_form/' + type,
            success: function( response )
            {
                $( '#predefinedProjectProperties' ).html( response );
                
                if ( type == 'presta_shop' ) {
                    $( '#third_party_project_installType' ).val( 'composer' );
                    $( '#formProjectThirdParty' ).attr( 'action', '/projects/third-party-install' );
                }
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
});
