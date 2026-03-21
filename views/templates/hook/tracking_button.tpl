{if isset($id_order)}
    ​<hr />
    <a href="{$link->getModuleLink('agcorreios', 'ordertracking', ['id_order' => $id_order])}">
        {l s='Acompanhar entrega' d='Shop.Theme.Customeraccount'}
    </a>
{/if}
