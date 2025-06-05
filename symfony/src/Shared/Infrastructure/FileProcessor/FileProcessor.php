<?php

namespace App\Shared\Infrastructure\FileProcessor;

use App\Shared\Domain\Exception\FileBadProcessedException;
use App\Shared\Domain\Exception\FileTooLargeException;
use App\Shared\Domain\Exception\FileTypeNotSupportedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use GdImage;

class FileProcessor
{
    private string $uploadDir = "/appdata/uploads/";

    /**
     * @throws \App\Shared\Domain\Exception\FileTooLargeException
     * @throws \App\Shared\Domain\Exception\FileBadProcessedException
     * @throws \App\Shared\Domain\Exception\FileTypeNotSupportedException
     */
    public function uploadProfileIcon(UploadedFile $imgProfile, string $userId): string
    {
        if ($imgProfile->getSize() > 100 * 1024 * 1024) {
            throw new FileTooLargeException();
        }

        // Verifica tipo MIME
        $this->throwIfIsNotImageType(imgProfile: $imgProfile);

        // Directorios
        $uploadDir = $this->uploadDir . 'profile/' . $userId . '/';
        $outputPath = $uploadDir . $userId . '.webp';

        // Asegura directorio
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Procesa imagen
        $imageResource = $this->createImageResource($imgProfile->getPathname());
        if (!$imageResource) {
            throw new FileBadProcessedException();
        }

        // Recorta a cuadrado centrado
        $cropped = $this->cropSquare($imageResource);

        // Guarda en WebP
        imagewebp($cropped, $outputPath, 80);

        // Libera memoria
        imagedestroy($imageResource);
        imagedestroy($cropped);
        unlink($imgProfile->getPathname());

        return 'profile/' . $userId . '/' . $userId . '.webp';
    }

    public function deleteProfileIcon(string $userId): void
    {
        $filePath = $this->uploadDir . 'profile/' . $userId . '/' . $userId . '.webp';
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * @throws \App\Shared\Domain\Exception\FileTypeNotSupportedException
     */
    private function throwIfIsNotImageType(UploadedFile $imgProfile): void
    {
        if (strpos($imgProfile->getMimeType(), 'image/') !== 0) {
            throw new FileTypeNotSupportedException();
        }
    }

    private function createImageResource(string $path): GdImage|null
    {
        $info = getimagesize($path);
        switch ($info['mime']) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                return null;
        }
    }

    private function cropSquare(GdImage $img): GdImage
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $size = min($width, $height);
        $x = ($width - $size) / 2;
        $y = ($height - $size) / 2;

        $newImg = imagecreatetruecolor($size, $size);
        imagecopyresampled($newImg, $img, 0, 0, $x, $y, $size, $size, $size, $size);
        return $newImg;
    }
}
