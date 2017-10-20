<?php

namespace Kopay\NotificationBundle\Entity;

class NotificationPush extends Notification
{
    /**
     * @var array
     */
    protected $value;

    /**
     * @return array
     */
    public function getValue(): ? array
    {
        return $this->value;
    }

    /**
     * @param array $value
     */
    public function setValue(array $value)
    {
        $this->value = $value;
    }

}