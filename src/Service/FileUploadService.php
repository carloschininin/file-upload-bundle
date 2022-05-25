<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    private string $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $secure = sha1(uniqid((string) mt_rand(), true)).'.'.$extension;

        try {
            $file->move($this->getTargetDirectory(), $secure);
        } catch (FileException) {
        }

        return $secure;
    }

    public function remove(?string $nombre): void
    {
        if (null === $nombre || '' === trim($nombre)) {
            return;
        }

        $file = $this->getTargetDirectory().$nombre;

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
