(function () {
	var forms = document.querySelectorAll('.ignition-booking-form');

	if (forms.length > 0) {
		var today = new Date();

		Array.from(forms).forEach(function (form) {
			var pickerEnd = new Litepicker({
				element: form.querySelector('.ignition-booking-form-depart'),
				minDate: today,
				minDays: 2,
				plugins: ['mobilefriendly'],
			});

			var pickerStart = new Litepicker({
				element: form.querySelector('.ignition-booking-form-arrive'),
				minDate: today,
				plugins: ['mobilefriendly'],
				setup: function (picker) {
					picker.on('hide', function () {
						var startDate = picker.getDate();
						startDate.setDate(startDate.getDate() + 1);
						pickerEnd.setOptions({
							minDate: startDate,
						});
					});
				},
			});
		})
	}
})();
