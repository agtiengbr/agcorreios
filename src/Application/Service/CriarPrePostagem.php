<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Application\Exception\BadRequestException;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\OrderCarrier;
use AGTI\Correios\Entity\Orders;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\Contato;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\CriarPrepostagemService;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\CriarPrepostagemServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\DeclaracaoConteudoItem;
use AGTI\Correios\Infrastructure\API\Remote\Prepostagem\CriarPrepostagem\Endereco;
use AGTI\Correios\Infrastructure\Mapping\MappingAdapter;
use AGTI\Correios\ValueObject\Configuration;
use Doctrine\ORM\EntityManagerInterface;

class CriarPrePostagem
{
    use ApiApplicationTrait;

    private $apiService;
    private $configuration;
    private $em;
    private $mappingAdapter;

    private $fromAddress;
    private $toAddress;

    private $fromContact;
    private $toContact;

    public function __construct(CriarPrepostagemService $apiService, Configuration $configuration, EntityManagerInterface $em, MappingAdapter $mappingAdapter)
    {
        $this->apiService = $apiService;
        $this->configuration = $configuration;
        $this->em = $em;
        $this->mappingAdapter = $mappingAdapter;
    }

    /**
     * @var Orders $order Pedido para o qual a pré-postagem será gerada
     * @var string $serviceCode - código dos Correios que será utilizado
     * @var string $token Bearer Token da API
     * @var boolean $coleta Se deverá ser feita a coleta da mercadoria ou se ela será postada na agência
     * @var boolean $reversa Se a etiqueta é de logística reversa ou não
     */
    public function exec(Orders $order, $serviceCode, $token, $coleta, $reversa)
    {
        $args = new CriarPrepostagemServiceArgs;


        $args->setCodigoServico($serviceCode)
            ->setIdCorreios($this->configuration->getUsername());

        

        if ($this->fromContact) {
            $remetente = $this->fromContact;
        } else {
            $remetente = new Contato;
            $remetente->setNome($this->configuration->getSenderData()->getShopName())
                ->setDddCelular($this->configuration->getSenderData()->getDdd())
                ->setCelular($this->configuration->getSenderData()->getCellphone())
                ->setEmail($this->configuration->getSenderData()->getEmail())
                ->setCpfCnpj($this->configuration->getSenderData()->getDocumentNumber());
        }

        if ($this->fromAddress) {
            $remetente->setEndereco($this->fromAddress);
        } else {
            $remetente->setEndereco(
                (new Endereco)
                    ->setLogradouro($this->configuration->getSenderData()->getAddress())
                    ->setCep($this->configuration->getSenderData()->getPostcode())
                    ->setNumero($this->configuration->getSenderData()->getAddressNumber())
                    ->setComplemento($this->configuration->getSenderData()->getOthers())
                    ->setBairro($this->configuration->getSenderData()->getNeighborhood())
                    ->setCidade($this->configuration->getSenderData()->getCity())
                    ->setUf($this->configuration->getSenderData()->getUf())
            );
        }

        $phone = preg_replace("/[^0-9]+/", "", $order->getAddressDelivery()->getPhoneMobile());
        $ddd = substr($phone, 0, 2);
        $phoneNumber = substr($phone, 2);
        
        $data = $this->mappingAdapter->getDocumentFromCustomer($order->getCustomer());
        if ($data['cnpj'] && $data['company_name']) {
            $document = $data['cnpj'];
            $name = $data['company_name'];
        } else {
            $document = $data['cpf'];
            $name = $order->getCustomer()->getFirstname() . ' ' . $order->getCustomer()->getLastname();
        }

        if ($this->toContact) {
            $destinatario = $this->toAddress;
        } else {
            $destinatario = new Contato;
            $destinatario->setNome($name)
            ->setDddCelular($ddd)
            ->setCelular($phoneNumber)
            ->setEmail($order->getCustomer()->getEmail())
            ->setCpfCnpj($document);
        }
    
        if ($this->toAddress){
            $destinatario->setEndereco($this->toAddress);
        } else {
            $destinatario->setEndereco(
                (new Endereco)
                    ->setLogradouro($order->getAddressDelivery()->getAddress1())
                    ->setCep(preg_replace("/[^0-9]+/", "", $order->getAddressDelivery()->getPostcode()))
                    ->setNumero($this->mappingAdapter->getNumberFromAddress($order->getAddressDelivery()))
                    ->setComplemento($order->getAddressDelivery()->getOther())
                    ->setBairro($order->getAddressDelivery()->getAddress2())
                    ->setCidade($order->getAddressDelivery()->getCity())
                    ->setUf($order->getAddressDelivery()->getState()->getIsoCode())
            );
        };

        if (!$reversa) {    
            $args->setRemetente($remetente)
                ->setDestinatario($destinatario);
        } else {
            $args->setDestinatario($remetente)
                ->setRemetente($destinatario);
        }
        
        $args->setNumeroNotaFiscal("")
            ->setNumeroCartaoPostagem($this->configuration->getCartaoPostagem())
            ->setCodigoFormatoObjetoInformado(2)
            ->setCienteObjetoNaoProibido(1)
            ->setSolicitarColeta($coleta ? 'S' : 'N')
            ->setLogisticaReversa($reversa ? 'S' : 'N');


        $declaracao = [];
        $weight = 0;

        $volume = 0;
        foreach ($order->getProducts() as $product) {
            $declaracao[] = (new DeclaracaoConteudoItem)
                ->setConteudo($product->getProductName())
                ->setQuantidade($product->getQty())
                ->setValor($product->getPrice());

            $weight += $product->getProductWeight();
            $volume += $product->getProduct()->getWidth() * $product->getProduct()->getHeight() * $product->getProduct()->getDepth();
        }
        
      
        //converte o peso para gramas
        if (\Configuration::get('PS_WEIGHT_UNIT') == 'kg') {
            $weight *= 1000;
        }


        $args->setItensDeclaracaoConteudo($declaracao)
            ->setPesoInformado(max(1, $weight))
            ->setAlturaInformada(\Tools::ps_round(max(1, pow($volume, 1/3))), 4)
            ->setLarguraInformada(\Tools::ps_round(max(1, pow($volume, 1/3))), 4)
            ->setComprimentoInformado(\Tools::ps_round(max(1, pow($volume, 1/3))), 4);

        $this->validateTracking($args);

        $r = $this->apiService->exec($args, $token);
        
        try {
            $this->postApiRequest($this->apiService->getRequest(), $this->em);
        } catch (BadRequestException $e) {
            $e->setApiMessage(implode(";", $r->getMsgs()));
            throw $e;
        }

        $tracking = new AgcorreiosTracking;
        $tracking->setIdRemote($r->getId())
            ->setOrder($order)
            ->setTrackingCode($r->getCodigoObjeto())
            ->setServiceCode($r->getCodigoServico())
            ->setSolicitarColeta($r->getSolicitarColeta() == 'S')
            ->setLogisticaReversa($r->getLogisticaReversa() == 'S')
            ->setPrazoPostagem($r->getPrazoPostagem())
            ->setStatusAtual($r->getStatusAtual());

        $this->em->persist($tracking);

        $oc = $this->em->getRepository(OrderCarrier::class)->findOneBy(['order' => $order]);
        $oc->setTrackingNumber($r->getCodigoObjeto());

        $this->em->flush();
        return $tracking;
    }

    public function setFromAddress(Endereco $address)
    {
        $this->fromAddress = $address;
    }

    public function setToAddress(Endereco $address)
    {
        $this->toAddress = $address;
    }


    /**
     * Get the value of toContact
     */ 
    public function getToContact()
    {
        return $this->toContact;
    }

    /**
     * Set the value of toContact
     *
     * @return  self
     */ 
    public function setToContact($toContact)
    {
        $this->toContact = $toContact;

        return $this;
    }

    /**
     * Get the value of fromContact
     */ 
    public function getFromContact()
    {
        return $this->fromContact;
    }

    /**
     * Set the value of fromContact
     *
     * @return  self
     */ 
    public function setFromContact($fromContact)
    {
        $this->fromContact = $fromContact;

        return $this;
    }

    private function validateTracking(CriarPrepostagemServiceArgs $args)
    {
        $remetente = $args->getRemetente();
        try {
            $this->validateContact($remetente);
        } catch (\Exception $e) {
            throw new \Exception("Erro validando o remetente da pré-postagem: {$e->getMessage()}.");
        }

        $destinatario = $args->getDestinatario();
        try {
            $this->validateContact($destinatario);
        } catch (\Exception $e) {
            throw new \Exception("Erro validando o destinatário da pré-postagem: {$e->getMessage()}.");
        }

    }

    private function validateContact(Contato $contato)
    {
        if ($contato->getNome() == '') {
            throw new \Exception("O nome do contato é obrigatório.");
        }

        $endereco = $contato->getEndereco();
        if ($endereco->getLogradouro() == '') {
            throw new \Exception("Logradouro não informado.");
        }

        if (!preg_match("/^[0-9]{8}$/", $endereco->getCep())) {
            throw new \Exception("CEP inválido. Deve conter exatamente 8 dígitos, e nenhum caractere não numérico.");
        }

        if (strlen($contato->getDddTelefone()) > 0 && !preg_match("/^[0-9]{2}$/", $contato->getDddTelefone())) {
            throw new \Exception("DDD do celular inválido. Deve conter exatamente 2 dígitos.");
        }

        if (strlen($contato->getDddCelular()) > 0 && !preg_match("/^[0-9]{2}$/", $contato->getDddCelular())) {
            throw new \Exception("DDD do telefone inválido. Deve conter exatamente 2 dígitos.");
        }

        
        if (strlen($contato->getTelefone()) > 0 && !preg_match("/^[0-9]{8}$/", $contato->getDddTelefone())) {
            throw new \Exception("Número de telefone inválido. Deve conter exatamente 8 dígitos.");
        }

        if (strlen($contato->getCelular()) > 0 && !preg_match("/^[0-9]{8,9}$/", $contato->getCelular())) {
            throw new \Exception("Número do telefone celular inválido. Deve conter de 8 a 9 dígitos.");
        }
    }
}
