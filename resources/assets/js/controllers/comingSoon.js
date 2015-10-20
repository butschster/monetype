App.Components.add('register.validator', function() {
	$.countdown.setDefaults($.countdown.regionalOptions['ru']);
	$('#defaultCountdown').countdown({
		until: new Date(2016, 1-1, 1)
	});

	$('.cooming-soon-content').backstretch([
		"/img/coming_soon.jpg"
	], {fade: 1000});

	// Validation for login form
	$("#registerForm").validate({
		rules: {
			username: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 3,
				maxlength: 20
			},
			password_confirmation: {
				required: true,
				minlength: 3,
				maxlength: 20,
				equalTo: 'input[name="password"]'
			}
		},

		// Messages for form validation
		messages: {
			email: {
				required: 'Please enter your email address',
				email: 'Please enter a VALID email address'
			},
			password: {
				required: 'Please enter your password'
			},
			password_confirmation: {
				required: 'Please enter your password one more time',
				equalTo: 'Please enter the same password as above'
			}
		}
	});
});