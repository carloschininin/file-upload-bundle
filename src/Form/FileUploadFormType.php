<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\Form;

use CarlosChininin\FileUpload\Model\FileUpload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileUploadFormType extends AbstractType
{
    public const NAME = 'file_upload';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => false,
                'constraints' => $options['constraints'],
            ])
            ->add('folder', HiddenType::class, [
                'empty_data' => $options['folder'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileUpload::class,
            'folder' => null,
            'constraints' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return self::NAME;
    }
}
