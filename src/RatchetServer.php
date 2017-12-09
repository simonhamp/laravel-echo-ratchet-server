<?php

namespace SimonHamp\LaravelEchoRatchetServer;

use Ratchet\ConnectionInterface;
use Askedio\LaravelRatchet\RatchetWsServer as BaseServer;

class RatchetServer extends BaseServer
{
    private $subscribedTopics = [];

    /**
     * Client-side message from Echo
     */
    public function onMessage(ConnectionInterface $from, $message)
    {
        $message = json_decode($message);

        // TODO: Various auth checks and safety. We don't want to try to handle anything/everything from the client!
        $channel = $message->message->channel;

        if ($message->event == 'subscribe') {
            // Tie the connection to the channel
            $this->subscribedTopics[$channel][] = $from;
        }
    }

    /**
     * Server-side message from ZMQ
     */
    public function onEntry($entry)
    {
        if (is_array($entry)) {
            // First item from laravel-zmq is the channel name
            $channel = $entry[0];

            // Second is the actual message (in JSON format)
            $entry = json_decode($entry[1], true);

            // Put the channel name back into the payload
            $entry['channel'] = $channel;

            // And re-encode so it can be sent to clients
            $entry = json_encode($entry);
        }

        // No channel found in the payload or channel doesn't have any subscribers
        if (! $channel || ! array_key_exists($channel, $this->subscribedTopics)) {
            return;
        }

        if (starts_with($channel, 'private-')) {
            // Only get the subscribers to the channel that this should be published on
            $subs = $this->subscribedTopics[$channel];

            foreach ($subs as $to) {
                $this->send($to, $entry);
            }
        } else {
            // If it's public, send to all clients
            $this->sendAll($entry);
        }
    }
}
