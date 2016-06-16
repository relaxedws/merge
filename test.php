<?php

namespace testing;

require __DIR__ ."/src/ThreeWayMerge.php";


use Relaxed\Merge\ThreeWayMerge\ThreeWayMerge;

$original = [
    'keyA' => [
        0 => [
            'keyB' => 'This is honey
            and who cares',
            'keyC' => 'This is however, not apple',
        ],
        1 => [
            'keyB' => 'This is milk',
            'keyC' => 'This is mango',
        ],
        2 => 'a little sugar',
    ]
];

$local = [
    'keyA' => [
        0 => [
            'keyB' =>  'This is honey',
            'keyC' => 'This is however, not apple',
        ],
        2 => 'a little sugar',
        1 => [
            'keyB' => 'This is milky milky',
            'keyC' => 'This is mango',
        ],
    ]
];

$remote = [
    'keyA' => [
        0 => [
            'keyB' => 'This is honeya
            and who cares
            i dont',
            'keyC' => 'This is apple',
        ],
        1 => [
            'keyB' => 'This is milk',
            'keyC' => 'This is changed because of remote',
        ],
        2 => 'a little new something',
    ]
];

$multiline = new ThreeWayMerge();
$new_arr = $multiline->performMerge($original, $local, $remote);
print_r($new_arr);