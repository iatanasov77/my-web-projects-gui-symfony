$(function()
{
    $( '#btnCreateDatasource' ).on( 'click', function () {
        var url = $( this ).attr( 'data-url' );

        $.ajax({
            type: "GET",
            url: url,
            success: function( response )
            {
                $( '#datasourceFormContainer > div.card-body' ).html( response );
                
                /** Bootstrap 5 Modal Toggle */
                const myModal = new bootstrap.Modal( '#grafana-datasource-modal', {
                    keyboard: false
                });
                myModal.show( $( '#grafana-datasource-modal' ).get( 0 ) );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '#btnCreateDatasourceExporter' ).on( 'click', function () {
        var url = $( this ).attr( 'data-url' );

        $.ajax({
            type: "GET",
            url: url,
            success: function( response )
            {
                $( '#datasourceExporterFormContainer > div.card-body' ).html( response );
                
                /** Bootstrap 5 Modal Toggle */
                const myModal = new bootstrap.Modal( '#grafana-datasource-exporter-modal', {
                    keyboard: false
                });
                myModal.show( $( '#grafana-datasource-exporter-modal' ).get( 0 ) );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
    
    $( '#btnApplyDatasource' ).on( 'click', function ( e )
    {
        // alert( 'FormDatasource Submit !!!' );
        $( '#FormDatasource' ).submit();
    });
    
    $( '#btnApplyDatasourceExporter' ).on( 'click', function ( e )
    {
        alert( 'FormDatasourceExporter Submit !!!' );
        $( '#FormDatasourceExporter' ).submit();
    });
});