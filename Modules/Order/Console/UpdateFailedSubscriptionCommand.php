<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Baqat\Repositories\FrontEnd\BaqatSubscriptionRepository as BaqatSubscriptionRepo;

class UpdateFailedSubscriptionCommand extends Command
{
    protected $name = 'subscription:update';
    protected $description = 'Update pending online subscriptions';
    protected $subscription;

    public function __construct(BaqatSubscriptionRepo $subscription)
    {
        $this->subscription = $subscription;
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = $this->subscription->getOnlinePendingSubscriptions();
        foreach ($subscriptions as $k => $subscription) {
            $subscription->update([
                'payment_status_id' => 3, // failed
                'payment_confirmed_at' => null,
            ]);
        }
        $this->info('Subscriptions Updated Successfully.');
    }

}
