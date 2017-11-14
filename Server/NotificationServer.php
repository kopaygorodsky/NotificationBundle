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
        $conn->send('You are connected from: '.$conn->remoteAddress);

        if ('127.0.0.1' === $conn->remoteAddress) {
            $conn->uniqueId                     = uniqid('internal_', true);
            $this->connections[$conn->uniqueId] = $conn;
        } elseif ($this->authProvider) {
            //if auth is enabled
            if (null === $authToken = $this->authProvider->authenticate($conn)) {
                $conn->close(403);

                return;
            }
            $user                              = $authToken->getUser();
            $conn->uniqueId                    = $user->getId();
            $this->connections[$user->getId()] = $conn;
        } else {
            $conn->uniqueId                     = uniqid('conn_', true);
            $this->connections[$conn->uniqueId] = $conn;
        }

        $conn->send('..:: Hello from the Notification Center ::..');
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

        if (isset($data['receiver']) && array_key_exists($data['receiver'], $this->connections)) {
            $this->connections[$data['receiver']]->send(json_encode($data['message']));
        }
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
        $conn->send('Error : '.$e->getMessage());
        $conn->close();
    }
}
