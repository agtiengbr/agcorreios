<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem;


class CriarPrepostagemServiceArgs
{
    private $idCorreios;
    private $remetente;
    private $destinatario;
    private $codigoServico;
    private $numeroNotaFiscal;
    private $numeroCartaoPostagem;
    private $chaveNFe;
    private $itensDeclaracaoConteudo;
    private $pesoInformado;
    private $codigoFormatoObjetoInformado;
    private $alturaInformada;
    private $larguraInformada;
    private $comprimentoInformado;
    private $diametroInformado;
    private $cienteObjetoNaoProibido;
    private $solicitarColeta;
    private $logisticaReversa;

    /**
     * @var \DateTime
     */
    private $prazoPostagem;

    /**
      * @var boolean
     */
    private $statusAtual;

    /**
      * @var string
     */
    private $dataHoraStatusAtual;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $codigoObjeto;

    /**
     * Get the value of remetente
     */ 
    public function getRemetente()
    {
        return $this->remetente;
    }

    /**
     * Set the value of remetente
     *
     * @return  self
     */ 
    public function setRemetente(Contato $remetente)
    {
        $this->remetente = $remetente;

        return $this;
    }

    /**
     * Get the value of destinatario
     */ 
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set the value of destinatario
     *
     * @return  self
     */ 
    public function setDestinatario(Contato $destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get the value of codigoServico
     */ 
    public function getCodigoServico()
    {
        return $this->codigoServico;
    }

    /**
     * Set the value of codigoServico
     *
     * @return  self
     */ 
    public function setCodigoServico($codigoServico)
    {
        $this->codigoServico = $codigoServico;

        return $this;
    }

    /**
     * Get the value of numeroNotaFiscal
     */ 
    public function getNumeroNotaFiscal()
    {
        return $this->numeroNotaFiscal;
    }

    /**
     * Set the value of numeroNotaFiscal
     *
     * @return  self
     */ 
    public function setNumeroNotaFiscal($numeroNotaFiscal)
    {
        $this->numeroNotaFiscal = $numeroNotaFiscal;

        return $this;
    }

    /**
     * Get the value of numeroCartaoPostagem
     */ 
    public function getNumeroCartaoPostagem()
    {
        return $this->numeroCartaoPostagem;
    }

    /**
     * Set the value of numeroCartaoPostagem
     *
     * @return  self
     */ 
    public function setNumeroCartaoPostagem($numeroCartaoPostagem)
    {
        $this->numeroCartaoPostagem = $numeroCartaoPostagem;

        return $this;
    }

    /**
     * Get the value of chaveNFe
     */ 
    public function getChaveNFe()
    {
        return $this->chaveNFe;
    }

    /**
     * Set the value of chaveNFe
     *
     * @return  self
     */ 
    public function setChaveNFe($chaveNFe)
    {
        $this->chaveNFe = $chaveNFe;

        return $this;
    }

    /**
     * Get the value of itensDeclaracaoConteudo
     */ 
    public function getItensDeclaracaoConteudo()
    {
        return $this->itensDeclaracaoConteudo;
    }

    /**
     * Set the value of itensDeclaracaoConteudo
     *
     * @return  self
     */ 
    public function setItensDeclaracaoConteudo($itensDeclaracaoConteudo)
    {
        $this->itensDeclaracaoConteudo = $itensDeclaracaoConteudo;

        return $this;
    }

    /**
     * Get the value of pesoInformado
     */ 
    public function getPesoInformado()
    {
        return $this->pesoInformado;
    }

    /**
     * Set the value of pesoInformado
     *
     * @return  self
     */ 
    public function setPesoInformado($pesoInformado)
    {
        $this->pesoInformado = $pesoInformado;

        return $this;
    }

    /**
     * Get the value of codigoFormatoObjetoInformado
     */ 
    public function getCodigoFormatoObjetoInformado()
    {
        return $this->codigoFormatoObjetoInformado;
    }

    /**
     * Set the value of codigoFormatoObjetoInformado
     *
     * @return  self
     */ 
    public function setCodigoFormatoObjetoInformado($codigoFormatoObjetoInformado)
    {
        $this->codigoFormatoObjetoInformado = $codigoFormatoObjetoInformado;

        return $this;
    }

    /**
     * Get the value of alturaInformada
     */ 
    public function getAlturaInformada()
    {
        return $this->alturaInformada;
    }

    /**
     * Set the value of alturaInformada
     *
     * @return  self
     */ 
    public function setAlturaInformada($alturaInformada)
    {
        $this->alturaInformada = $alturaInformada;

        return $this;
    }

    /**
     * Get the value of larguraInformada
     */ 
    public function getLarguraInformada()
    {
        return $this->larguraInformada;
    }

    /**
     * Set the value of larguraInformada
     *
     * @return  self
     */ 
    public function setLarguraInformada($larguraInformada)
    {
        $this->larguraInformada = $larguraInformada;

        return $this;
    }

    /**
     * Get the value of comprimentoInformado
     */ 
    public function getComprimentoInformado()
    {
        return $this->comprimentoInformado;
    }

    /**
     * Set the value of comprimentoInformado
     *
     * @return  self
     */ 
    public function setComprimentoInformado($comprimentoInformado)
    {
        $this->comprimentoInformado = $comprimentoInformado;

        return $this;
    }

    /**
     * Get the value of diametroInformado
     */ 
    public function getDiametroInformado()
    {
        return $this->diametroInformado;
    }

    /**
     * Set the value of diametroInformado
     *
     * @return  self
     */ 
    public function setDiametroInformado($diametroInformado)
    {
        $this->diametroInformado = $diametroInformado;

        return $this;
    }

    /**
     * Get the value of cienteObjetoNaoProibido
     */ 
    public function getCienteObjetoNaoProibido()
    {
        return $this->cienteObjetoNaoProibido;
    }

    /**
     * Set the value of cienteObjetoNaoProibido
     *
     * @return  self
     */ 
    public function setCienteObjetoNaoProibido($cienteObjetoNaoProibido)
    {
        $this->cienteObjetoNaoProibido = $cienteObjetoNaoProibido;

        return $this;
    }

    /**
     * Get the value of solicitarColeta
     */ 
    public function getSolicitarColeta()
    {
        return $this->solicitarColeta;
    }

    /**
     * Set the value of solicitarColeta
     *
     * @return  self
     */ 
    public function setSolicitarColeta($solicitarColeta)
    {
        $this->solicitarColeta = $solicitarColeta;

        return $this;
    }

    /**
     * Get the value of logisticaReversa
     */ 
    public function getLogisticaReversa()
    {
        return $this->logisticaReversa;
    }

    /**
     * Set the value of logisticaReversa
     *
     * @return  self
     */ 
    public function setLogisticaReversa($logisticaReversa)
    {
        $this->logisticaReversa = $logisticaReversa;

        return $this;
    }

    /**
     * Get the value of idCorreios
     */ 
    public function getIdCorreios()
    {
        return $this->idCorreios;
    }

    /**
     * Set the value of idCorreios
     *
     * @return  self
     */ 
    public function setIdCorreios($idCorreios)
    {
        $this->idCorreios = $idCorreios;

        return $this;
    }

    /**
     * Get the value of codigoObjeto
     *
     * @return  string
     */ 
    public function getCodigoObjeto()
    {
        return $this->codigoObjeto;
    }

    /**
     * Set the value of codigoObjeto
     *
     * @param  string  $codigoObjeto
     *
     * @return  self
     */ 
    public function setCodigoObjeto($codigoObjeto)
    {
        $this->codigoObjeto = $codigoObjeto;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of dataHoraStatusAtual
     *
     * @return  string
     */ 
    public function getDataHoraStatusAtual()
    {
        return $this->dataHoraStatusAtual;
    }

    /**
     * Set the value of dataHoraStatusAtual
     *
     * @param  string  $dataHoraStatusAtual
     *
     * @return  self
     */ 
    public function setDataHoraStatusAtual($dataHoraStatusAtual)
    {
        $this->dataHoraStatusAtual = $dataHoraStatusAtual;

        return $this;
    }

    /**
     * Get the value of prazoPostagem
     *
     * @return  \DateTime
     */ 
    public function getPrazoPostagem()
    {
        return $this->prazoPostagem;
    }

    /**
     * Set the value of prazoPostagem
     *
     * @param  \DateTime  $prazoPostagem
     *
     * @return  self
     */ 
    public function setPrazoPostagem(\DateTime $prazoPostagem)
    {
        $this->prazoPostagem = $prazoPostagem;

        return $this;
    }

    /**
     * Get the value of statusAtual
     *
     * @return  boolean
     */ 
    public function getStatusAtual()
    {
        return $this->statusAtual;
    }

    /**
     * Set the value of statusAtual
     *
     * @param  boolean  $statusAtual
     *
     * @return  self
     */ 
    public function setStatusAtual($statusAtual)
    {
        $this->statusAtual = $statusAtual;

        return $this;
    }
}