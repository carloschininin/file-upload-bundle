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

    public function upload(UploadedFile $file, ?string $folder = null): string
    {
        $extension = $file->getClientOriginalExtension();
        $secure = sha1(uniqid((string) mt_rand(), true)).'.'.$extension;

        try {
            $folder = $this->getFolder($folder);
            $file->move($this->getTargetDirectory().$folder, $secure);
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

    public function getFolder(?string $folder): string
    {
        if (null === $folder || '' === trim($folder)) {
            return '';
        }

        if (!str_starts_with('/', $folder)) {
            return '/'.$folder;
        }

        return $folder;
    }

    public function getTargetDirectory(): string
    {
        return $this->publicDirectory.$this->fileUploadDirectory;
    }
}
