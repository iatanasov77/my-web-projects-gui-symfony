require( '../../css/multi-select.css' );
require( 'jquery-multi-select/src/jquery.multi-select.js' );

//https://iamceege.github.io/tooltipster/
require( 'tooltipster/dist/css/tooltipster.bundle.css' );
require( 'tooltipster/dist/js/tooltipster.bundle.js' );

import 'jquery.terminal/js/jquery.terminal.js';
require( 'jquery.terminal/css/jquery.terminal.css' );

function startInstall( url, serializedData )
{
    var data                = installData( serializedData );
    
    var lastResponseLength  = false;
    $.ajax({
        method: 'POST',
        url: url,
        data: serializedData,
        xhrFields: {
            // Getting on progress streaming response
            onprogress: function( e )
            {
                var progressResponse;
                var response    = e.currentTarget.response;
                if( lastResponseLength === false ) {
                    $( "#commandPhpVersion" ).html( response );
                    
                    progressResponse    = response;
                    lastResponseLength  = response.length;
                } else {
                    progressResponse    = response.substring( lastResponseLength );
                    lastResponseLength  = response.length;
                }
                
                // In My Case
                $( "#phpInstallContainer" ).append( progressResponse ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
                
                if ( progressResponse == '' ) {
                    
                }
            }
        }
    })
    .done( function( html ) {
        var setupUrl     = "/php-versions/" + data.version + "/setup";
        var startUrl     = "/php-versions/" + data.version + "/start-fpm";
        
        $.get( setupUrl, function( response ) {
            $( "#phpInstallContainer" ).append( '<br><br>PhpFpm Setup is Done!<br>' ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
            $.get( startUrl, function( response ) {
                $( "#phpInstallContainer" ).append( 'PhpFpm is Started!<br>' ).animate( {scrollTop: $( '#phpInstallContainer' ).prop( "scrollHeight" ) }, 0 );
            });
        });
        
        $( "#btnClosePhpInstall" ).text( 'Close' );
        $( "#btnClosePhpInstall" ).show();
    })
    .fail( function() {
        alert( "AJAX return an ERROR !!!" );
    });
}

function installPhpVersion( version, defaultVariants )
{
	$( "#lablePhpVersion" ).text( version );
	$( "#inputPhpVersion" ).val( version );
	
	$( "#phpInstallContainer" ).html( '' );
	
	
	/** Bootstrap 5 Modal Toggle */
    const myModal = new bootstrap.Modal( '#install-php-version-modal', {
        keyboard: false
    });
    myModal.show( $( '#install-php-version-modal' ).get( 0 ) );
            
    // EVENTS
	$( '#install-php-version-modal' ).get( 0 ).addEventListener( 'hidden.bs.modal', function ( event ) {
		window.location.reload();
	});
}

function installData( serializedForm )
{
    let data    = {
        'version': '',
        'phpBrewCustomName': '',
        'displayBuildOutput': 'True',
        'phpBrewVariants': [],
        'phpExtensions': [],
        
    };
    
    for ( var i = 0; i < serializedForm.length; i++ ) {
        switch ( serializedForm[i].name ) {
            case 'phpBrewVariants[]':
                data.phpBrewVariants.push( serializedForm[i].value );
                break;
            case 'phpExtensions[]':
                data.phpExtensions.push( serializedForm[i].value );
                break;
            default:
                data[serializedForm[i].name]    = serializedForm[i].value;
        }
    }
    
    return data;
}

$( function()
{
	//$( '[data-toggle="tooltip"]' ).tooltipster();
	
	$( '#btnRunPhpInstall' ).on( 'click', function () {
		$( "#btnClosePhpInstall" ).hide();
		$( '#formPhpInstall' ).submit();
		$( this ).hide();
	});
	
	$( '.btnInstallPhpVersion' ).on( 'click', function () {
		$( "#consolePhpInstall" ).hide();
		
		$( "#phpInstallContainer" ).html( '' );
		
		/** Bootstrap 5 Modal Toggle */
        const myModal = new bootstrap.Modal( '#install-php-version-modal', {
            keyboard: false
        });
        myModal.show( $( '#install-php-version-modal' ).get( 0 ) );
		
		// EVENTS
		$( '#install-php-version-modal' ).get( 0 ).addEventListener( 'hidden.bs.modal', function ( event ) {
			window.location.reload();
		});
	});
	
	// https://www.jqueryscript.net/demo/jQuery-Plugin-For-Multiple-Select-With-Checkboxes-multi-select-js/
	// https://github.com/mysociety/jquery-multi-select
	$( '#phpbrewVariants' ).multiSelect({
		noneText: '-- Select Variants --',
		allText: 'All Selected',
    });
    
    $( '#phpExtensions' ).multiSelect({
		noneText: '-- Select Extensions --',
		allText: 'All Selected',
    });
	
	$( '#formPhpInstall' ).on( 'submit', function()
	{
		var url               = $( "#install-php-version-modal" ).attr( 'data-actionUrl' );
		var serializedData    = $( this ).serializeArray();
		var data              = installData( serializedData );
		//console.log( data ); return false;
		
		$( "#lablePhpVersion" ).text( 'Install PHP: ' + data.version );
		
		$( "#formPhpInstall" ).hide( 1000 );
		$( "#consolePhpInstall" ).show();
		
		var term    = $( '#phpInstallContainer' ).terminal({
            install: function() {
                startInstall( url, serializedData );
            }
        });
        term.exec( 'install' );
		
		return false;
	});
	
	$( '.multi-select-button' ).addClass( 'form-select' );
});
