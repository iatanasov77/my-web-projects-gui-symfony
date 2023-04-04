import * as spinnerService from '../includes/spinner';

function createProject( form )
{
    spinnerService.showSpinner( '#formProjectThirdPartyContainer' );
    
    $.ajax({
        type: "POST",
        url: form.attr( 'action' ),
        data: form.serialize(),
        dataType: 'json',
        success: function( response )
        {
            spinnerService.hideSpinner( '#formProjectThirdPartyContainer' );
            
            form[0].reset();
            if ( response.status == 'error' ) {
                
                if ( response.errType == 'alert' ) {
                    alert( response.data );
                    return;
                }
                
                var ul = $( '<ul>' );
                $.each( response.errors, function( key, value ) {
                    ul.append(
                        $( document.createElement( 'li' ) ).text( key + ': ' + value )
                    );
                });
                
                $( '#errorMessage > div.card-body' ).html( ul );
                $( '#errorMessage' ).show();
            } else {
                $( '#create-project-third-party-modal' ).modal( 'toggle' );
                
                $( '#submitMessage > div.card-body' ).html( 'Successfuly adding project.' );
                $( '#submitMessage' ).show();
                $( '#sectionProjects' ).html( response.data );
            }
        },
        error: function(  )
        {
            spinnerService.hideSpinner( '#formProjectThirdPartyContainer' );
            alert( "SYSTEM ERROR!!!" );
        }
   });
}

function installProject( form )
{
    $( '#formProjectThirdPartyContainer' ).html( '<div class="console" id="consoleProjectInstall"><div id="projectInstallContainer"></div></div>' );
    
    var lastResponseLength  = false;
    $.ajax({
        method: 'POST',
        url: form.attr( 'action' ),
        data: form.serializeArray(),
        xhrFields: {
            // Getting on progress streaming response
            onprogress: function( e )
            {
                var progressResponse;
                var response    = e.currentTarget.response;
                if( lastResponseLength === false ) {
                    //$( "#commandPhpVersion" ).html( response );
                    
                    progressResponse    = response;
                    lastResponseLength  = response.length;
                } else {
                    progressResponse    = response.substring( lastResponseLength );
                    lastResponseLength  = response.length;
                }
                
                // In My Case
                $( "#projectInstallContainer" ).append( progressResponse ).animate( {scrollTop: $( '#projectInstallContainer' ).prop( "scrollHeight" ) }, 0 );
                
                if ( progressResponse == '' ) {
                    
                }
            }
        }
    })
    .done( function( html ) {
        /*
        var setupUrl     = "/php-versions/" + data.version + "/setup";
        $.get( setupUrl, function( response ) {
            $( "#projectInstallContainer" ).append( '<br><br>PhpFpm Setup is Done!<br>' ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
            
        });
        */
        
        $( "#btnClosePhpInstall" ).text( 'Close' );
        $( "#btnClosePhpInstall" ).show();
    })
    .fail( function() {
        alert( "AJAX return an ERROR !!!" );
    });
}

$( function()
{
    $( '#btnInstallThirdPartyProject' ).on( 'click', function( e )
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
       
    $( '#create-project-third-party-modal' ).on( 'click', '#btnInstallProjectThirdParty', function( e )
    {
        var form            = $( '#formProjectThirdParty' );
        var predefinedType  = $( '#third_party_project_predefinedType' ).val();
        
        switch ( predefinedType ) {
            case 'presta_shop':
                installProject( form );
                break;
            case 'symfony':
                createProject( form );
                break;
            case 'laravel':
                createProject( form );
                break;
            case 'sylius':
                createProject( form );
                break;
            case 'magento':
                createProject( form );
                break;
            case 'django':
                createProject( form );
                break;
            default:
                alert( 'UNKNOWN PROJECT TYPE !!!' )
        }
    });

});
