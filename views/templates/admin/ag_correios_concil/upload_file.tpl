<form method="post" enctype="multipart/form-data">
    <div class="panel">
        <div class="alert alert-info">Você pode anexar um arquivo CSV semelhante ao gerado pela sua fatura dos Correios para realizar a conciliação dos custos cobrados do seu cliente e os valores pagos aos Correios. <a href='{$url}'>Clique aqui</a> para baixar um arquivo de exemplo.</div>

        <div class="row">
            <div class="col-md-6">
                <label for="csvfile">Arquivo</label>
                <input class="form-control" type="file" name="csvfile" id="csvfile"/>
            </div>
            <div class="col-md-6"></div>
        </div>

        <hr>

        <button class="btn btn-primary" type="submit" name="uploadCsv">Enviar arquivo CSV</btn>
    </div>
</form>
