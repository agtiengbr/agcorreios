<?php
namespace AGTI\Correios\Application\Service;

use AGTI\Correios\Entity\AgcorreiosConcilRows;

class CreateConcilRowsFromFile
{
    public function exec($filename)
    {
        $ret = [];

        $file = fopen($filename, "r");
        $row = 0;
        $indexes = [];
        while (($data = fgetcsv($file, 10000, ";")) !== FALSE){
            if ($row == 0) {
                foreach ($data as $i=>$title) {
                    $indexes[$title] = $i;
                }
            } else {
                $obj = new AgcorreiosConcilRows;
                $obj->setTrackingNumber($data[$indexes['Numero da Etiqueta']]);

                $rawCost = $data[$indexes['Valor Liquido (R$)']];
                $rawCost = str_replace('R$ ', '', $rawCost);
                $rawCost = str_replace(',', '.', $rawCost);

                $obj->setCost($rawCost);
                $obj->setStatus(0);

                $ret[] = $obj;
            }
            $row++;
        }

        return $ret;
    }
}