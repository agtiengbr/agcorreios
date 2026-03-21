$(document).ready(function() {
    // Event handler for the button click
    $('.open-label-form-btn').on('click', function() {
        cleanMessages();
        // Hide the label listing
        $('.label-listing').hide();

        // Show the label creation
        $('.label-creation').show();
    });

    $('.open-label-listing-btn').on('click', function() {
        cleanMessages();
        // Hide the label listing
        $('.label-listing').show();

        // Show the label creation
        $('.label-creation').hide();
    });

    function cleanMessages() {
        $('.agcorreios-label-error').html('');
        $('.agcorreios-label-success').html('');
    }

    // Event handler for the create label button
    $('.create_label-btn').on('click', function() {
        var serviceId = $('#services').val();
        var coleta = $('input[name="coleta"]').is(':checked') ? 'coleta' : '';
        var reversa = $('input[name="reversa"]').is(':checked') ? 'reversa' : '';
        var idOrder = $('#agcorreios_id_order').val();
        var btn = $('.create_label-btn');

        cleanMessages();

        btn.attr('disabled', true);

        $.ajax({
            url: `${agcorreios_base_uri}&api=createLabel&id_order=${idOrder}&service_code=${serviceId}&${coleta}&${reversa}`,
            method: 'POST',
            success: function(response) {
                const responseJson = JSON.parse(response);

                if (responseJson.success) {
                    $('.agcorreios-label-success').html('Etiqueta criada com sucesso, sua página atualizará em alguns instantes...');
                    location.reload();
                } else {
                    $('.agcorreios-label-error').html(responseJson.error);
                }
            },
            error: function(xhr, status, error) {
                $('.agcorreios-label-error').html(xhr.responseText)
            },
        }).always(() => {
            btn.attr('disabled', false);
        });
    });

    // Event handler for the create label button
    $('td[name=agcorreios_print_label] a').on('click', function() {
        var labelId = $(this).data('id');

        if(labelId) {
            window.open(`${agcorreios_base_uri}&api=printLabels&ids[]=${labelId}`, '_blank');
        }
    });

    // Click event to select all checkboxes
    $('.agcorreios_select_all_boxes').on('click', function() {
        $('.agcorreios_labels_table input[type="checkbox"]').prop('checked', true);
    });

    // Click event to unselect all checkboxes
    $('.agcorreios_unselect_all_boxes').on('click', function() {
        $('.agcorreios_labels_table input[type="checkbox"]').prop('checked', false);
    });

    // Click event to print multiple labels
    $('.agcorreios_print_multiple_labels').on('click', function() {
        var selectedIds = [];
        $('.agcorreios_labels_table input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        // Format the ids[] query string
        var idsQueryString = selectedIds.map(id => `ids[]=${id}`).join('&');

        window.open(`${agcorreios_base_uri}&api=printLabels&${idsQueryString}`, '_blank');


        console.log(idsQueryString);
    });
});
