<?php

require_once 'lib/Object3D.php';

$tests = array(
    //0
    array(
        'box' => array(
            'width' => 10,
            'height' => 7,
            'depth' => 4
        ),
        'childrens' => array(
            array(
                'width' => 1,
                'height' => 4,
                'depth' => 3
           )
        ),
        'result' => true
    ),
    //1
    array(
        'box' => array(
            'width' => 10,
            'height' => 8,
            'depth' => 5
        ),
        'childrens' => array(
            array(
                'width' => 2,
                'height' => 7,
                'depth' => 3
            ),
            array(
                'width' => 1,
                'height' => 4,
                'depth' => 2
            )
        ),
        'result' => true
    ),
    //2
    array(
        'box' => array(
            'width' => 10,
            'height' => 7,
            'depth' => 4
        ),
        'childrens' => array(
            array(
                'width' => 1,
                'height' => 2,
                'depth' => 1
            ),
            array(
                'width' => 1,
                'height' => 2,
                'depth' => 1
            ),
            array(
                'width' => 1,
                'height' => 1,
                'depth' => 2
            ),
            array(
                'width' => 2,
                'height' => 1,
                'depth' => 1
            )
        ),
        'result' => true
    ),
    //3
    array(
        'box' => array(
            'width' => 10,
            'height' => 7,
            'depth' => 4
        ),
        'childrens' => array(
            array(
                'width' => 1,
                'height' => 2,
                'depth' => 1
            ),
            array(
                'width' => 1,
                'height' => 2,
                'depth' => 1
            ),
            array(
                'width' => 1,
                'height' => 1,
                'depth' => 2
            ),
            array(
                'width' => 2,
                'height' => 1,
                'depth' => 1
            )
            ,
            array(
                'width' => 1,
                'height' => 1,
                'depth' => 1
            )
            ,
            array(
                'width' => 3,
                'height' => 1,
                'depth' => 1
            )
        ),
        'result' => false
    )
);

echo "Starting tests\n";

foreach ($tests as $i => $test) {
    echo sprintf("Teste %d\n:", $i);

    $parent_box = new Object3D($width = $test['box']['width'], $test['box']['height'], $test['box']['depth']);

    $children_boxes = array();
    foreach ($test['childrens'] as $children) {
        $children_boxes[] = new Object3D($width = $children['width'], $height = $children['height'], $depth = $children['depth']);
    }

    echo $parent_box->fit($children_boxes) == $test['result'] ? 'Ok' : 'Error';
    echo "\n";
}
