<?php

namespace Kopay\NotificationBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Kopay\NotificationBundle\Entity\NotificationRecipient;

class NotificationMetadataListener
{
    /**
     * @var string
     */
    private $userClass;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (NotificationRecipient::class !== $classMetadata->getName()) {
            return;
        }

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy()
        ;

        $classMetadata->mapManyToOne([
            'fieldName' => 'recipient',
            'targetEntity' => $this->userClass,
            'joinColumn' => [
                'name'                  => 'recipient_id',
                'referencedColumnName'  => $namingStrategy->referenceColumnName(),
            ]
        ]);
    }
}