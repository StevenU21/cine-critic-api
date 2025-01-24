<?php

namespace App\Services;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver()); 
    }

    public function storeImage(UploadedFile $file, string $name, int $id, string $directory): string
    {
        try {
            $image = $this->readImage($file);
            $imageName = $this->generateImageName($name, $id);
            $imagePath = $this->generateImagePath($directory, $imageName);

            $this->deleteExistingImage($imagePath);
            $webpImage = $this->encodeImageToWebp($image);

            $this->saveImage($imagePath, $webpImage);

            return $imagePath;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error saving the image: ' . $e->getMessage());
        }
    }

    protected function readImage(UploadedFile $file)
    {
        return $this->imageManager->read($file->getRealPath());
    }

    protected function generateImageName(string $name, int $id): string
    {
        return Str::slug($name, '-') . '-' . $id . '.webp';
    }

    protected function generateImagePath(string $directory, string $imageName): string
    {
        return $directory . '/' . $imageName;
    }

    protected function deleteExistingImage(string $imagePath): void
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    protected function encodeImageToWebp($image)
    {
        return $image->toWebp(70);
    }

    protected function saveImage(string $imagePath, $webpImage): void
    {
        Storage::disk('public')->put($imagePath, $webpImage);
    }

    public function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
