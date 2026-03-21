<?php
    $url = "https://www2.correios.com.br/sistemas/rastreamento/ctrl/ctrlRastreamento.cfm";
    $obj = isset($_REQUEST['objeto'])?$_REQUEST['objeto']:'';
?>

<form name="rastrear" method="post" action="<?php echo $url;?>">
    <input type="hidden" name="Objetos" value="<?php echo $obj;?>">
</form>

<script>
    window.onload = function(){
        document.forms['rastrear'].submit()
    }
</script>