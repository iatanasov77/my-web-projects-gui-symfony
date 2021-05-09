var spinner = '<div id="project-spinner" style="text-align:center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>';

/**
 * onTopOf: ID of the element on top of display the spinner
 */
function showSpinner( onTopOf ) {
	$( onTopOf ).before( spinner );
	$( '#project-spinner' ).css( {'opacity': '0.8', 'position':'absolute', 'text-align':'center', 'top':'170px', 'left':'210px', 'z-index':'9'} );
	$( onTopOf ).css( {'pointer-events': 'none', 'position':'relative', 'width': '100%', 'height': '100%', 'opacity': '0.8'} );
}
function hideSpinner( onTopOf ) {
	$( onTopOf ).removeAttr( 'style' );
	$( '#project-spinner' ).remove();
}

$(function()
{
	// Init Delete Form
	$( '#sectionExtensions' ).on( 'click', '.btnDelete', function( e )
	{
		$( '#project_delete_projectId' ).val( $( this ).attr( 'data-projectId' ) );
	});

    $( '#phpbrew-extension-edit-modal' ).on( 'hidden.bs.modal', function ()
    {
    	$( '#errorMessage .card-body' ).html( '' );
    	$( '#errorMessage' ).hide();
        $( '#formPhpbrewExtensionContainer' ).html( '' );
    });
    
	$( '#sectionExtensions' ).on( 'click', '.btnEdit', function( e )
	{
        $( '#formPhpbrewExtensionContainer' ).html( spinner );
        
		$.ajax({
			type: "GET",
		 	url: "/phpbrew/extensions/edit/" + $( this ).attr( 'data-extension' ),
			success: function( response )
			{
				$( '#formPhpbrewExtensionContainer' ).html( response );
			},
			error: function()
			{
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});

	$( '#phpbrew-extension-edit-modal' ).on( 'click', '#btnSaveEtension', function( e )
	{
		showSpinner( '#formPhpbrewExtensionContainer' );
		
		var form	= $( '#formPhpbrewExtension' );
		
		$.ajax({
			type: "POST",
		 	url: form.attr( 'action' ),
		 	data: form.serialize(),
		 	dataType: 'json',
			success: function( response )
			{
				hideSpinner( '#formPhpbrewExtensionContainer' );
				
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
					$( '#phpbrew-extension-edit-modal' ).modal( 'toggle' );
					
					$( '#submitMessage > div.card-body' ).html( 'Successfuly saving phpbrew extension.' );
					$( '#submitMessage' ).show();
					$( '#sectionExtensions' ).html( response.data );
				}
			},
			error: function(  )
			{
				hideSpinner( '#formPhpbrewExtensionContainer' );
				alert( "SYSTEM ERROR!!!" );
			}
       });
	});
	
	
});
