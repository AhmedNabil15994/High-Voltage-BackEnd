<?php

namespace Modules\Baqat\Repositories\FrontEnd;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Modules\Baqat\Entities\Baqat;
use Modules\Baqat\Entities\BaqatSubscription;
use Modules\Baqat\Traits\BaqatTrait;
use Modules\Order\Entities\PaymentStatus;

class BaqatSubscriptionRepository
{
    use BaqatTrait;

    protected $baqatSubscription;
    protected $baqat;

    public function __construct(BaqatSubscription $baqatSubscription, Baqat $baqat)
    {
        $this->baqatSubscription = $baqatSubscription;
        $this->baqat = $baqat;
    }

    public function findById($id, $with = [])
    {
        $query = $this->baqatSubscription->withDeleted();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->find($id);
    }

    public function create($request, $baqa, $baqaPrice)
    {
        DB::beginTransaction();

        $userId = auth()->id();
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(intval($baqa->duration_by_days))->format('Y-m-d');
        $subscriptionNewPrice = $baqaPrice;

        try {
            $baqatSubscription = $this->baqatSubscription->create([
                'baqat_id' => $request->baqat_id,
                'user_id' => $userId,
                'start_at' => $startDate,
                'end_at' => $endDate,
                'price' => $subscriptionNewPrice,
                'type' => 'client',
                'duration_by_days' => $baqa->duration_by_days,
                'payment_status_id' => 1, // pending
            ]);

            $baqatSubscription->transaction()->updateOrCreate(['baqat_subscription_id' => $baqatSubscription->id], [
                'method' => $request->payment_type,
                'result' => null,
            ]);

            DB::commit();
            return $baqatSubscription;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateSubscriptionPayment($request)
    {
        DB::beginTransaction();
        try {
            $subscription = $this->findById($request['OrderID'], ['baqa', 'user']);
            if (!$subscription) {
                return false;
            }

            $endDate = null;
            $subscriptionNewPrice = null;
            $durationByDays = null;
            $subscriptionsBalance = null;
            $subscriptionEndDate= null;

            if ($request['Result'] == 'CAPTURED') {
                $newPaymentStatus = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $subscription->payment_status_id;
                $paymentConfirmedAt = date('Y-m-d H:i:s');
                $startDate = Carbon::now()->format('Y-m-d');
                $lastUserSubscription = $this->getUserLastSubscription($subscription->user_id);
                if (!is_null($lastUserSubscription)) {
                    $subscriptionStartDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->start_at);
                    $subscriptionEndDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->new_end_at ?? $lastUserSubscription->end_at);
                    $checkDate = Carbon::now()->between($subscriptionStartDate, $subscriptionEndDate);

                    if ($subscriptionStartDate->gt(Carbon::now()) == true) {
                        return false;
                    }

                    if ($checkDate == true) {
                        // there is active subscription
                        $endDate = $subscriptionEndDate->addDays(intval($subscription->baqa->duration_by_days))->format('Y-m-d');
                        $subscriptionNewPrice = floatval($lastUserSubscription->price) + $subscription->price;
                        $durationByDays = intval($subscription->baqa->duration_by_days) + intval($lastUserSubscription->baqa->duration_by_days);
                    } else {
                        $endDate = Carbon::now()->addDays(intval($subscription->baqa->duration_by_days))->format('Y-m-d');
                        $subscriptionNewPrice = $subscription->price;
                    }

                    $subscriptionsBalance = floatval($subscription->baqa->client_price);
                    $lastUserSubscription->update(['end_at' => $startDate, 'new_end_at' => $lastUserSubscription->end_at]);
                } else {
                    $subscriptionsBalance = floatval($subscription->baqa->client_price);
                }

                $userPointsCount = calculateUserPointsCount($subscription->price);
                $subscription->user->increment('loyalty_points_count', $userPointsCount);
            } else {
                $newPaymentStatus = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $subscription->payment_status_id;
                $paymentConfirmedAt = null;
            }

            $subscriptionData = [
                'payment_status_id' => $newPaymentStatus,
                'payment_confirmed_at' => $paymentConfirmedAt,
            ];

            if (!is_null($endDate)) {
                $subscriptionData['end_at'] = $endDate;
            }
            if (!is_null($subscriptionNewPrice)) {
                $subscriptionData['price'] = $subscriptionNewPrice;
            }
            if (!is_null($durationByDays)) {
                $subscriptionData['duration_by_days'] = $durationByDays;
            }

            $subscription->update($subscriptionData);

            if (!is_null($subscriptionsBalance)) {
                if($subscriptionEndDate && $subscriptionEndDate->lt(Carbon::now()) == true){
                    $subscription->user->decrement('subscriptions_balance', floatval($subscription->user->subscriptions_balance));
                    $subscription->user->increment('subscriptions_balance', $subscriptionsBalance);
                }else{
                    $subscription->user->increment('subscriptions_balance', $subscriptionsBalance);
                }
            }

            $subscription->transaction()->updateOrCreate(
                [
                    'baqat_subscription_id' => $request['OrderID'],
                ],
                [
                    'auth' => $request['Auth'],
                    'tran_id' => $request['TranID'],
                    'result' => $request['Result'],
                    'post_date' => $request['PostDate'],
                    'ref' => $request['Ref'],
                    'track_id' => $request['TrackID'],
                    'payment_id' => $request['PaymentID'],
                ]
            );

            DB::commit();
            return ($request['Result'] == 'CAPTURED') ? true : false;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getOnlinePendingSubscriptions()
    {
        $currentDate = new \DateTime;
        $currentDate->modify('-15 minutes'); // get subscriptions after 15 minutes
        $formattedDate = $currentDate->format('Y-m-d H:i:s');
        $subscriptions = $this->baqatSubscription->where('payment_status_id', 1);
        $subscriptions = $subscriptions->where('created_at', '<=', $formattedDate);
        return $subscriptions->get();
    }

    public function getExpiredSubscriptions()
    {
        return $this->baqatSubscription->with('user')->expired()->successSubscriptions()->lastPerGroup()->get();
    }

}
