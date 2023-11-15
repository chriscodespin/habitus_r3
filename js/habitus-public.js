
(function( $ ) {
	
	'use strict';
	$(document).ready(function() {

        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        $("#form-timezone").val(timezone);

		//var optionsDisplay = { year: 'numeric', month: 'long', day: 'numeric' };
        //$("#tracking-date-display").text(now.toLocaleDateString("en-US", optionsDisplay));
    });

})( jQuery );
