<?php

declare(strict_types=1);

namespace CarlosChininin\FileUpload\EventSubscriber;

use CarlosChininin\FileUpload\Model\FileUpload;
use CarlosChininin\FileUpload\Service\FileUploadService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploadEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly FileUploadService $FileUploadService)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof FileUpload) {
            return;
        }

        $file = $entity->file();

        if ($file instanceof UploadedFile) {
            $previusPath = $entity->filePath();
            $secure = $this->FileUploadService->upload($file);
            $entity->setSecure($secure);
            $this->FileUploadService->remove($previusPath);
        }
    }

    private function removeFile($entity): void
    {
        if (!$entity instanceof FileUpload) {
            return;
        }

        $nombre = $entity->filePath();
        $this->FileUploadService->remove($nombre);
    }
}
