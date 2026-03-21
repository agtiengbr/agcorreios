<div class="row">
	<ul class="nav nav-tabs vertical col-lg-2" aria-orientation="vertical">
		<li class="active"><a data-toggle="tab" href="#tabConfig"><i class="icon-cogs"></i> Configurações</a></li>
		<li class=""><a href="{$url_services}"> Serviços </a></li>
		<li class=""><a href="{$url_labels}"> Etiquetas </a></li>
		<li class=""><a href="{$url_prices}"> Preços </a></li>
		<li class=""><a href="{$url_discounts}"> Descontos </a></li>
		<li class=""><a href="{$url_interval}"> Regiões </a></li>
		<li class=""><a href="{$url_concil}"> Conciliação </a></li>
	</ul>

	<div class='tab-content col-lg-10'>
		<div class='tab-pane active' id="tabConfig">
			<ul class="nav nav-tabs" role="tablist">
				<li class='active'>
					<a data-toggle="tab" href="#tabConfigurations">
						<i class="icon-cogs"></i> Configuração
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tabAdditionalConfigurations">
						<i class="icon-cogs"></i> Configurações Extras
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tabMappingTracking">
						<i class="icon-cogs"></i> Mapeamento de Rastreamento
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tabSimulation">
						<i class="icon-cogs"></i> Simulaçao de Frete
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tabMaintenance">
						<i class="icon-question-circle"></i> Manutenção
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tabHelp">
						<i class="icon-question-circle"></i> Ajuda
					</a>
				</li>
			</ul>
			<div class='tab-content'>
				<div class='tab-pane active in' id="tabConfigurations">{$tabs['config']}</div>
				<div class='tab-pane' id="tabAdditionalConfigurations">{$tabs['additional_config']}</div>
				<div class='tab-pane' id="tabMappingTracking">{$tabs['mappign_tracking']}</div>
				<div class='tab-pane' id="tabSimulation">{$tabs['simulation']}</div>
				<div class='tab-pane' id="tabMaintenance">{$tabs['maintenance']}</div>
				<div class='tab-pane' id="tabHelp">
					<div class='panel'>
						{include file=$modules_path|cat:"agcliente/views/templates/hook/includes/tab_help.tpl"}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
