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
    | Res name
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
        'xsmall' => [
            'width' => 64
        ],
        'small' => [
            'width' => 144
        ],
        'medium' => [
            'width' => 800
        ],
        'large' => [
            'width' => 1024
        ],
        'xlarge' => [
            'width' => 1920
        ]
    ]
];
