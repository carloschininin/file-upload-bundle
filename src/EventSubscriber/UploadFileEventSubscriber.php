<?php

declare(strict_types=1);

namespace CarlosChininin\UploadFile\EventSubscriber;

use CarlosChininin\UploadFile\Model\UploadFile;
use CarlosChininin\UploadFile\Service\UploadFileService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadFileEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UploadFileService $uploadFileService)
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

        if ($entity instanceof UploadFile) {
            $entity->setPathTemp($entity->path());
        }

        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof UploadFile) {
            return;
        }

        $file = $entity->file();

        if ($file instanceof UploadedFile) {
            $secure = $this->uploadFileService->upload($file);
            $entity->setSecure($secure);
            $this->uploadFileService->remove($entity->pathTemp());
        }
    }

    private function removeFile($entity): void
    {
        if (!$entity instanceof UploadFile) {
            return;
        }

        $nombre = $entity->path();
        $this->uploadFileService->remove($nombre);
    }
}
