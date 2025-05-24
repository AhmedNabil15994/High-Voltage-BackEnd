<?php

namespace Modules\Baqat\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Baqat\Http\Requests\FrontEnd\BaqatSubscriptionRequest;
use Modules\Baqat\Repositories\FrontEnd\BaqatRepository as BaqatRepo;
use Modules\Baqat\Repositories\FrontEnd\BaqatSubscriptionRepository as BaqatSubscription;
use Modules\Transaction\Services\UPaymentService;

class BaqatSubscriptionController extends Controller
{
    protected $baqatSubscription;
    protected $payment;
    protected $baqat;

    public function __construct(BaqatSubscription $baqatSubscription, UPaymentService $payment, BaqatRepo $baqat)
    {
        $this->baqatSubscription = $baqatSubscription;
        $this->payment = $payment;
        $this->baqat = $baqat;
    }

    public function store(BaqatSubscriptionRequest $request)
    {
        $baqa = $this->baqat->findById($request->baqat_id);
        $baqaPrice = floatval($baqa->price);
        if (!is_null($baqa->offer)) {
            if (!is_null($baqa->offer->offer_price)) {
                $baqaPrice = $baqa->offer->offer_price;
            } else {
                $baqaPrice = calculateOfferAmountByPercentage($baqaPrice, $baqa->offer->percentage);
            }
        }
        
        $subscription = $this->baqatSubscription->create($request, $baqa, $baqaPrice);
        if ($subscription) {
            if (in_array($request->payment_type, ['knet', 'cc'])) {
                $newSubscriptionObject = [
                    'id' => $subscription->id,
                    'total' => $baqaPrice,
                ];
                $paymentUrl = $this->payment->send($newSubscriptionObject, $request->payment_type, 'create-subscription');
                if (is_null($paymentUrl)) {
                    return $this->redirectToFailedPayment();
                } else {
                    return redirect()->away($paymentUrl);
                }
            } else {
                return redirect()->back()->with([
                    'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.payment_not_supported_now'),
                ]);
            }
        }
        return redirect()->back()->with([
            'alert' => 'danger', 'status' => __('baqat::frontend.baqat_subscriptions.alerts.subscription_failed'),
        ]);
    }

    public function redirectToFailedPayment()
    {
        return redirect()->route('frontend.baqat.index')->with([
            'alert' => 'danger', 'status' => __('baqat::frontend.baqat_subscriptions.alerts.subscription_failed'),
        ]);
    }

    public function subscriptionWebhooks(Request $request)
    {
        $this->baqatSubscription->updateSubscriptionPayment($request);
    }

    public function subscriptionSuccess(Request $request)
    {
        $checkSubscription = $this->baqatSubscription->updateSubscriptionPayment($request);
        return $checkSubscription ? $this->redirectToPaymentOrOrderPage() : $this->redirectToFailedPayment();
    }

    public function subscriptionFailed(Request $request)
    {
        $this->baqatSubscription->updateSubscriptionPayment($request);
        return $this->redirectToFailedPayment();
    }

    public function redirectToPaymentOrOrderPage()
    {
        return redirect()->route('frontend.profile.index')->with([
            'alert' => 'success', 'status' => __('baqat::frontend.baqat_subscriptions.alerts.subscription_success'),
        ]);
    }

}
