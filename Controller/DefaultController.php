<?php

namespace Kopay\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('kopaygorodsky_notification.websockets.auth_provider');
        \Ratchet\Client\connect('ws://localhost:8080')->then(function($conn) {
            $data = ['receiver' => 'conn_59f146516d8ad7.11422006', 'message' => 'info'];

            $conn->send(json_encode($data));
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });

        die();
    }

}
