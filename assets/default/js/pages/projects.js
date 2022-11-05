import * as spinnerService from '../includes/spinner';

$(function()
{
	$( '#formProjectContainer' ).on( 'change', '#project_predefinedType', function( e )
	{
		var type	= $( this ).val();
		if ( predefinedProjects[type] == undefined ) {
			alert( "Project type '" + type + "' is undefined!" );
		}
		
// 		$( '#project_sourceType' ).val( predefinedProjects[type].sourceType );
// 		$( '#project_repository' ).val( predefinedProjects[type].sourceUrl );
// 		$( '#project_branch' ).val( predefinedProjects[type].branch );
		
		$.ajax({
			type: "GET",
		 	url: '/predefined_project_form/' + type,
			success: function( response )
			{
				$( '#predefinedProjectForm' ).html( response );
			},
			error: function()
			{
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});
	
	// Init Delete Form
	$( '#sectionProjects' ).on( 'click', '.btnDelete', function( e )
	{
		$( '#project_delete_projectId' ).val( $( this ).attr( 'data-projectId' ) );
	});

    $( '#create-project-modal' ).on( 'hidden.bs.modal', function ()
    {
    	$( '#errorMessage .card-body' ).html( '' );
    	$( '#errorMessage' ).hide();
        $( '#formProjectContainer' ).html( '' );
    });
    
    $( '#project-install-modal' ).on( 'hidden.bs.modal', function ()
    {
        $( '#phpInstallContainer' ).html( '' );
        window.location.reload();
    });

    $( '#btnCreateProject' ).on( 'click', function( e )
    {
        $( '#formProjectContainer' ).html( spinnerService.spinner );
        
        $.ajax({
            type: "GET",
            url: "/projects/edit/0",
            success: function( response )
            {
                $( '#formProjectContainer' ).html( response );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '#btnCreateCategory' ).on( 'click', function( e )
    {
        $( '#formCategoryContainer' ).html( spinnerService.spinner );
        
        $.ajax({
            type: "GET",
            url: "/categories/edit/0",
            success: function( response )
            {
                $( '#formCategoryContainer' ).html( response );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
	$( '#sectionProjects' ).on( 'click', '.btnEdit', function( e )
	{
        $( '#formProjectContainer' ).html( spinnerService.spinner );
        
		$.ajax({
			type: "GET",
		 	url: "/projects/edit/" + $( this ).attr( 'data-projectId' ),
			success: function( response )
			{
				$( '#formProjectContainer' ).html( response );
			},
			error: function()
			{
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});

	$( '#sectionProjects' ).on( 'click', '.btnInstall', function( e )
	{
		var url	= "/projects/install/" + $( this ).attr( 'data-projectId' );
		
		$( '#projectName' ).text( $( this ).attr( 'data-projectName' ) );
		$( '#project-install-modal' ).modal( 'toggle' );
		
		var lastResponseLength	= false;
		$.ajax({
			method: 'POST',
			url: url,
			//data: data,
			xhrFields: {
	            // Getting on progress streaming response
	            onprogress: function( e )
	            {
	                var progressResponse;
	                var response	= e.currentTarget.response;
	                
	                if( lastResponseLength === false ) {
	                    progressResponse	= response;
	                } else {
	                    progressResponse	= response.substring( lastResponseLength );
	                }
	                
	                lastResponseLength	= response.length;
	                
	                // In My Case
	                $( "#phpInstallContainer" ).append( progressResponse ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
	                
	                if ( progressResponse == '' ) {
	                	
	                }
	            }
	        }
		})
		.done( function( html ) {
			//alert( "DONE !!!" );
		})
		.fail( function() {
			alert( "AJAX return an ERROR !!!" );
		});
	});
	
	$( '#sectionProjects' ).on( 'click', '.btnInstallManual', function( e )
	{
		var spinner   = '<div class="spinner-border" role="status"  id="projectSpinner"><span class="sr-only">Loading...</span></div>';
		$( this ).before( spinner );
		
		$( '#btnEditInstallManual' ).attr( 'data-projectId', $( this ).attr( 'data-projectId' ) );
		
		$.ajax({
			type: "GET",
		 	url: "/projects/install_manual/" + $( this ).attr( 'data-projectId' ),
			success: function( response )
			{
				$( "#projectSpinner" ).remove();
				
				$( '#installManual > div.card-body' ).html( response );
				$( '#install-manual-modal' ).modal( 'toggle' );
				$( '#btnEditInstallManual').show();
			},
			error: function()
			{
				$( "#projectSpinner" ).remove();
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});
	
	$( '#install-manual-modal' ).on( 'click', '#btnEditInstallManual', function( e )
	{
		var spinner   = '<div class="spinner-border" role="status"  id="projectSpinner"><span class="sr-only">Loading...</span></div>';
		$( this ).before( spinner );
		
		$.ajax({
			type: "GET",
		 	url: "/projects/edit_install_manual/" + $( this ).attr( 'data-projectId' ),
			success: function( response )
			{
				$( "#projectSpinner" ).remove();
				$( '#btnEditInstallManual').hide();
				
				$( '#installManual > div.card-body' ).html( response );
				//$( '#install-manual-modal' ).modal( 'toggle' );
			},
			error: function()
			{
				$( "#projectSpinner" ).remove();
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});

	// Submit Delete Form
	$( '#btnDeleteProject' ).on( 'click', function( e )
	{
		e.preventDefault();
	    
	    $( "<div>Do you want to delete this Project?</div>" ).dialog({
	        buttons: {
	            "Ok": function () {
	                var form	= $( '#formDeleteProject' );
					$.ajax({
						type: "POST",
					 	url: form.attr( 'action' ),
					 	data: form.serialize(),
					 	dataType: 'json',
						success: function( response )
						{
							form[0].reset();
							if ( response.status == 'error' ) {
								alert( "FORM ERROR!!!" );
			
							} else {
								$( '#delete-project-modal' ).modal( 'toggle' );
								
								$( '#submitMessage > div.card-body' ).html( 'Successfuly deleting project.' );
								$( '#submitMessage' ).show();
								$( '#sectionProjects' ).html( response.data );
							}
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

	$( '#create-project-modal' ).on( 'click', '#btnSaveProject', function( e )
	{
		spinnerService.showSpinner( '#formProjectContainer' );
		
		var form	= $( '#formProject' );
		//alert( form.attr( 'action' ) ); return;
		$.ajax({
			type: "POST",
		 	url: form.attr( 'action' ),
		 	data: form.serialize(),
		 	dataType: 'json',
			success: function( response )
			{
				spinnerService.hideSpinner( '#formProjectContainer' );
				
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
					$( '#create-project-modal' ).modal( 'toggle' );
					
					$( '#submitMessage > div.card-body' ).html( 'Successfuly adding project.' );
					$( '#submitMessage' ).show();
					$( '#sectionProjects' ).html( response.data );
				}
			},
			error: function(  )
			{
				spinnerService.hideSpinner( '#formProjectContainer' );
				alert( "SYSTEM ERROR!!!" );
			}
       });
	});
	
	$( '#create-category-modal' ).on( 'click', '#btnSaveCategory', function( e )
	{
		
		var form	= $( '#formCategory' );
		alert( form.attr( 'action' ) );
		$.ajax({
			type: "POST",
		 	url: form.attr( 'action' ),
		 	data: form.serialize(),
		 	dataType: 'json',
			success: function( response )
			{
				form[0].reset();
				if ( response.status == 'error' ) {
					var ul = $( '<ul>' );
					$.each( response.errors, function( key, value ) {
						ul.append(
    				        $( document.createElement( 'li' ) ).text( key + ': ' + value )
    				    );
					});
					
					$( '#errorMessage > div.card-body' ).html( ul );
					$( '#errorMessage' ).show();
				} else {
					$( '#create-project-modal' ).modal( 'toggle' );
					
					$( '#submitMessage > div.card-body' ).html( 'Successfuly create category.' );
					$( '#submitMessage' ).show();
					$( '#sectionProjects' ).html( response.data );
				}
			},
			error: function()
			{
				alert( "SYSTEM ERROR!!!" );
			}
       });
	});
	
	$( '#create-project-modal' ).on( 'click', '.btnDeleteHost', function( e )
	{
		var $btn 	= $( this );
		var host	= $( this ).attr( 'data-host' );
		var url		= '/hosts/' + host + '/delete';
		
		spinnerService.showSpinner( '#formProjectContainer' );
		$.ajax({
			type: "GET",
		 	url: url,
			success: function( response )
			{
				spinnerService.hideSpinner( '#formProjectContainer' );
				$btn.closest( 'li' ).remove();
				//window.location.reload();
			},
			error: function()
			{
				spinnerService.hideSpinner( '#formProjectContainer' );
				alert( "SYSTEM ERROR!!!" );
			}
		});
	});
});

