# Imagination

A Laravel package that allows to handle images on your Eloquent models seamlessly.

## Installation

To install this package just a few steps are needed

### Composer

Pull this package in through Composer using the following command inside your terminal:

```bash
composer require everywell/imagination
```

### Service Provider

Add the package to your application service providers in `config/app.php` file.

```php
'providers' => [
    
    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    
    /**
     * Third Party Service Providers...
     */
    EveryWell\Imagination\ImaginationServiceProvider::class,

],
```

### Config File

Publish the package config file to your aplication running the following command inside your terminal:

    php artisan vendor:publish --provider="EveryWell\Imagination\ImaginationServiceProvider"

### Trait and Contract

Include `HasImages` trait and also implement `HasImages` contract inside your model class.

```php
use EveryWell\Imagination\Traits\HasImages;
use EveryWell\Imagination\Contracts\HasImages as HasImagesContract;

class News extends Model implements HasImagesContract
{
    use HasImages;
```

### Images attribute

Add an `images` array attribute to your model containing the fields that should be handled as images.

```php
use EveryWell\Imagination\Traits\HasImages;
use EveryWell\Imagination\Contracts\HasImages as HasImagesContract;

class News extends Model implements HasImagesContract
{
    use HasImages;
    
    protected $fillable = [
        'title',
        'text',
        'banner',
        'image'
    ];
        
    protected $images = [
        'banner',
        'image' => [
            'discard_original' => true, // Optional: if true, doesn't save the original version of the image
            'dimensions' => [           // Optional: specifies different dimensions than the default config
                [
                    'name' => 'thumb',
                    'width' => 150
                ],
                [
                    'name' => 'small',
                    'width' => 250
                ],
                [
                    'name' => 'regular',
                    'width' => 600
                ]
            ]
        ]
    ];
```

And that's it!
