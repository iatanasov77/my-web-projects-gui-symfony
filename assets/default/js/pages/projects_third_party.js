import * as spinnerService from '../includes/spinner';

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
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
       
    $( '#create-project-third-party-modal' ).on( 'click', '#btnSaveProjectThirdParty', function( e )
    {
        spinnerService.showSpinner( '#formProjectThirdPartyContainer' );
        
        var form    = $( '#formProjectThirdParty' );
        
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
    });

});
