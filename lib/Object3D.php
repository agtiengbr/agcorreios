<?php

class Object3D
{
    //largura
    public $width;
    //altura
    public $height;
    //profundidade
    public $depth;

    public function __construct($width, $height, $depth)
    {
        $this->width = $width;
        $this->height = $height;
        $this->depth = $depth;
    }

    //verifica se este objeto pode conter um array de objetos
    public function fit(array $objects)
    {
        //dimensões do próprio objeto (objeto pai)
        $own_dimensions = array($this->width, $this->height, $this->depth);
        sort($own_dimensions);

        foreach ($objects as $object) {
            //se a menor dimensão do objeto atual for negativa, não é possível inserir os objetos requisitados
            if ($own_dimensions[0] < 0) {
                return false;
            }

            // printf("Dimensões próprias: %s", $this->printDimensions($own_dimensions));
            //dimensões do objeto filho que será inserido dentro do objeto pai
            $object_dimensions = array($object->width, $object->height, $object->depth);
            // printf("Dimensões do filho: %s", $this->printDimensions($object_dimensions));
            sort($object_dimensions);

            //insere o objeto filho no objeto pai
            $own_dimensions = $this->subtractDimensions($own_dimensions, $object_dimensions);
            sort($own_dimensions);
        }

        // printf("Dimensões próprias: %s", $this->printDimensions($own_dimensions));
        //se a menor dimensão do objeto atual for negativa, não é possível inserir os objetos requisitados
        return $own_dimensions[0] >= 0;
    }

    protected function subtractDimensions(array $dim1, array $dim2)
    {
        return array(
            $dim1[0] - $dim2[0],
            $dim1[1] - $dim2[1],
            $dim1[2] - $dim2[2]
        );
    }

    protected function printDimensions(array $dim)
    {
        return sprintf("%d \t%d \t%d\n", $dim[0], $dim[1], $dim[2]);
    }
}
