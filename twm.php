#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

require_once('src/ThreeWayMerge.php');

use Relaxed\Merge\ThreeWayMerge\ThreeWayMerge;

$original = [
    'title' => 'abc',
    'body' => "lorem ipsum",
];

$local = [
    'title' => 'abc',
    'body' => 'dolor',
];

$remote = [
    'title' => '123',
    'body' => 'lorem ipsum',
];

$merge = new ThreeWayMerge();
$merged = $merge->performMerge($original, $local, $remote);
