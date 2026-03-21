window.addEventListener('load', function(){

    $("body").append(`
    <div id='appVue'>
        <modalCSV :api='this.api'/>
    </div>`);

    new Vue({
        el: '#appVue',
        data: {
            api: {
                'importcsv' : urls.importcsv
            }

        }
    });
    $('.agmodal').hide();
    
});

$(function(){
    $('#page-header-desc-agcorreios_interval-download').prop('target', '_blank');
    $('#page-header-desc-agcorreios_interval-download').click(function(){
        if (confirm('Tem certeza? Os preços deverão ser todos recalculados após a realizaçao desse procedimento.')) {
            $.get($(this).prop('href'));
            $('#form-agcorreios_interval').prepend(`<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>A criação dos intervalos está sendo processada em segundo plano.</div>`);
        }

        return false;
    });

    
});
$( document ).ready(function() {
    $('#page-header-desc-agcorreios_interval-csv').removeAttr('href');

    $('#page-header-desc-agcorreios_interval-csv').click(function(){
        $('.agmodal').show();
    });
});
