<?php

namespace EveryWell\Imagionation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\Facades\Image;

class CreateImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model that has the image.
     *
     * @var Object
     */
    protected $model;

    /**
     * The image key (column name).
     *
     * @var String
     */
    protected $imageKey;

    /**
     * The image dimensions.
     *
     * @var array
     */
    protected $dimension;

    /**
     * The image stream.
     *
     * @var string
     */
    protected $image;

    /**
     * Create a new job instance.
     *
     * @param $model        Object  The model that has the image
     * @param $imageKey     String  The image key (column name)
     * @param $dimension    array   The image dimensions
     * @param $image        string  The image stream
     */
    public function __construct($model, $imageKey, $dimension, $image)
    {
        $this->model = $model;
        $this->imageKey = $imageKey;
        $this->dimension = $dimension;
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->model->getImageDisk($this->imageKey);

        $imagePath = $this->model->getImageDimensionPath($this->imageKey, $this->dimension);

        $scalingMode = $this->model->getImageScalingMode($this->imageKey);

        $image = Image::make($this->image);

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

        $disk->put($imagePath, (string) $image->encode($this->model->getImageFormat($this->imageKey)), 'public');
    }
}