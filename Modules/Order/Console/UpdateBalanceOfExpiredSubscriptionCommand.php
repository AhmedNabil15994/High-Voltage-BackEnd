<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Baqat\Repositories\FrontEnd\BaqatSubscriptionRepository as BaqatSubscriptionRepo;
use Modules\User\Entities\SubscriptionBalanceLog;

class UpdateBalanceOfExpiredSubscriptionCommand extends Command
{
    protected $name = 'subscription-balance:update';
    protected $description = 'Update balance of expired subscriptions';
    protected $subscription;

    public function __construct(BaqatSubscriptionRepo $subscription)
    {
        $this->subscription = $subscription;
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = $this->subscription->getExpiredSubscriptions();
        foreach ($subscriptions as $k => $subscription) {
            $amount = $subscription->price;
            $subscription->update(['price' => 0]);

            $subscription->user->update(['subscriptions_balance' => 0]);

            SubscriptionBalanceLog::create([
                'user_id' => $subscription->user_id,
                'order_id' => null,
                'amount_before' => $amount,
                'amount' => $amount,
                'amount_after' => 0,
            ]);
        }
        $this->info('Subscriptions Balance Updated Successfully.');
    }

}
