<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ResizeImage implements ShouldQueue
{
    use Queueable;

    public $image_path;

    /**
     * Create a new job instance.
     */
    public function __construct($image_path)
    {
        $this->image_path = $image_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         //uso de intervention para redimensionar Imagen Cargada
         $manager = new ImageManager(new Driver());
         $image = $manager->read(storage_path('app/public/' . $this->image_path['image_path']));
         $image->scale(1200, null, function ($constraint) {
             $constraint->aspectRatio();
         });
         $image->save(storage_path('app/public/' . $$this->image_path['image_path']), null, 'jpg');

    }
}
