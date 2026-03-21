<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional;

class PrecoNacionalResponseSuccess
{
    private $coProduto;
    private $pcBase;
    private $pcBaseGeral;
    private $peVariacao;
    private $pcReferencia;
    private $vlBaseCalculoImposto;
    private $inPesoCubico;
    private $psCobrado;
    private $peAdValorem;
    private $vlSeguroAutomatico;
    private $qtAdicional;
    private $pcFaixa;
    private $pcFaixaVariacao;
    private $pcProduto;
    private $pcFinal;

    /**
     * Get the value of coProduto
     */ 
    public function getCoProduto()
    {
        return $this->coProduto;
    }

    /**
     * Set the value of coProduto
     *
     * @return  self
     */ 
    public function setCoProduto($coProduto)
    {
        $this->coProduto = $coProduto;

        return $this;
    }

    /**
     * Get the value of pcBase
     */ 
    public function getPcBase()
    {
        return $this->pcBase;
    }

    /**
     * Set the value of pcBase
     *
     * @return  self
     */ 
    public function setPcBase($pcBase)
    {
        $this->pcBase = $pcBase;

        return $this;
    }

    /**
     * Get the value of pcBaseGeral
     */ 
    public function getPcBaseGeral()
    {
        return $this->pcBaseGeral;
    }

    /**
     * Set the value of pcBaseGeral
     *
     * @return  self
     */ 
    public function setPcBaseGeral($pcBaseGeral)
    {
        $this->pcBaseGeral = $pcBaseGeral;

        return $this;
    }

    /**
     * Get the value of peVariacao
     */ 
    public function getPeVariacao()
    {
        return $this->peVariacao;
    }

    /**
     * Set the value of peVariacao
     *
     * @return  self
     */ 
    public function setPeVariacao($peVariacao)
    {
        $this->peVariacao = $peVariacao;

        return $this;
    }

    /**
     * Get the value of pcReferencia
     */ 
    public function getPcReferencia()
    {
        return $this->pcReferencia;
    }

    /**
     * Set the value of pcReferencia
     *
     * @return  self
     */ 
    public function setPcReferencia($pcReferencia)
    {
        $this->pcReferencia = $pcReferencia;

        return $this;
    }

    /**
     * Get the value of vlBaseCalculoImposto
     */ 
    public function getVlBaseCalculoImposto()
    {
        return $this->vlBaseCalculoImposto;
    }

    /**
     * Set the value of vlBaseCalculoImposto
     *
     * @return  self
     */ 
    public function setVlBaseCalculoImposto($vlBaseCalculoImposto)
    {
        $this->vlBaseCalculoImposto = $vlBaseCalculoImposto;

        return $this;
    }

    /**
     * Get the value of inPesoCubico
     */ 
    public function getInPesoCubico()
    {
        return $this->inPesoCubico;
    }

    /**
     * Set the value of inPesoCubico
     *
     * @return  self
     */ 
    public function setInPesoCubico($inPesoCubico)
    {
        $this->inPesoCubico = $inPesoCubico;

        return $this;
    }

    /**
     * Get the value of psCobrado
     */ 
    public function getPsCobrado()
    {
        return $this->psCobrado;
    }

    /**
     * Set the value of psCobrado
     *
     * @return  self
     */ 
    public function setPsCobrado($psCobrado)
    {
        $this->psCobrado = $psCobrado;

        return $this;
    }

    /**
     * Get the value of peAdValorem
     */ 
    public function getPeAdValorem()
    {
        return $this->peAdValorem;
    }

    /**
     * Set the value of peAdValorem
     *
     * @return  self
     */ 
    public function setPeAdValorem($peAdValorem)
    {
        $this->peAdValorem = $peAdValorem;

        return $this;
    }

    /**
     * Get the value of vlSeguroAutomatico
     */ 
    public function getVlSeguroAutomatico()
    {
        return $this->vlSeguroAutomatico;
    }

    /**
     * Set the value of vlSeguroAutomatico
     *
     * @return  self
     */ 
    public function setVlSeguroAutomatico($vlSeguroAutomatico)
    {
        $this->vlSeguroAutomatico = $vlSeguroAutomatico;

        return $this;
    }

    /**
     * Get the value of qtAdicional
     */ 
    public function getQtAdicional()
    {
        return $this->qtAdicional;
    }

    /**
     * Set the value of qtAdicional
     *
     * @return  self
     */ 
    public function setQtAdicional($qtAdicional)
    {
        $this->qtAdicional = $qtAdicional;

        return $this;
    }

    /**
     * Get the value of pcFaixa
     */ 
    public function getPcFaixa()
    {
        return $this->pcFaixa;
    }

    /**
     * Set the value of pcFaixa
     *
     * @return  self
     */ 
    public function setPcFaixa($pcFaixa)
    {
        $this->pcFaixa = $pcFaixa;

        return $this;
    }

    /**
     * Get the value of pcFaixaVariacao
     */ 
    public function getPcFaixaVariacao()
    {
        return $this->pcFaixaVariacao;
    }

    /**
     * Set the value of pcFaixaVariacao
     *
     * @return  self
     */ 
    public function setPcFaixaVariacao($pcFaixaVariacao)
    {
        $this->pcFaixaVariacao = $pcFaixaVariacao;

        return $this;
    }

    /**
     * Get the value of pcProduto
     */ 
    public function getPcProduto()
    {
        return $this->pcProduto;
    }

    /**
     * Set the value of pcProduto
     *
     * @return  self
     */ 
    public function setPcProduto($pcProduto)
    {
        $this->pcProduto = $pcProduto;

        return $this;
    }

    /**
     * Get the value of pcFinal
     */ 
    public function getPcFinal()
    {
        return $this->pcFinal;
    }

    /**
     * Set the value of pcFinal
     *
     * @return  self
     */ 
    public function setPcFinal($pcFinal)
    {
        $this->pcFinal = $pcFinal;

        return $this;
    }
}