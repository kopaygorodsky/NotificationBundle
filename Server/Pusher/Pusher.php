<?php

namespace Kopay\NotificationBundle\Server\Pusher;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Ratchet\Client;

class Pusher
{
    public function send(NotificationMessageInterface $notification): void
    {
//        $data = ['receiver' => 'conn_59f146516d8ad7.11422006', 'message' => 'info'];
//        Client\connect('ws://localhost:8080')->then(function($conn) {
//            $conn->send(json_encode($data));
//            $conn->close();
//        }, function ($e) {
//            echo "Could not connect: {$e->getMessage()}\n";
//        });
    }
}