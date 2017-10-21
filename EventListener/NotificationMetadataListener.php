<?php

namespace Kopay\NotificationBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Kopay\NotificationBundle\Entity\Notification;

class NotificationMetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
//        return;
//        $classMetadata = $eventArgs->getClassMetadata();
//
//        if (Notification::class !== $classMetadata->getName()) {
//            return;
//        }
//
//        $classMetadata->mapOneToMany([
//            'fieldName' => 'recipiens',
//            'targetEntity' => '',
//
//        ]);
    }
}