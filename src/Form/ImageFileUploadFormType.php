<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Form;

class ImageFileUploadFormType extends FileUploadFormType
{
    public const NAME = 'image_file_upload';

    public function getBlockPrefix(): string
    {
        return self::NAME;
    }
}
