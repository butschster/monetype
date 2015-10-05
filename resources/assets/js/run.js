$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	runApplication();

	function runApplication() {
		App.Components.init();
		App.Controllers.call();
		App.Messages.init();
	}
});