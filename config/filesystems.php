<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   =>public_path('src/resource'),
        ],
        'public'  => [
            'driver'     => 'local',
            'root'       => public_path('src/resource'),
            'url'        => env('VIDEO_DOMAIN').'/src/resource/',
            'visibility' => 'public',
        ],
        'uploads' => [
            'driver' => 'local',
            'root'   => public_path('src/upload'),
        ],
        's3'      => [
            'driver' => 's3',
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],
        'qiniu'   => [
            'driver'     => 'qiniu',
            'access_key' => env('QINIU_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),
            'secret_key' => env('QINIU_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),
            'bucket'     => env('QINIU_BUCKET', 'lanmphp'),
            'domain'     => env('QINIU_DOMAIN', 'xxx.clouddn.com'), // or host: https://xxxx.clouddn.com
        ],
    ],

];
