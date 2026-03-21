<?php
namespace AGTI\Correios\Infrastructure\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer
{
    public static function buildSerializer()
    {
        $encoders = [new JsonEncoder()];
        
        $normalizers = [
            new DateTimeNormalizer(),
            new ContratoServiceResponseSuccessNormalizer,
            Normalizer::buildObjectNormalizer(),
            new ArrayDenormalizer
        ];
        $serializer = new SymfonySerializer($normalizers, $encoders);

        return $serializer;
    }
}