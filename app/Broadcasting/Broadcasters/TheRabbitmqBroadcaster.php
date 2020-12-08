<?php

namespace App\Broadcasting\Broadcasters;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Broadcasting\Broadcasters\UsePusherChannelConventions;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TheRabbitmqBroadcaster extends Broadcaster
{
    use UsePusherChannelConventions;
    protected $prefix = "";

    //PhpAmqpLib\Connection\AMQPStreamConnection
    protected $connection;
    //protected $channel;//$connection->channel()
    const X_NAME = 'exchange_name';

    public function __construct() {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        // $this->channel = $connection->channel();
        // $this->channel->exchange_declare(X_NAME, 'fanout', false, false, false);
    }

    function __destruct() {
        // $this->channel->close();
        $this->connection->close();
    }
    
    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function auth($request)
    {
        $channelName = $this->normalizeChannelName(
            str_replace($this->prefix, '', $request->channel_name)
        );

        if (empty($request->channel_name) ||
            ($this->isGuardedChannel($request->channel_name) &&
            ! $this->retrieveUser($request, $channelName))) {
            throw new AccessDeniedHttpException;
        }

        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );
    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (is_bool($result)) {
            return json_encode($result);
        }

        $channelName = $this->normalizeChannelName($request->channel_name);

        return json_encode(['channel_data' => [
            'user_id' => $this->retrieveUser($request, $channelName)->getAuthIdentifier(),
            'user_info' => $result,
        ]]);
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        if (empty($channels)) {
            return;
        }

        $payload = json_encode([
            'event' => $event,
            'data' => $payload,
            'socket' => Arr::pull($payload, 'socket'),
        ]);
        $msg = new AMQPMessage($payload);

        $channel = $this->connection->channel();
        $channel->exchange_declare(self::X_NAME, 'topic', false, false, false);

        foreach($channels as $routing_key/*as redis channel*/) {
            $channel->basic_publish($msg, self::X_NAME,$routing_key);
        }
        $channel->close();


        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("TheRabbitmqBroadcaster@broadcast: payload=".$payload);
    }

    /**
     * Format the channel array into an array of strings.
     *
     * @param  array  $channels
     * @return array
     */
    protected function formatChannels(array $channels)
    {
        return array_map(function ($channel) {
            return $this->prefix.$channel;
        }, parent::formatChannels($channels));
    }
}