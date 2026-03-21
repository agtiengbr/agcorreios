<?php
namespace AGTI\Correios\Infrastructure\Serializer;

use AGTI\Correios\Infrastructure\API\Remote\Contrato\ContratoServiceResponseSuccess;
use AGTI\Correios\Infrastructure\API\Remote\DataModel\ShippingService;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ContratoServiceResponseSuccessNormalizer implements DenormalizerInterface
{

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $normalizer = Normalizer::buildObjectNormalizer();
        $serializer = Serializer::buildSerializer();

        $normalizer->setSerializer($serializer);
        $dt = $normalizer->denormalize($data, $type, $format, $context);

        $itemsObj = [];
        if (is_array($dt->getItens())) {
            foreach ($dt->getItens() as $item) {
                $itemsObj[] = $serializer->denormalize($item, ShippingService::class, $format, $context);
            }
        }
        
        $dt->setItens($itemsObj);

        return $dt;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ContratoServiceResponseSuccess::class;
    }
    
}