<?php
return
[
  'versions' => [
    // default services
    'default' => [
      'url' => 'http://map.namaa.ir',
      
      'headers' => [
        'Accept' => 'application/json, text/plain, */*',
        'Accept-Encoding' => 'gzip, deflate, sdch',
        'Accept-Language' => 'en-US,en;q=0.8',
      ],

      'services'=>[
        'hash'=>[
          'url' => 'hash/',
          'type' => 'GET',
          'headers' => [
            'Accept' => 'image/webp,image/*,*/*;q=0.8'
          ],
          'params' => [],
          'ssl' => false,
        ]
      ]
    ],

    // version 1 services
    'v1' => [
      'url' => 'http://api.cedarmaps.com',
      
      'headers' => [
        //'Accept' => 'application/json, text/plain, */*',
        //'Accept-Encoding' => 'gzip, deflate, sdch',
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