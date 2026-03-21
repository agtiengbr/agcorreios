window.addEventListener('load', function(){
    (function createApp(){
        let html = `
            <div id="pricesApp">
                <agmodal v-if="modal" @backdrop-clicked="modal=false">
                    <div slot="body">
                        <loading-div v-if="loading"></loading-div>
                        <div v-else>
                            <p>Serviço dos Correios: {{ service.correios_name }}</p>

                            <p>Preenchimento da tabela:</p>
                                <ul>
                                    <li v-for="weight in weights">De {{ weight.from }}kg a {{ weight.to }}kg: {{ weight.fillment }}%<qli>
                                </ul>
                            </p>
                        </div>
                    </div>
                </agmodal>
            </div>
        `

        $(html).appendTo($('body'));
    })();

    Vue.component('loading-div', {
        template: `<div class="div-loading"><svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" width="64" height="64" stroke="#007bff"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"></animateTransform></path></g></g></svg></div>`
    });

    app = new Vue({
        el: '#pricesApp',
        data: {
            idService: -1,
            modal: false,
            service: {},
            weights: [],
            loading: false
        },
        watch: {
            idService: function(){
                this.loadData();
            }
        },
        methods: {
            loadData: async function(){
                this.loading = true;

                let url = new URL(location.href);

                params = url.searchParams;
                params.set('action', 'loadServiceData');
                params.set('id', this.idService);

                let r = await axios.get(url.toString());

                this.service = r.data.service;
                this.weights = r.data.weights;

                this.loading = false;
                console.log(this.loading);
            }
        }
    });

    $('.create_intervals').click(function(){
        let url = $(this).prop('href');
        $.get(url);

        $('#form-agcorreios_services').prepend(`<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>A criação dos preços está sendo processada em segundo plano. Por favor espere ao menos 30 minutos antes de verificar se ela foi concluída ou tentar novamente.</div>`);

        return false;
    })

    $('.column-total_prices').click(function(){
        let id = $(this).closest('tr').find('.column-id_agcorreios_services').text().trim();

        app.idService = id;
        app.modal = true;

        return false;
    });
});