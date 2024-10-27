<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new Channel('transactions');
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Une nouvelle transaction a été effectuée.',
            'transaction' => $this->transaction,
        ];
    }
}
