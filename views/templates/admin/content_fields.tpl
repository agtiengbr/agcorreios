<div class="tab-pane d-print-block" id="etiquetasContent" role="tabpanel" aria-labelledby="etiquetasContent">
    <div class="my-3">

        <input class="hidden d-none" id="agcorreios_id_order" value="{$id_order}">

        <div class="label-listing">
            <button class="btn btn-primary open-label-form-btn">Gerar nova etiqueta</button>
            <hr class="my-3">

            <div class="alert alert-danger agcorreios-label-error"></div>
            <div class="alert alert-success agcorreios-label-success"></div>

            <h4>Etiquetas de postagem:</h4>
            {if $labels}
                <div class="table-responsive" style="max-height: 200px;">
                    <table class="table agcorreios_labels_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Cód. Rastreio</th>
                                <th>Emissão</th>
                                <th>Estado</th>
                                <th>Imprimir</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$labels item=$label}
                                <tr>
                                    <td class="row-selector text-center">
                                        <input type="checkbox" name="agcorreios_labels[]" value="{$label.id}" class="noborder">
                                    </td>
                                    <td name="agcorreios_tracking_code">{$label.tracking_code}</td>
                                    <td name="agcorreios_date_add">{$label.date_add}</td>
                                    <td name="agcorreios_status_atual">{$label.status_atual}</td>
                                    <td name="agcorreios_print_label"><a class="cursor-pointer" data-id="{$label.id}"><i class="material-icons">print</i></a></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>

                <div class="btn-group bulk-actions dropup">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="bulk_action_menu_agcorreios_admin_labels">
                        Ações em massa <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="cursor-pointer agcorreios_select_all_boxes"><i class="material-icons">check_box</i> Selecionar todos</a>
                        </li>
                        <li>
                            <a class="cursor-pointer agcorreios_unselect_all_boxes"><i class="material-icons">check_box_outline_blank</i> Limpar seleções</a>
                        </li>
                        <li class="divider"><hr class="my-1"></li>
                        <li>
                            <a class="cursor-pointer agcorreios_print_multiple_labels"><i class="material-icons">print</i> Imprimir</a>
                        </li>
                    </ul>
                </div>
            {else}
                <p>Ainda não existem etiquetas de postagem para este pedido.</p>
            {/if}
        </div>

        <div class="label-creation" style="display: none;">
            <h3>Gerar nova etiqueta</h3>
            <hr class="my-3">

            <div class="alert alert-danger agcorreios-label-error"></div>
            <div class="alert alert-success agcorreios-label-success"></div>

            <div class="form-group">
                <label>Selecione abaixo um dos serviços de envio disponíveis:</label>
                <select name="services" id="services" class="form-control col-lg-6">
                    {foreach from=$services item=$service}
                        <option value="{$service.correios_code}">{$service.name}</option>
                    {/foreach}
                </select>
            </div>

            <div class="form-group">
                <label>Escolha uma opção:</label>
                <div class="input-group flex-column">
                    <label>
                        <input type="checkbox" name="coleta" value="coleta"> Coleta
                    </label>
                    <label>
                        <input type="checkbox" name="reversa" value="reversa"> Reversa
                    </label>
                </div>
            </div>
            <hr class="my-2">
            <div class="text-right">
                <button class="btn btn-default open-label-listing-btn">Voltar</button>
                <button class="btn btn-primary create_label-btn">Gerar etiqueta</button>
            </div>
        </div>
    </div>
</div>
