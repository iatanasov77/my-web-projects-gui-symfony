function loadHostOptions( hostType )
{
	var url	= $( '#optionsContainer' ).attr( 'data-url' );
	var data = {};
	data['hostType'] = hostType;
		
	$.ajax({
		type: "GET",
	 	url: url,
	 	data: data,
		success: function( response )
		{
			$( '#optionsContainer' ).html( response );
		},
		error: function()
		{
			alert( "SYSTEM ERROR!!!" );
		}
	});
}

$(function()
{
	$( '.btnCreateVirtualHost' ).on( 'click', function () {
		$( "#host-create-modal" ).modal( 'show' );
	});
	
	$( '.btnUpdateVirtualHost' ).on( 'click', function () {
		var url	= $( this ).attr( 'data-url' );

		$.ajax({
			type: "GET",
		 	url: url,
			success: function( response )
			{
				$( '#hostFormContainer' ).html( response );
				$( "#host-create-modal" ).modal( 'show' );
			},
			error: function()
			{
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});
	
	$( '#host-change-php-version-modal' ).on( 'shown.bs.modal', function ( event ) {
		var url	= $( event.relatedTarget ).attr( 'data-url' );
	    $( '#host-change-php-version-form').attr( 'action', url );
	});
	
	$( '.btnDeleteHost' ).on( 'click', function( e )
	{
		e.preventDefault();
	    
	    $( "<div>Do you want to delete this Host?</div>" ).dialog({
	        buttons: {
	            "Ok": function () {
	                var url	= $( this ).attr( 'data-url' );
	                
					$.ajax({
						type: "GET",
					 	url: url,
						success: function( response )
						{
							window.location.reload();
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
	
	// HostType Options
	loadHostOptions( $( '#project_host_hostType' ).val() );
	$( '#project_host_hostType' ).on( 'change', function ()
	{
		loadHostOptions( $( this ).val() );
	});
});
