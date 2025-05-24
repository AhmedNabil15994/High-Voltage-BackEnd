<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Apps\Entities\DeliveryWorkingDay;
use Modules\Apps\Entities\PickupWorkingDay;

class WorkingTimesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $pickupCount = PickupWorkingDay::count();
        if ($pickupCount > 0) {
            DB::table('pickup_working_days')->delete();
        }
        $deliveryCount = DeliveryWorkingDay::count();
        if ($deliveryCount > 0) {
            DB::table('delivery_working_days')->delete();
        }
        $days = [
            [
                'day_name' => ['ar' => 'السبت', 'en' => 'Saturday'],
                'day_code' => 'sat',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الأحد', 'en' => 'Sunday'],
                'day_code' => 'sun',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الإثنين', 'en' => 'Monday'],
                'day_code' => 'mon',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الثلاثاء', 'en' => 'Tuesday'],
                'day_code' => 'tue',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الأربعاء', 'en' => 'Wednesday'],
                'day_code' => 'wed',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الخميس', 'en' => 'Thursday'],
                'day_code' => 'thu',
                'status' => 1,
                'is_full_day' => 1,
            ],
            [
                'day_name' => ['ar' => 'الجمعة', 'en' => 'Friday'],
                'day_code' => 'fri',
                'status' => 1,
                'is_full_day' => 1,
            ],
        ];

        foreach ($days as $key => $day) {
            PickupWorkingDay::create($day);
            DeliveryWorkingDay::create($day);
        }
    }
}
