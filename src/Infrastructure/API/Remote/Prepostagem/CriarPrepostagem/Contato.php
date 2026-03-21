<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem;

class Contato
{
    private $nome;
    private $dddTelefone;
    private $telefone;
    private $dddCelular;
    private $celular;
    private $email;
    private $cpfCnpj;
    private $documentoEstrangeiro;
    private $obs;
    private $endereco;

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

    /**
     * Get the value of dddTelefone
     */ 
    public function getDddTelefone()
    {
        return $this->dddTelefone;
    }

    /**
     * Set the value of dddTelefone
     *
     * @return  self
     */ 
    public function setDddTelefone($dddTelefone)
    {
        $this->dddTelefone = $dddTelefone;

        return $this;
    }

    /**
     * Get the value of telefone
     */ 
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set the value of telefone
     *
     * @return  self
     */ 
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get the value of dddCelular
     */ 
    public function getDddCelular()
    {
        return $this->dddCelular;
    }

    /**
     * Set the value of dddCelular
     *
     * @return  self
     */ 
    public function setDddCelular($dddCelular)
    {
        $this->dddCelular = $dddCelular;

        return $this;
    }

    /**
     * Get the value of celular
     */ 
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set the value of celular
     *
     * @return  self
     */ 
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of cpfCnpj
     */ 
    public function getCpfCnpj()
    {
        return $this->cpfCnpj;
    }

    /**
     * Set the value of cpfCnpj
     *
     * @return  self
     */ 
    public function setCpfCnpj($cpfCnpj)
    {
        $this->cpfCnpj = $cpfCnpj;

        return $this;
    }

    /**
     * Get the value of documentoEstrangeiro
     */ 
    public function getDocumentoEstrangeiro()
    {
        return $this->documentoEstrangeiro;
    }

    /**
     * Set the value of documentoEstrangeiro
     *
     * @return  self
     */ 
    public function setDocumentoEstrangeiro($documentoEstrangeiro)
    {
        $this->documentoEstrangeiro = $documentoEstrangeiro;

        return $this;
    }

    /**
     * Get the value of obs
     */ 
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * Set the value of obs
     *
     * @return  self
     */ 
    public function setObs($obs)
    {
        $this->obs = $obs;

        return $this;
    }

    /**
     * Get the value of endereco
     */ 
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set the value of endereco
     *
     * @return  self
     */ 
    public function setEndereco(Endereco $endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }
}