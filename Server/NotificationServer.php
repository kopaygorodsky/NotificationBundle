<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Server;

use Kopay\NotificationBundle\Server\Security\AuthenticatorInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class NotificationServer implements MessageComponentInterface
{
    protected $connections;

    /**
     * @var AuthenticatorInterface
     */
    protected $authProvider;

    public function __construct(AuthenticatorInterface $provider = null)
    {
        $this->connections  = [];
        $this->authProvider = $provider;
    }

    /**
     * A new websocket connection.
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if ('127.0.0.1' === $conn->remoteAddress) {
            $conn->uniqueId                     = uniqid('internal_', true);
        } elseif ($this->authProvider) {
            //if auth is enabled
            if (null === $authToken = $this->authProvider->authenticate($conn)) {
                $conn->close(403);

                return;
            }
            $user                              = $authToken->getUser();
            $conn->uniqueId                    = 'user_'.$user->getId();
        } else {
            $conn->uniqueId                     = uniqid('conn_', true);
        }

        $this->connections[$conn->uniqueId] = $conn;
        $conn->send(json_encode(['connection_id' => $conn->uniqueId, 'message' => '..:: Hello from the Notification Center ::..', 'ip' => $conn->remoteAddress]));
    }

    /**
     * Handle message sending.
     *
     * @param ConnectionInterface $from
     * @param string              $msg
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        //allowed only for internal connections
        if (false === strpos($from->uniqueId, 'internal_')) {
            $from->send('You are not allowed to send messages');

            return;
        }

        $data = json_decode($msg, true);

        if (!isset($data['recipient']) || !array_key_exists($receiverKey = $data['recipient'], $this->connections)) {
            return;
        }

        if (!isset($data['data'])) {
            return;
        }

        $this->connections[$receiverKey]->send(json_encode($data['data']));
    }

    /**
     * A connection is closed.
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        if (isset($this->connections[$conn->uniqueId])) {
            unset($this->connections[$conn->uniqueId]);
        }
    }

    /**
     * Error handling.
     *
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo sprintf("Error: %s\n", $e->getMessage());
        $conn->send('Error : '.$e->getMessage());
        $conn->close();
    }
}
