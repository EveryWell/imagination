<?php

namespace EveryWell\Imagination\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait HasImages
{
    /**
     * The default images dimensions.
     *
     * @var array
     */
    protected $imagesDimensions = [
        'xsmall' => [
            'width' => 64,
            'height' => 'auto'
        ],
        'small' => [
            'width' => 144,
            'height' => 'auto'
        ],
        'medium' => [
            'width' => 800,
            'height' => 'auto'
        ],
        'large' => [
            'width' => 1024,
            'height' => 'auto'
        ],
        'xlarge' => [
            'width' => 1920,
            'height' => 'auto'
        ]
    ];

    public function setAttribute($key, $value)
    {
        // If a 'set' mutator exists then it has priority
        // If the attribute doesn't exists in the images array then just call the parent
        if ($this->hasSetMutator($key) || !array_key_exists($key, $this->images)) {
            parent::setAttribute($key, $value);
        } else {
            $this->setImage($key, $value);
        }
    }

    public function getAttribute($key)
    {
        // If a 'get' mutator exists then it has priority
        // If the attribute doesn't exists in the images array then just call the parent
        if ($this->hasGetMutator($key) || !array_key_exists($key, $this->images)) {
            return parent::getAttribute($key);
        } else {
            return $this->getImage($key);
        }
    }

    protected function setImage($imageKey, $value)
    {
        $imagePath = $this->getImagePath($imageKey);

        // TODO: delete all images versions (wildcard?)
//        if (!empty($this->attributes[$imageKey]) && Storage::exists($imagePath . '/' . $this->attributes[$imageKey])) {
//            foreach ($this->images[$imageKey]['dimensions'] as $dimension) {
//                Storage::delete($imagePath . $this->attributes['image']);
//            }
//        }

        /**
         * Reset key value (If a new image is being uploaded it will be persisted to storage in the next step)
         */
        $this->attributes[$imageKey] = '';

        /**
         * Try to create an image from the given value,
         * if the image is readable then it is persisted to storage
         */
        try {
            $image = Image::make($value);

            do {
                $imageName = str_random() . '.jpg';
            } while (Storage::exists($imagePath . '/' . $imageName));

            // TODO: Handle private images
            Storage::put($imagePath . '/' . $imageName, $image->encode('jpg'), 'public');

            $this->attributes[$imageKey] = $imageName;

        } catch(Exception $e) {

            die('The image is not valid.');
        }
    }

    protected function getImage($imageKey, $size = 'small')
    {
        $imagePath = $this->getImagePath($imageKey);

        return Storage::url($imagePath . '/' . $this->attributes[$imageKey]);
    }

    protected function getImagePath($imageKey)
    {
        $basePath = config('imagination')->get('base_path');

        dd($basePath);

        return str_plural(strtolower(class_basename($this))) . '/' . str_plural(strtolower($imageKey));
    }
}
