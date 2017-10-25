<?php
  /**
   * Created by groovili
   * https://github.com/groovili
   */
  declare(strict_types = 1);

  namespace Kopay\NotificationBundle\Service;

  use Kopay\NotificationBundle\Entity\NotificationEmail;
  use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
  use Kopay\NotificationBundle\Entity\NotificationPush;
  use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

  /**
   * Class NotificationManager
   *
   * @package Kopay\NotificationBundle\Service
   */
  class NotificationManager {
    /**
     * @var array
     */
    private $configuration;

    protected const NOTIFICATION_EMAIL = 0;

    protected const NOTIFICATION_PUSH  = 1;

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
     * @param int $type
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function create(int $type): ?NotificationManager
    {
      if ($type !== self::NOTIFICATION_EMAIL && $type !== self::NOTIFICATION_EMAIL) {
        throw new InvalidArgumentException('Unknown type of notification.');
      }

      switch ($type) {
        case self::NOTIFICATION_EMAIL:
          $this->notification = new NotificationEmail();
          break;
        case self::NOTIFICATION_PUSH:
          $this->notification = new NotificationPush();
          break;
      }

      return $this;
    }

    /**
     * @param string $title
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function setTitle(string $title): ?NotificationManager
    {
      $this->notification->setTitle($title);

      return $this;
    }

    /**
     * @param string $message
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function setMessage(string $message): ?NotificationManager
    {
      $this->notification->setMessage($message);

      return $this;
    }

    /**
     * @param string|null $from
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function setFrom(string $from = null): ?NotificationManager
    {
      if (!($this->notification instanceof NotificationEmail)) {
        throw new InvalidArgumentException('Can\'t set from to push notification.');
      }

      if (is_null($from)) {
        $from = $this->configuration['types']['email']['from'];
      }

      $this->notification->setFromEmail($from);

      return $this;
    }

    /**
     * @param array $recipients
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function setRecipients(array $recipients): ?NotificationManager
    {
      if (empty($recipients)) {
        throw new InvalidArgumentException('At least one recipient required.');
      }

      foreach ($recipients as $recipient) {
        $this->notification->addRecipient($recipient);
      }

      return $this;
    }

    /**
     * @param array $value
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function setValue(array $value): ?NotificationManager
    {
      if (!($this->notification instanceof NotificationPush)) {
        throw new InvalidArgumentException('Can\'t set value to email notification.');
      }

      if (empty($value)) {
        throw new InvalidArgumentException('Value can\'t be empty.');
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

    /**
     * @param array $options
     *
     * @return \Kopay\NotificationBundle\Service\NotificationManager|null
     */
    public function send(array $options): ?NotificationManager
    {
      // TODO: Implement creation of job dependent on notification type. In
      // options available providers and socket ports

      return $this;
    }
  }