<?php

namespace EveryWell\Imagination\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait HasImages
{
    public function setAttribute($key, $value)
    {
        // If a 'set' mutator exists then it has priority
        // If the attribute doesn't exists in the images array then just call the parent
        if ($this->hasSetMutator($key) || (!in_array($key, $this->images) && !array_key_exists($key, $this->images))) {
            parent::setAttribute($key, $value);
        } else {
            $this->setImage($key, $value);
        }
    }

    public function getAttribute($key)
    {
        // If a 'get' mutator exists then it has priority
        // If the attribute doesn't exists in the images array then just call the parent
        if ($this->hasGetMutator($key) || (!in_array($key, $this->images) && !array_key_exists($key, $this->images))
        ) {
            return parent::getAttribute($key);
        } else {
            return $this->getImage($key);
        }
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $objectName = config('imagination.res_name');

        $attributes = parent::attributesToArray();

        foreach ($this->images as $imageKey => $value) {

            if (gettype($value) == 'string') {

                $attributes[$value . '_' . $objectName] = $this->getImageRes($value);
            }
            elseif (gettype($value) == 'array') {

                $attributes[$imageKey . '_' . $objectName] = $this->getImageRes($imageKey);
            }

        }

        return $attributes;
    }

    protected function setImage($imageKey, $value)
    {
        $imagePath = $this->getImagePath($imageKey);

        if (!empty($this->attributes[$imageKey])) {
            $this->deleteImage($imageKey);
        }

        if (empty($value)) {
            $this->attributes[$imageKey] = null;
            return;
        }

        $disk = $this->getImageDisk($imageKey);

        $image = Image::make($value);

        do {
            $imageName = str_random() . '.' . $this->getImageFormat($imageKey);
        } while ($disk->exists($imagePath . '/' . $imageName));

        // TODO: Handle private images
        $disk->put($imagePath . '/' . $imageName, (string) $image->encode($this->getImageFormat($imageKey)), 'public');

        $this->attributes[$imageKey] = $imageName;

        $this->createImages($imageKey, $value);
    }

    protected function getImage($imageKey)
    {
        return $this->getImageRes($imageKey);
    }

    protected function getImageRes($imageKey)
    {
        if (empty($this->attributes[$imageKey])) {
            return null;
        }

        $res = [];

        $disk = $this->getImageDisk($imageKey);

        $dimensions = $this->getImageDimensions($imageKey);

        foreach ($dimensions as $dimension) {

            $imagePath = $this->getImageDimensionPath($imageKey, $dimension);

            $res[$dimension['name']] = $disk->url($imagePath);
        }

        return $res;
    }

    protected function createImages($imageKey, $value)
    {
        $dimensions = collect($this->getImageDimensions($imageKey));

        foreach ($dimensions as $dimension) {

            $this->createImage($imageKey, $value, $dimension);
        }
    }

    protected function createImage($imageKey, $value, $dimension)
    {
        $disk = $this->getImageDisk($imageKey);

        $imagePath = $this->getImageDimensionPath($imageKey, $dimension);

        $scalingMode = $this->getImageScalingMode($imageKey);

        $image = Image::make($value);

        $width = !empty($dimension['width']) ? $dimension['width'] : null;
        $height = !empty($dimension['height']) ? $dimension['height'] : null;

        switch ($scalingMode) {

            case 'fit':
                $image->fit($width, $height);
                break;

            case 'resize':
            default:
                $image->resize($width, $height, function ($constraint) use ($dimension) {
                    $constraint->aspectRatio();
                });
        }

        $disk->put($imagePath, (string) $image->encode($this->getImageFormat($imageKey)), 'public');
    }

    protected function deleteImage($imageKey)
    {
        $disk = $this->getImageDisk($imageKey);

        $fileInfo = $this->getFileInfo($imageKey);

        $dimensions = $this->getImageDimensions($imageKey);

        foreach ($dimensions as $dimension) {

            $imagePath = $this->getImageDimensionPath($imageKey, $dimension);

            if ($disk->exists($imagePath)) {
                $disk->delete($imagePath);
            }
        }

        $originalFile = $this->getImagePath($imageKey) . '/' . $fileInfo['name'] . '.' . $fileInfo['extension'];

        if ($disk->exists($originalFile)) {
            $disk->delete($originalFile);
        }

        $this->attributes[$imageKey] = '';
    }

    protected function getImageDimensionPath($imageKey, $dimension)
    {
        $fileInfo = $this->getFileInfo($imageKey);

        $path = $this->getImagePath($imageKey) . '/' . $fileInfo['name'] . '_' . $dimension['name'] . '';

        $path .= '.' . $fileInfo['extension'];

        return $path;
    }

    protected function getFileInfo($imageKey)
    {
        return [
            'name' => pathinfo($this->attributes[$imageKey], PATHINFO_FILENAME),
            'extension' => pathinfo($this->attributes[$imageKey], PATHINFO_EXTENSION)
        ];
    }

    protected function getImageDimensions($imageKey)
    {
        $dimensions = config('imagination.dimensions');

        if (!empty($this->images[$imageKey]['dimensions'])) {
            $dimensions = $this->images[$imageKey]['dimensions'];
        }

        return $dimensions;
    }

    protected function getImageFormat($imageKey)
    {
        $format = config('imagination.format');

        if (!empty($this->images[$imageKey]['format'])) {
            $format = $this->images[$imageKey]['format'];
        }

        return $format;
    }

    protected function getImageScalingMode($imageKey)
    {
        $scalingMode = config('imagination.scaling_mode');

        if (!empty($this->images[$imageKey]['scaling_mode'])) {
            $scalingMode = $this->images[$imageKey]['scaling_mode'];
        }

        return $scalingMode;
    }

    protected function getImagePath($imageKey)
    {
        $basePath = config('imagination.base_path');

        return $basePath . str_plural(strtolower(class_basename($this))) . '/' . str_plural(strtolower($imageKey));
    }

    protected function getImageDisk($imageKey)
    {
        $disk = config('imagination.disk');

        if (!empty($this->images[$imageKey]['disk'])) {
            $disk = $this->images[$imageKey]['disk'];
        }

        return Storage::disk($disk);
    }
}
