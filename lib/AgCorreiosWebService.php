<?php
use AgCorreios\Exceptions\OverWeightException;
use AgCorreios\Exceptions\UnreachableAddressException;

class AgCorreiosWebservice
{
    public static function calculaFrete(
        $servico,
        $cepOrigem,
        $cepDestino,
        $peso,
        $altura,
        $largura,
        $comprimento,
        $valor,
        $aviso_recebimento,
        $maos_proprias,
        $redirect = false,
        $contract_number='',
        $contract_password=''
    ) {
        $largura = max($largura, 20);
        $comprimento = max($comprimento, 16);
        $altura = max($altura, 2);

        $maoPropria = $maos_proprias? 'S' : 'N';
        $avisoRecebimento = $aviso_recebimento? 'S' : 'N';
            
        $valor = str_replace(".", ",", $valor);

        $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
        
        $url .= "
            nCdServico=$servico&
            sCepOrigem=$cepOrigem&
            sCepDestino=$cepDestino&
            nVlPeso=$peso&
            nCdFormato=1&
            nVlComprimento=$comprimento&
            nVlAltura=$altura&
            nVlLargura=$largura&
            nVlDiametro=1&
            sCdMaoPropria=$maoPropria&
            nVlValorDeclarado=$valor&
            sCdAvisoRecebimento=$avisoRecebimento&
            StrRetorno=xml
        ";

        if ($contract_number && $contract_password) {
            $url .= "&nCdEmpresa={$contract_number}&sDsSenha={$contract_password}";
        }

        $url = preg_replace('/\s+/', '', $url);
        if ($redirect) {
            header("Location: {$url}");
            exit();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($ch);
        curl_close($ch);

        if ($xml) {
            $xml = self::produceXMLObjectTree($xml);
        }

        // Verifica se não há erros segundo páginas 14 e 15 de
        //https://www.correios.com.br/para-voce/correios-de-a-a-z/pdf/calculador-remoto-de-precos-e-prazos/manual-de-implementacao-do-calculo-remoto-de-precos-e-prazos
        if ($xml and ($xml->cServico->Erro == '0' || in_array ($xml->cServico->Erro, ['010', '009', '011']))) {
            $array = (array)($xml->cServico);
            $preco = (float)str_replace([".", ","], ["", "."], $array["Valor"]);
            $preco = number_format($preco, 2, '.', '');
            
            $return["total"] = $preco;
            $return["prazo"] = $array["PrazoEntrega"];
            $return["maoPropria"] = str_replace(",", ".", $array["ValorMaoPropria"]);
            $return["avisoRecebimento"] = str_replace(",", ".", $array["ValorAvisoRecebimento"]);
            $return["valorDeclarado"] = str_replace(",", ".", $array["ValorValorDeclarado"]);
            $return["frete"] =
            $return["total"] - $return["maoPropria"] - $return["avisoRecebimento"] - $return["valorDeclarado"];

            return $return;
        } else {
            if (@$xml->cServico->Erro == -4) {
                throw new OverWeightException("Peso excedido.");
            }

            if (@$xml->cServico->Erro == '-888' || @$xml->cServico->Erro == '-3') {
                throw new UnreachableAddressException("Esse serviço não pode ser utilizado para o destino desejado.");   
            }

            $error = 'Ocorreu um erro inesperado. Retorno: ' . json_encode($xml) . ' código ' . $xml->cServico->Erro;
            throw new Exception($error);
        }
    }

    protected static function produceXMLObjectTree($raw_XML)
    {
        libxml_use_internal_errors(true);
        try {
            $xmlTree = new SimpleXMLElement($raw_XML);
        } catch (Exception $e) {
            // Something went wrong.
            $error_message = 'SimpleXMLElement threw an exception.';
            foreach (libxml_get_errors() as $error_line) {
                $error_message .= "\t" . $error_line->message;
            }

            trigger_error($error_message);
            return false;
        }
        return $xmlTree;
    }
}
