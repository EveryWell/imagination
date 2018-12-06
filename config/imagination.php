<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Images disk
    |--------------------------------------------------------------------------
    |
    | The storage disk used by default for the images.
    |
    */

    'disk' => env('IMAGINATION_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Base path
    |--------------------------------------------------------------------------
    |
    | The storage base path (namespace) used to allow multiple environments
    | in the same disk.
    |
    */

    'base_path' => env('IMAGINATION_BASE_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Resources object name
    |--------------------------------------------------------------------------
    |
    | The reference appended to the attribute name to identify the image object
    | containing all the image versions.
    |
    */

    'res_name' => 'res',

    /*
    |--------------------------------------------------------------------------
    | Scaling mode
    |--------------------------------------------------------------------------
    |
    | The scaling mode that should be used when uploading a new image.
    | Accepted values: 'fit', 'resize'.
    |
    */

    'scaling_mode' => 'resize',

    /*
    |--------------------------------------------------------------------------
    | Image format
    |--------------------------------------------------------------------------
    |
    | The format that should be used to persist the images into the storage.
    | Accepted values: 'jpg', 'png', 'bmp', 'gif', 'original'.
    |
    */

    'format' => 'jpg',

    /*
    |--------------------------------------------------------------------------
    | Dimensions
    |--------------------------------------------------------------------------
    |
    | The versions that the application should automatically create when
    | uploading a new image.
    |
    */
    
    'dimensions' => [
        [
            'name' => 'xsmall',
            'width' => 64
        ],
        [
            'name' => 'small',
            'width' => 144
        ],
        [
            'name' => 'medium',
            'width' => 800
        ],
        [
            'name' => 'large',
            'width' => 1024
        ],
        [
            'name' => 'xlarge',
            'width' => 1920
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Store original
    |--------------------------------------------------------------------------
    |
    | Do ya wanna keep dat fat cow?
    |
    */

    'store_original' => false
];
