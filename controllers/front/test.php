<?php

use AGTI\Correios\Application\Service\CreateConcilRowsFromFile;
use AGTI\Correios\Application\Service\CriarPrePostagem;
use AGTI\Correios\Application\Service\GeraPdfEtiquetas;
use AGTI\Correios\Application\Service\PrazoNacional;
use AGTI\Correios\Application\Service\PrecoNacional;
use AGTI\Correios\Application\Service\TokenRetriever;
use AGTI\Correios\Entity\AgcorreiosTracking;
use AGTI\Correios\Entity\Orders;
use AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch\AddBatchService;
use AGTI\Correios\Infrastructure\API\Local\Batch\AddBatch\AddBatchServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemService;
use AGTI\Correios\Infrastructure\API\Remote\Autentica\CartaoDePostagem\AutenticaCartaoDePostagemServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Contrato\ContratoService;
use AGTI\Correios\Infrastructure\API\Remote\Contrato\ContratoServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalService;
use AGTI\Correios\Infrastructure\API\Remote\Prazo\Nacional\PrazoNacionalServiceArgs;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalService;
use AGTI\Correios\Infrastructure\API\Remote\Preco\Nacional\PrecoNacionalServiceArgs;
use AGTI\Correios\ValueObject\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

class agcorreiostestModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        // $http = new CurlHttpClient();
        // $url = "https://api.correios.com.br/token/v1/autentica/cartaopostagem";
        // $extraHeaders = [
        //     "Authorization: Basic NDkzNTI1ODgwMDAxMzE6UEZYR2xsSlI2a3Naemp6WHc3ZTNtMUowZFd0djg2UmwxQ2pnRGRhRg==",
        //     "Content-Type: application/json"
        // ];
        // $method = 'POST';
        // $data = [
        //     'numero' => '0078151970'
        // ];

        // $r = $http->request(
        //     $method,
        //     $url,
        //     [
        //         // 'query' => $querystring,
        //         'json' => $data,
        //         'headers' => $extraHeaders,
        //         'timeout' => 30
        //     ]
        // );

        // try {
        //     $resp = $r->getContent();
        //     $info = $r->getInfo();
        // dump($resp, $info);

        // } catch (\Exception $e) {
        //     dump($e);
        // }


        /** @var ContratoService */
        $s = $this->get(ContratoService::class);
        $args = new ContratoServiceArgs;

        /** @var TokenRetriever */
        $sToken = $this->get(TokenRetriever::class);

        /** @var Configuration */
        $config = $this->get(Configuration::class);

        $token = $sToken->exec(
            $config->getUsername(),
            $config->getPassword(),
            $config->getCartaoPostagem()
        );

        $args->setToken($token)
            ->setCnpj("07670088000106")
            ->setNumero($config->getContractNumber());

        $s->exec($args);
        exit();



        //criação das etiquetas
        // /** @var EntityManagerInterface */
        // $em = $this->get('doctrine.orm.entity_manager');

        // /** @var Orders */
        // $order = $em->getRepository(Orders::class)->findOneBy(['id' => 100]);

        // /** @var CriarPrePostagem */
        // $s = $this->get(CriarPrePostagem::class);

        // /** @var TokenRetriever */
        // $sToken = $this->get(TokenRetriever::class);

        // /** @var Configuration */
        // $config = $this->get(Configuration::class);
        // $token = $sToken->exec(
        //     $config->getUsername(),
        //     $config->getPassword(),
        //     $config->getCartaoPostagem()
        // );

        // $s->exec($order, "03220", $token->getToken());
        


        //geração dos pdfs
        /** @var GeraPdfEtiquetas */
        $s = $this->get(GeraPdfEtiquetas::class);

        /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var TokenRetriever */
        $sToken = $this->get(TokenRetriever::class);

        /** @var Configuration */
        $config = $this->get(Configuration::class);
        $token = $sToken->exec(
            $config->getUsername(),
            $config->getPassword(),
            $config->getCartaoPostagem()
        );

        $trackings = $em->getRepository(AgcorreiosTracking::class)->findAll();
        $f = $s->exec($trackings, $token);

        $filename = stream_get_meta_data($f)['uri'];
            
        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="etiquetas.pdf"');
        header('content-Transfer-Encoding:binary');
        header('Accept-Ranges:bytes');
        @readfile($filename);

        fclose($f);
        
        exit();
    }
}