<?php
return
[
  'timeout' => 300, //miliseconds
  'versions' => [
    // default services
    'default' => [
      'url' => 'http://api.cedarmaps.com',
      
      'headers' => [
        'Accept' => 'application/json, text/plain, */*',
        'Accept-Encoding' => 'gzip, deflate'
      ],

      'services'=>[
      ]
    ],

    // version 1 services
    'v1' => [
      'url' => 'http://api.cedarmaps.com',
      
      'headers' => [
        'Accept' => 'application/json, text/plain, */*',
        'Accept-Encoding' => 'gzip, deflate',
        'Authorization' => 'Bearer aa28ccc5cea0afff90228ff86a831dbd7573bf44',
        //'Accept-Language' => 'en-US,en;q=0.8',
      ],

      'services'=>[
        'geocode'=>[
          'url' => '/geocode/cedarmaps.streets/{title}',
          'type' => 'GET',
          'headers' => [],
          'params' => [],
          'ssl' => false
        ],
        'reverse'=>[
          'url' => '/geocode/cedarmaps.streets/{geo}.json',
          'type' => 'GET',
          'headers' => [],
          'params' => [],
          'ssl' => false
        ],
        'distance'=>[
          'url' => '/distance/cedarmaps.driving/',
          'type' => 'GET',
          'headers' => [],
          'params' => [],
          'ssl' => false
        ],
        'direction'=>[
          'url' => '/direction/cedarmaps.driving/{origin};{destination}',
          'type' => 'GET',
          'headers' => [],
          'params' => [],
          'ssl' => false
        ]
      ]
    ]
  ]
];
