<?php

namespace Kopay\NotificationBundle\Server;

use Kopay\NotificationBundle\Server\Security\AuthProviderInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Notification implements MessageComponentInterface
{
    protected $connections;

    /**
     * @var AuthProviderInterface
     */
    protected $authProvider;

    public function __construct(AuthProviderInterface $provider = null)
    {
        $this->connections = [];
        $this->authProvider = $provider;
    }

    /**
     * A new websocket connection
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if ($this->authProvider) {
            if (null === $authToken = $this->authProvider->authenticate($conn)) {
                $conn->close(403);
                return;
            };
            $user = $authToken->getUser();
            $conn->uniqueId = $user->getId();
            $this->connections[$user->getId()] = $conn;
        } else {
            $uniqueId = uniqid('conn_', true);
            $conn->uniqueId = $uniqueId;
            $this->connections[$uniqueId] = $conn;
        }
        echo "New connection $uniqueId\n";
        //$conn->send('..:: Hello from the Notification Center ::..');
    }

    /**
     * Handle message sending
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);

        if (isset($data['receiver']) && array_key_exists($data['receiver'], $this->connections)) {
            $this->connections[$data['receiver']]->send($data['message']);
        }
        $from->send('Done');
    }

    /**
     * A connection is closed
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        if (isset($this->connections[$conn->uniqueId])) {
            unset($this->connections[$conn->uniqueId]);
        }
    }

    /**
     * Error handling
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send('Error : ' . $e->getMessage());
        $conn->close();
    }
}