<label class='col-lg-3 control-label'>Faixas de Peso</label>

<div class='col-lg-2 well'>
	<div class='row'>
		<div class='col-sm-5'>
			DE
		</div>
		<div class='col-sm-5'>
			ATÉ
		</div>
	</div>
	{foreach $weights as $weight}
		<div class='row' data-obj-id="{$obj->id}">
			<div class='col-sm-5'>
				<input type="text" name="weights[{$weight['id_agcorreios_range_weight']}][delimiter1]" value="{$weight.delimiter1}"/>
			</div>
			<div class='col-sm-5'>
				<input type="text" name="weights[{$weight['id_agcorreios_range_weight']}][delimiter2]" value="{$weight.delimiter2}" />
			</div>

			<icon class="icon-times" title="Excluir Intervalo"/>
		</div>
	{/foreach}
	<button class="btn btn-default"><icon class="icon-plus"/>Adicionar Intervalo</button>
</div>