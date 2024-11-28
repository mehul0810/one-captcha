document.addEventListener('DOMContentLoaded', function () {
	const service = document.getElementById('onecaptcha-service');

	service.addEventListener('change', function ( event ) {
		const selectedService = event.target.value;
		const serviceFieldsGroup = document.querySelectorAll('.onecaptcha-fields-group');

		Array.from(serviceFieldsGroup).forEach(function ( fieldGroup ) {
			// Set all the displayed fields group to none.
			fieldGroup.classList.remove('active');

			// Set the selected service fields group to display.
			document.querySelector( `.onecaptcha-${selectedService}-fields-group` ).classList.add( 'active' );
		});
	});

});
