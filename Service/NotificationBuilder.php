<?php
  declare(strict_types = 1);

  namespace Kopay\NotificationBundle\Service;

  use Kopay\NotificationBundle\Entity\NotificationEmail;
  use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
  use Kopay\NotificationBundle\Entity\NotificationPush;

  class NotificationBuilder
  {
    /**
     * @var array
     */
    private $configuration;

    protected const NOTIFICATION_EMAIL = 'notify_email';

    protected const NOTIFICATION_PUSH  = 'notify_push';

    /**
     * @var NotificationMessageInterface
     */
    protected $notification = null;

    /**
     * NotificationManager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
      $this->configuration = $config;
    }

    /**
     * @param string $type
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function create(string $type): ?NotificationBuilder
    {
      if ($type !== self::NOTIFICATION_EMAIL && $type !== self::NOTIFICATION_EMAIL) {
        throw new \InvalidArgumentException('Unknown type of notification.');
      }

      switch ($type) {
        case self::NOTIFICATION_EMAIL:
          $this->notification = new NotificationEmail();
          break;
        case self::NOTIFICATION_PUSH:
          $this->notification = new NotificationPush();
          break;
        default:
          throw new \InvalidArgumentException(sprintf("Case '%s' was not implemented",
              $type));
      }

      return $this;
    }

    /**
     * @param string $title
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function setTitle(string $title): ?NotificationBuilder
    {
      $this->notification->setTitle($title);

      return $this;
    }

    /**
     * @param string $message
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function setMessage(string $message): ?NotificationBuilder
    {
      $this->notification->setMessage($message);

      return $this;
    }

    /**
     * @param string|null $from
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function setFrom(string $from = null): ?NotificationBuilder
    {
      if (!($this->notification instanceof NotificationEmail)) {
        throw new \InvalidArgumentException('Can\'t set from to push notification.');
      }

      if (null === $from) {
        $from = $this->configuration['types']['email']['from'];
      }

      $this->notification->setFromEmail($from);

      return $this;
    }

    /**
     * @param array $recipients
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function setRecipients(array $recipients): ?NotificationBuilder
    {
      if (empty($recipients)) {
        throw new \InvalidArgumentException('At least one recipient required.');
      }

      foreach ($recipients as $recipient) {
        $this->notification->addRecipient($recipient);
      }

      return $this;
    }

    /**
     * @param array $value
     *
     * @return \Kopay\NotificationBundle\Service\NotificationBuilder|null
     */
    public function setValue(array $value): ?NotificationBuilder
    {
      if (!($this->notification instanceof NotificationPush)) {
        throw new \InvalidArgumentException('Can\'t set value to email notification.');
      }

      if (empty($value)) {
        throw new \InvalidArgumentException('Value can\'t be empty.');
      }

      $this->notification->setValue($value);

      return $this;
    }

    /**
     * @return \Kopay\NotificationBundle\Entity\NotificationMessageInterface|null
     */
    public function getEntity(): ?NotificationMessageInterface
    {
      return $this->notification;
    }
  }