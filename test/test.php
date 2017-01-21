<?php
require 'vendor/autoload.php';



$geocode = (new Cedar\Cedar('v1'))
    ->load('geocode')
    ->setParamByKey('title', 'ونک');
//
//$reverse = (new Cedar\Cedar('v1'))
//    ->load('reverse')
//    ->setParamByKey('geo', '35.71648,51.40897');

$direction = (new Cedar\Cedar('v1'))
    ->load('direction')
    ->setParamByKey('origin', '35.76433,51.36562')
    ->setParamByKey('destination', '35.76331,51.36532');


//dd($geocode->getJson());
//dd($reverse->getJson() );
dd( $direction->getJson());
