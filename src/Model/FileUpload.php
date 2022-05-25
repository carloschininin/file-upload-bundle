<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $secure =null;
    private ?string $path = null;
    private ?UploadedFile $file = null;
    private ?string $pathTemp = null;

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function secure(): ?string
    {
        return $this->secure;
    }

    public function setSecure(?string $secure): void
    {
        $this->secure = $secure;
    }

    public function path(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function file(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        if (null !== $file) {
            $this->setName($file->getClientOriginalName());
            $this->file = $file;
        }
    }

    public function filePath(): ?string
    {
        if (null === $this->secure()) {
            return null;
        }

        return $this->path().'/'.$this->secure();
    }

    public function pathTemp(): ?string
    {
        return $this->pathTemp;
    }

    public function setPathTemp(?string $pathTemp): void
    {
        $this->pathTemp = $pathTemp;
    }
}
