(function( $ ) {
	
	'use strict';
	$(document).ready(function() {
		const cb_2week_id = "#acf-field_5fa1cfab86978-2";
		const cb_4week_id = "#acf-field_5fa1cfab86978-4";
		const txt_start_date_id = ".acf-field-5ee1191f42ed7";
		const txt_end_date_id = ".acf-field-5ee1195742ed8";
		const cb = "#cb_complete";

		$( cb_2week_id ).click(function( event ) {
			let numWeeks = 2;
			let now = new Date();
			$(txt_start_date_id).find('.hasDatepicker').datepicker('setDate', now );
			now.setDate(now.getDate() + numWeeks * 7);
			$(txt_end_date_id).find('.hasDatepicker').datepicker('setDate', now );
		});
		
		$( cb_4week_id ).click(function( event ) {
			let numWeeks = 4;
			let now = new Date();
			$(txt_start_date_id).find('.hasDatepicker').datepicker('setDate', now );
			now.setDate(now.getDate() + numWeeks * 7);
			$(txt_end_date_id).find('.hasDatepicker').datepicker('setDate', now );
		});

		$( txt_end_date_id ).change(function() {
			$( cb_2week_id ).prop( "checked", false );
			$( cb_4week_id ).prop( "checked", false );
		  });

	});


})( jQuery );

