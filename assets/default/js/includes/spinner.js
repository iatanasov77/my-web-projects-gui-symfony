export const spinner = '<div id="project-spinner" style="text-align:center;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>';

/**
 * onTopOf: ID of the element on top of display the spinner
 */
export const showSpinner = ( onTopOf ) => {
    $( onTopOf ).before( spinner );
    $( '#project-spinner' ).css( {'opacity': '0.8', 'position':'absolute', 'text-align':'center', 'top':'170px', 'left':'210px', 'z-index':'9'} );
    $( onTopOf ).css( {'pointer-events': 'none', 'position':'relative', 'width': '100%', 'height': '100%', 'opacity': '0.8'} );
}

export const hideSpinner = ( onTopOf ) => {
    $( onTopOf ).removeAttr( 'style' );
    $( '#project-spinner' ).remove();
}
