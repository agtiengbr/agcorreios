<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\ConsultaRotulo;

class ConsultaRotuloResponseSuccess
{
    private $nome;
    private $dados;

    /**
     * Get the value of dados
     */ 
    public function getDados()
    {
        return $this->dados;
    }

    /**
     * Set the value of dados
     *
     * @return  self
     */ 
    public function setDados($dados)
    {
        $this->dados = $dados;

        return $this;
    }

    /**
     * Get the value of nome
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }
}