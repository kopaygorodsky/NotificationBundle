<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

abstract class Notification implements NotificationMessageInterface
{
    /**
     * @var Uuid
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $recipientsItems;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $seenAt;

    public function __construct(string $title, string $message, bool $visible, array $recipients)
    {
        $this->id              = Uuid::uuid4();
        $this->recipientsItems = new ArrayCollection();
        $this->createdAt       = new \DateTime();
        $this->title           = $title;
        $this->message         = $message;
        $this->visible         = $visible;

        if (empty($recipients)) {
            throw new \InvalidArgumentException('You have to add at least one receiver');
        }

        array_walk($recipients, function ($recipient) {
            $this->recipientsItems->add(new NotificationRecipient($this, $recipient));
        });
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection|NotificationRecipient[]
     */
    public function getRecipientsItems(): Collection
    {
        return $this->recipientsItems;
    }

    /**
     * @return string
     */
    public function getMessage(): ? string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getTitle(): ? string
    {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function seen(): void
    {
        $this->seenAt = new \DateTime();
    }
}
