<?php
  /**
   * Created by groovili
   * https://github.com/groovili
   */
  declare(strict_types = 1);

  namespace Kopay\NotificationBundle\Service;

  use Kopay\NotificationBundle\Entity\Notification;
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

    protected const NOTIFICATION_TYPE = [
        'Push'  => 0,
        'Email' => 1,
    ];

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
     * @param array $options
     */
    public function create(int $type, array $options): ?NotificationMessageInterface
    {
      if(!isset($options['recipients']) || empty($options['recipients'])){
        throw new InvalidArgumentException('Option "recipients" is 
        required to create notification.');
      }

      if(!in_array($type, self::NOTIFICATION_TYPE)){
        throw new InvalidArgumentException('Unknown type of notification.');
      }

      $title = (isset($options['title'])) ? $options['title'] : '';
      $message = (isset($options['message'])) ? $options['message'] : '';

      switch ($type) {
        case self::NOTIFICATION_TYPE['Push']:
          $notification = new NotificationPush();
          $value = (isset($options['value'])) ? $options['value'] : [];
          $notification->setValue($value);
          break;
        case self::NOTIFICATION_TYPE['Email']:
          $notification = new NotificationEmail();
          $from = (isset($options['from'])) ? $options['from'] :
              $this->configuration['types']['email']['from'];
          $notification->setFromEmail($from);
          break;
      }

      $notification->setTitle($title);
      $notification->setMessage($message);

      foreach ($options['recipients'] as $recipient){
        $notification->addRecipient($recipient);
      }

      return $notification;
    }

    /**
     * @param \Kopay\NotificationBundle\Entity\Notification $notification
     * @param array $options
     */
    public function send(Notification $notification, array $options)
    {
      // TODO: Implement creation of job dependent on notification type. In
      // options available providers and socket ports
    }
  }