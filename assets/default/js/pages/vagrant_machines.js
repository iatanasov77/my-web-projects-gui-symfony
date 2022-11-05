// JqueryUi conflicts with JqueryEasyUi
require( 'jquery-ui-dist/jquery-ui.js' );
require( 'jquery-ui-dist/jquery-ui.css' );
require( 'jquery-ui-dist/jquery-ui.theme.css' );

import * as spinnerService from '../includes/spinner';

$(function()
{
    $( '#create-vagrant-machine-modal' ).on( 'hidden.bs.modal', function ()
    {
        $( '#errorMessage .card-body' ).html( '' );
        $( '#errorMessage' ).hide();
        $( '#formContainer' ).html( '' );
    });
    
    $( '.btnCreateVagrantMachine' ).on( 'click', function () {
        $( '#formContainer' ).html( spinnerService.spinner );
        
        var url = $( this ).attr( 'data-url' );
        $.ajax({
            type: "GET",
            url: url,
            success: function( response )
            {
                $( "#create-vagrant-machine-modal" ).modal( 'show' );
                $( '#formContainer' ).html( response );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '#create-vagrant-machine-modal' ).on( 'click', '#btnSaveForm', function( e )
    {
        spinnerService.showSpinner( '#formContainer' );
        
        var form    = $( '#formMachine' );
        
        $.ajax({
            type: "POST",
            url: form.attr( 'action' ),
            data: form.serialize(),
            dataType: 'json',
            success: function( response )
            {
                spinnerService.hideSpinner( '#formContainer' );
                
                form[0].reset();
                if ( response.status == 'error' ) {
                
                } else {
                    document.location   = document.location;
                }
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '.btnDeleteVagrantMachine' ).on( 'click', function( e )
    {
        e.preventDefault();
        var deleteUrl = $( this ).attr( 'data-url' );
        
        $( "<div>Do you want to delete this Host?</div>" ).dialog({
            buttons: {
                "Ok": function () {

                    $.ajax({
                        type: "GET",
                        url: deleteUrl,
                        success: function( response )
                        {
                            //window.location.reload();
                        },
                        error: function()
                        {
                            alert( "SYSTEM ERROR!!!" );
                        }
                    });
                },
                "Cancel": function () {
                    $( this ).dialog( "close" );
                }
            }
        });
    });
});
