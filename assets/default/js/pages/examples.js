require( 'glyphicons' );
require( '../../css/glyphicons.css' );
require( 'bootstrap-sass' );
require( 'bootstrap-gtreetable/dist/bootstrap-gtreetable.css' );
require( 'bootstrap-gtreetable/dist/bootstrap-gtreetable.js' );

require( '../../css/multi-select.css' );
require( 'jquery-multi-select/src/jquery.multi-select.js' );

//https://iamceege.github.io/tooltipster/
require( 'tooltipster/dist/css/tooltipster.bundle.css' );
require( 'tooltipster/dist/js/tooltipster.bundle.js' );


$( function()
{
	/**
	 *********************************
	 * 								 *
	 *  GTreeTable	Custom Actions	 *
	 * 								 *
	 *********************************
	 */
	$( '#tableVersionsAvailable' ).gtreetable({
		'source': function ( id ) {
			return {
				type: 'GET',
				url: $( '#tableVersionsAvailable' ).attr( 'data-url' ),
				data: { 'version': id },
				dataType: 'json',
				error: function( XMLHttpRequest ) {
					alert( 'GTreeTable ERROR !!!' );
					alert( XMLHttpRequest.status + ': ' + XMLHttpRequest.responseText );
				}
  	      	}
  	    },
  	    'nodeIndent': 32,
  	    'showExpandIconOnEmpty': false,
  	    'defaultActions': null,
  	    'actions': [
  	    	{
				name: 'Install',
				event: function ( oNode, oManager ) {
					if ( oNode.level == 1 ) {
						alert( 'Cannot Install parent minors!' );
						return false;
					}
					
					if ( oNode.type == 'installed' ) {
						alert( 'This version is already installed!' );
						return false;
					}
					
					// http://page_url#notarget
					//alert( window.location.href.split( '#' )[0] );
					
					//console.log( oNode.id );
					installPhpVersion( oNode.id );
				},
  	    	}
	    ],
  	});


	/**
	 *********************************
	 * 								 *
	 *  MultiSelect With Presets	 *
	 * 								 *
	 *********************************
	 */
	$( '#phpbrewVariants' ).multiSelect({
		noneText: '-- Select Variants --',
		// EXAMPLE PRESETS
		/*
        presets: [
            {
                name: 'All categories',
                options: []
            },
            {
                name: 'My categories',
                options: ['a', 'c']
            }
        ]
        */
    });
	
	
	/**
	 *****************************************
	 * 										 *
	 *  STREAMED RESPONSE WITH JQUERY AJAX	 *
	 * 										 *
	 *****************************************
	 */
	$( '#formPhpInstall' ).on( 'submit', function()
	{
		var url		= $( "#install-php-version-modal" ).attr( 'data-actionUrl' );
		var data 	= $( this ).serializeArray();
		
		$( "#lablePhpVersion" ).text( 'Install PHP: ' + data[0].value );
		
		$( "#formPhpInstall" ).hide( 1000 );
		$( "#consolePhpInstall" ).show();
		$( "#btnClosePhpInstall" ).show();
		
		var lastResponseLength	= false;
		$.ajax({
			method: 'POST',
			url: url,
			data: data,
			xhrFields: {
	            // Getting on progress streaming response
	            onprogress: function(e)
	            {
	                var progressResponse;
	                var response	= e.currentTarget.response;
	                if( lastResponseLength === false) {
	                	$( "#commandPhpVersion" ).html( response );
	                	
	                    progressResponse	= response;
	                    lastResponseLength	= response.length;
	                } else {
	                    progressResponse	= response.substring(lastResponseLength);
	                    lastResponseLength	= response.length;
	                }
	                
	                // In My Case
	                $( "#phpInstallContainer" ).append( progressResponse ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
	                
	                if ( progressResponse == '' ) {
	                	
	                }
	                
	                
	            /* When rendering progress bar
	                var parsedResponse = JSON.parse(progressResponse);
	                $('#progressTest').text(progressResponse);
	                $('#fullResponse').text(parsedResponse.message);
	                $('.progress-bar').css('width', parsedResponse.progress + '%');
	            */
	            }
	        }
		})
		.done( function( html ) {
			
		})
		.fail( function() {
			alert( "AJAX return an ERROR !!!" );
		});
		
		return false;
	});
	
});
