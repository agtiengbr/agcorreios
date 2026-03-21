window.addEventListener('load', function(){
    Vue.component('modalcsv', {
        props: [
            'api'
        ],
        data: function(){
            return {
                disabledButtons: false,

            };
        },
        
        template: `
        <agmodal @backdropClicked="closeModal" class="bootstrap">

            <template slot="header">
                <h5 class="modal-title">Importar CSV</h5>
            </template>
            <template slot="body">
                <div class="alert alert-warning">Atenção: O processamento do seu arquivo CSV será feito em segundo plano, e isso pode levar alguns minutos, a depender da quantidade de linhas do seu arquivo. O seu PrestaShop poderá ficar inacessível, apenas para o seu usuário, durante o processamento do arquivo. O módulo gerará um log no painel do seu PrestaShop, informando se o processamento foi concluído com sucesso ou não.</div>

                <div class="alert alert-info">Clique <a href="/modules/agcorreios/data/importExample.csv" target="_blank">aqui</a> para baixar o modelo de CSV.</div>

                <input type="file" class="form-control" id="csvFile" placeholder="csv">
            </template>
        
            <template slot="footer">
                <button type="button" class="btn btn-primary" :disabled="disabledButtons" @click="sendCsvFile">Confirmar</button>
                <button type="button" class="btn btn-danger" :disabled="disabledButtons" @click="closeModal" data-dismiss="modal">Cancelar</button>
            </template>
        </agmodal>
        `,

        methods: {
            closeModal: function(e){
                $('.agmodal').hide();
            },
            sendCsvFile: function(e){
                let that = this;
                var form = new FormData();
                form.append('fileUpload', $('#csvFile').prop('files')[0]);
                this.disabledButtons=true;
            
                $.ajax({
                    url: this.api.importcsv,
                    data: form,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: data  => {

                    },
                    beforeSend: () => { 
                        that.disabledButtons=true;
                        alert("O processamento foi iniciado.");
                        that.closeModal();
                    },
                    statusCode: {
                        504: (responseObject, textStatus, jqXHR) => {
                            that.disabledButtons=false;
                            alert("Essa ação esta demorando mais do que esperado,verifique nos logs do prestashop se ele foi concluido.");
                        },
                    }
                })
            },
        }
    });
});