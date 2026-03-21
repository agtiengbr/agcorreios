<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Rastro\Objetos;

class TipoPostal
{
    private $sigla;
    private $descricao;
    private $categoria;

    /**
     * Get the value of sigla
     */ 
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set the value of sigla
     *
     * @return  self
     */ 
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get the value of descricao
     */ 
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     *
     * @return  self
     */ 
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of categoria
     */ 
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set the value of categoria
     *
     * @return  self
     */ 
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }
}