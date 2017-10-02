 $(function() {
		var dateFormat = "mm/dd/yy",
			from = $( "#from" )
				.datepicker({
					defaultDate: "+1w",
					changeMonth: false,
					numberOfMonths: 1,
					minDate:'today'
				})
				.on( "change", function() {
					to.datepicker( "option", "minDate", getDate( this ) );
				}),
			to = $( "#to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: false,
				numberOfMonths: 1
			})
			departuredate = $( "#departuredate" ).datepicker({
				defaultDate: "+1w",
				changeMonth: false,
				numberOfMonths: 1,
				minDate:'today'
			})
			
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			});


/*$(".datedepature" ).datepicker({
				defaultDate: "+1w",
				changeMonth: false,
				numberOfMonths: 1,
				minDate:'today'
			});*/


		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
		
	} );
	
	
	
	