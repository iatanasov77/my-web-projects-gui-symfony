require( 'glyphicons' );
require( '../../css/glyphicons.css' );
require( 'bootstrap-sass' );

require( '../../css/multi-select.css' );
require( 'jquery-multi-select/src/jquery.multi-select.js' );

//https://iamceege.github.io/tooltipster/
require( 'tooltipster/dist/css/tooltipster.bundle.css' );
require( 'tooltipster/dist/js/tooltipster.bundle.js' );

function installPhpVersion( version, defaultVariants )
{
	$( "#lablePhpVersion" ).text( version );
	$( "#inputPhpVersion" ).val( version );
	
	$( "#phpInstallContainer" ).html( '' );
	$( "#install-php-version-modal" ).modal( 'show' );
	
	// EVENTS
	$( "#install-php-version-modal" ).on( 'hidden.bs.modal', function () {
		window.location.reload();
	});
}

$( function()
{
	$( '[data-toggle="tooltip"]' ).tooltipster();
	
	$( '.btnInstallPhpVersion' ).on( 'click', function () {
		$( "#consolePhpInstall" ).hide();
		$( "#btnClosePhpInstall" ).hide();
		
		$( "#phpInstallContainer" ).html( '' );
		$( "#install-php-version-modal" ).modal( 'show' );
		
		// EVENTS
		$( "#install-php-version-modal" ).on( 'hidden.bs.modal', function () {
			window.location.reload();
		});
	});
	
	// https://www.jqueryscript.net/demo/jQuery-Plugin-For-Multiple-Select-With-Checkboxes-multi-select-js/
	// https://github.com/mysociety/jquery-multi-select
	$( '#phpbrewVariants' ).multiSelect({
		noneText: '-- Select Variants --',
    });
    
    $( '#phpExtensions' ).multiSelect({
		noneText: '-- Select Extensions --',
    });
	
	$( '#formPhpInstall' ).on( 'submit', function()
	{
		var url		= $( "#install-php-version-modal" ).attr( 'data-actionUrl' );
		var data 	= $( this ).serializeArray();
		
		$( "#lablePhpVersion" ).text( 'Install PHP: ' + data[0].value );
		
		$( "#formPhpInstall" ).hide( 1000 );
		$( "#consolePhpInstall" ).show();
		$( "#btnClosePhpInstall" ).show();
		
		//console.log( data );
		//return false;

		var lastResponseLength	= false;
		$.ajax({
			method: 'POST',
			url: url,
			data: data,
			xhrFields: {
	            // Getting on progress streaming response
	            onprogress: function( e )
	            {
	                var progressResponse;
	                var response	= e.currentTarget.response;
	                if( lastResponseLength === false ) {
	                	$( "#commandPhpVersion" ).html( response );
	                	
	                    progressResponse	= response;
	                    lastResponseLength	= response.length;
	                } else {
	                    progressResponse	= response.substring( lastResponseLength );
	                    lastResponseLength	= response.length;
	                }
	                
	                // In My Case
	                $( "#phpInstallContainer" ).append( progressResponse ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
	                
	                if ( progressResponse == '' ) {
	                	
	                }
	            }
	        }
		})
		.done( function( html ) {
			var setupUrl	= "/php-versions/" + data[0].value + "/setup";
			var startUrl	= "/php-versions/" + data[0].value + "/start-fpm";
			$.get( setupUrl, function( response ) {
				$( "#phpInstallContainer" ).append( '<br><br>PhpFpm Setup is Done!<br>' ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
				$.get( startUrl, function( response ) {
					$( "#phpInstallContainer" ).append( 'PhpFpm is Started!<br>' ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
				});
			});
		})
		.fail( function() {
			alert( "AJAX return an ERROR !!!" );
		});
		
		return false;
	});
	
	
});
