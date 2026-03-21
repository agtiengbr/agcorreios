<?php

namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Application\Exception\BadRequestException;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\OrderCarrier;
use AGTI\Correios\Entity\Orders;
use AGTI\Correios\Entity\Customer;
use AGTI\Correios\Entity\Address;
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

    public function __construct(CriarPrepostagemService $apiService, Configuration $configuration, EntityManagerInterface $em, MappingAdapter $mappingAdapter)
    {
        $this->apiService = $apiService;
        $this->configuration = $configuration;
        $this->em = $em;
        $this->mappingAdapter = $mappingAdapter;
    }

    /**
     * @param Customer $customer Cliente associado à etiqueta
     * @param Address $customerAddress - endereço do cliente
     * @param AgcorreiosTracking[] $products - itens que serão inclusos na etiqueta
     * @param string $serviceCode - código dos Correios que será utilizado
     * @param Product $token Bearer Token da API
     * @param boolean $coleta Se deverá ser feita a coleta da mercadoria ou se ela será postada na agência
     * @param boolean $reversa Se a etiqueta é de logística reversa ou não
     * @param Orders $pedido que será associado à etiqueta
     */
    public function exec(Customer $customer, Address $customerAddress, $products, $serviceCode, $token, $coleta, $reversa, Orders $order)
    {
        $args = new CriarPrepostagemServiceArgs;


        $args->setCodigoServico($serviceCode)
            ->setIdCorreios($this->configuration->getUsername());

        

        $remetente = new Contato;
        $remetente->setNome($this->configuration->getSenderData()->getShopName())
            ->setDddCelular($this->configuration->getSenderData()->getDdd())
            ->setCelular($this->configuration->getSenderData()->getCellphone())
            ->setEmail($this->configuration->getSenderData()->getEmail())
            ->setCpfCnpj($this->configuration->getSenderData()->getDocumentNumber())
            ->setEndereco(
                (new Endereco)
                    ->setLogradouro($this->configuration->getSenderData()->getAddress())
                    ->setCep($this->configuration->getSenderData()->getPostcode())
                    ->setNumero($this->configuration->getSenderData()->getAddressNumber())
                    ->setComplemento($this->configuration->getSenderData()->getOthers())
                    ->setBairro($this->configuration->getSenderData()->getNeighborhood())
                    ->setCidade($this->configuration->getSenderData()->getCity())
                    ->setUf($this->configuration->getSenderData()->getUf())
        );

        $phone = preg_replace("/[^0-9]+/", "", $customerAddress->getPhoneMobile());
        $ddd = substr($phone, 0, 2);
        $phoneNumber = substr($phone, 2);
        
        $data = $this->mappingAdapter->getDocumentFromCustomer($customer);
        if ($data['cnpj'] && $data['company_name']) {
            $document = $data['cnpj'];
            $name = $data['company_name'];
        } else {
            $document = $data['cpf'];
            $name = $customer->getFirstname() . ' ' . $customer->getLastname();
        }

        $destinatario = new Contato;
        $destinatario->setNome($name)
        ->setDddCelular($ddd)
        ->setCelular($phoneNumber)
        ->setEmail($customer->getEmail())
        ->setCpfCnpj($document)
        ->setEndereco(
            (new Endereco)
                ->setLogradouro($customerAddress->getAddress1())
                ->setCep(preg_replace("/[^0-9]+/", "", $customerAddress->getPostcode()))
                ->setNumero($this->mappingAdapter->getNumberFromAddress($customerAddress))
                ->setComplemento($customerAddress->getOther())
                ->setBairro($customerAddress->getAddress2())
                ->setCidade($customerAddress->getCity())
                ->setUf($customerAddress->getState()->getIsoCode())
        );

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
        foreach ($products as $product) {
            $declaracao[] = (new DeclaracaoConteudoItem)
                ->setConteudo($product->getName())
                ->setQuantidade($product->getQty())
                ->setValor($product->getUnitPrice());

            $weight += $product->getWeight();
            $volume += $product->getWidth() * $product->getHeight() * $product->getDepth();
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
            ->setLogisticaReversa($r->getLogisticaReversa() == 'S');

        $this->em->persist($tracking);

        $oc = $this->em->getRepository(OrderCarrier::class)->findOneBy(['order' => $order]);
        $oc->setTrackingNumber($r->getCodigoObjeto());

        $this->em->flush();

    }
}