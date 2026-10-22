{extends file='page.tpl'}
{block name='page_title'}
    Rastreamento
{/block}

{block name='page_content'}

    <div>
        <h2>Acompanhe sua entrega</h2>
        <h6>Esses são os eventos de rastreamento associados aos seus pedidos.</h6>
    </div>

    <hr>

    <div class="my-4">
        <p><b>Código de rastreio:</b> {$trackingInfo}</p>
    </div>

    {if !empty($trackingRefreshFailed)}
        <div class="alert alert-warning" role="alert">Não foi possível atualizar o rastreamento junto aos Correios neste momento. Exibindo os eventos já conhecidos pela integração.</div>
    {/if}

    <div class="orders hidden-md-up">
        {if isset($trackingEvents)}
            {foreach from=$trackingEvents item=event}
                <div class="order mb-2 border-bottom pb-1">
                    <div class="row">
                        <div class="col-12">
                            <div><b>{$event.desc}</b></div>
                            <div class="date">{$event.date_add}</div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/if}
    </div>

    <table class="table table-striped table-bordered table-labeled hidden-sm-down">
        <thead class="thead-default">
            <tr>
                <th>Descrição</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$trackingEvents item=event}
                <tr>
                    <td>{$event.desc}</td>
                    <td>{$event.date_add}</td>
                </tr>   
            {/foreach}
        </tbody>
    </table>
{/block}
   