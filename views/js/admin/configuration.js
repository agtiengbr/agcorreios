$(function(){
	$.getJSON(location.href + '&check_auth', function(data){
		if (!data.is_authenticated) {
			$('form[name=agcorreios]')
				.find('input, textarea').filter(function(){return $(this).is(':not([name^=AGTI]):not([name=agcorreios_zipcode_origin]') && $(this).closest('#tabHelp').length == 0;})
				.attr('disabled', true)
				.attr('title', 'Esse campo não pode ser modificado na versão gratuita do módulo.');
		}

		$('#agcorreios_qty_processes').prop('type', 'number').prop('max', '30').prop('disabled', false);
		$('[name=agcorreios_precalculate]').prop('disabled', false);
	}); 
})

$(document).ready(function() {
    $('#AGCORREIOS_SHOP_POSTCODE').on('input', function(event) {
        // Remove any non-numeric characters for paste or other input methods
        this.value = this.value.replace(/\D/g, '').substring(0, 8);
    });

	$('#AGCORREIOS_SHOP_DDD').on('input', function(event) {
        // Limit to 2 digits
        if (this.value.length >= 2) {
            event.preventDefault(); // Stop further input if 2 digits are already entered
        }

        // Remove any non-numeric characters in case of paste or other input methods
        this.value = this.value.replace(/\D/g, '').slice(0, 2);

    });
    
	$('#AGCORREIOS_SHOP_PHONE').on('input', function(event) {
        // Limit to 9 digits
        if (this.value.length >= 9) {
            event.preventDefault(); // Stop further input if 9 digits are already entered
        }

        // Remove any non-numeric characters in case of paste or other input methods
        this.value = this.value.replace(/\D/g, '').slice(0, 9);
    });
});
