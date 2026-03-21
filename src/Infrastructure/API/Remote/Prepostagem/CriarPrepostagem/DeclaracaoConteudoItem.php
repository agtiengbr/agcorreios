<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem;

class DeclaracaoConteudoItem
{
    private $conteudo;
    private $quantidade;
    private $valor;

    private static function formatValorCorreios($valor)
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        if (is_string($valor)) {
            $valor = trim($valor);

            if (strpos($valor, ',') !== false && strpos($valor, '.') !== false) {
                $valor = str_replace('.', '', $valor);
                $valor = str_replace(',', '.', $valor);
            } elseif (strpos($valor, ',') !== false) {
                $valor = str_replace(',', '.', $valor);
            }
        }

        $valorFloat = (float) $valor;
        return number_format(round($valorFloat, 2), 2, '.', '');
    }

    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = self::formatValorCorreios($valor);

        return $this;
    }

    /**
     * Get the value of quantidade
     */ 
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set the value of quantidade
     *
     * @return  self
     */ 
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get the value of conteudo
     */ 
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Set the value of conteudo
     *
     * @return  self
     */ 
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;

        return $this;
    }
}
