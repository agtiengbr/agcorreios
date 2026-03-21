document.addEventListener('DOMContentLoaded', function(){
	function renderWeightsSelection()
	{
		var div = $('<div/>', {class: 'form-group'});
		div.insertAfter($('[name=enabled]').closest('.form-group'));

		var current_url = location.href.replace('#', '');
		div.load(current_url + '&renderWeightsPanel');
	}

	renderWeightsSelection();
});