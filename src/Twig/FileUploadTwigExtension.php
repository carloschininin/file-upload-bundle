<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Twig;

use CarlosChininin\FileUpload\Model\FileUpload;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class FileUploadTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $fileUploadDirectory
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('file_upload_webpath', [$this, 'webpath']),
        ];
    }

    public function webpath(?FileUpload $file): ?string
    {
        if (null === $file) {
            return null;
        }

        return $this->fileUploadDirectory.$file->filePath();
    }
}
