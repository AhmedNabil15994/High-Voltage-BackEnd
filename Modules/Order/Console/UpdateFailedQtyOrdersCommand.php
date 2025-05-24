<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Order\Repositories\Dashboard\OrderRepository as Order;

class UpdateFailedQtyOrdersCommand extends Command
{
    protected $name = 'order:update';
    protected $description = 'Reset pending online orders';
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        parent::__construct();
    }

    public function handle()
    {
        $orders = $this->order->getOnlinePendingOrders();
        foreach ($orders as $k => $order) {
            $order->update([
                'payment_status_id' => null,
            ]);
        }
        $this->info('Orders Updated Successfully.');
    }

}
