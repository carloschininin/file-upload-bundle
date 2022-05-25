<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    public function __construct(
        private readonly string $publicDirectory,
        private readonly string $fileUploadDirectory
    ) {
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

    public function remove(?string $fileName): void
    {
        if (null === $fileName || '' === trim($fileName)) {
            return;
        }

        $file = $this->getTargetDirectory().$fileName;

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->publicDirectory.$this->fileUploadDirectory;
    }
}
