<?php

namespace AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarRotulo;

class CriarRotuloServiceArgs
{
        private $codigosObjeto;


        private $idCorreios;
        private $numeroCartaoPostagem;
        
        private $tipoRotulo = 'P';
        private $formatoRotulo = 'ET';
        private $imprimeRemetente = 'S';
        private $layoutImpressao = 'PADRAO';

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
         * Get the value of codigosObjeto
         */ 
        public function getCodigosObjeto()
        {
                return $this->codigosObjeto;
        }

        /**
         * Set the value of codigosObjeto
         *
         * @return  self
         */ 
        public function setCodigosObjeto($codigosObjeto)
        {
                $this->codigosObjeto = $codigosObjeto;

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
         * Get the value of tipoRotulo
         */ 
        public function getTipoRotulo()
        {
                return $this->tipoRotulo;
        }

        /**
         * Set the value of tipoRotulo
         *
         * @return  self
         */ 
        public function setTipoRotulo($tipoRotulo)
        {
                $this->tipoRotulo = $tipoRotulo;

                return $this;
        }

        /**
         * Get the value of formatoRotulo
         */ 
        public function getFormatoRotulo()
        {
                return $this->formatoRotulo;
        }

        /**
         * Set the value of formatoRotulo
         *
         * @return  self
         */ 
        public function setFormatoRotulo($formatoRotulo)
        {
                $this->formatoRotulo = $formatoRotulo;

                return $this;
        }

        /**
         * Get the value of imprimeRemetente
         */ 
        public function getImprimeRemetente()
        {
                return $this->imprimeRemetente;
        }

        /**
         * Set the value of imprimeRemetente
         *
         * @return  self
         */ 
        public function setImprimeRemetente($imprimeRemetente)
        {
                $this->imprimeRemetente = $imprimeRemetente;

                return $this;
        }

        /**
         * Get the value of layoutImpressao
         */ 
        public function getLayoutImpressao()
        {
                return $this->layoutImpressao;
        }

        /**
         * Set the value of layoutImpressao
         *
         * @return  self
         */ 
        public function setLayoutImpressao($layoutImpressao)
        {
                $this->layoutImpressao = $layoutImpressao;

                return $this;
        }
}