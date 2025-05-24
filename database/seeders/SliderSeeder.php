<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Slider\Entities\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $all = [
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'background_image' => path_without_domain(url('uploads/sliders/banner-2-shape-2.png')),
                    'image' => path_without_domain(url('uploads/sliders/banner-2-bg-1.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'ملابس اكثر نظافة ليوم اكثر حيوية',
                        'en' => 'Cleaner Clothes for a Livelier Day',
                    ],
                    'short_description' => [
                        'ar' => 'ملابس اكثر نظافة ليوم اكثر حيوية',
                        'en' => 'Cleaner Clothes for a Livelier Day',
                    ],
                ],
                [
                    'start_at' => date('Y-m-d'),
                    'end_at' => date('Y-m-d', strtotime('+5 years')),
                    'link' => '#',
                    'background_image' => path_without_domain(url('uploads/sliders/banner-2-shape-2.png')),
                    'image' => path_without_domain(url('uploads/sliders/banner-2-bg-2.png')),
                    'status' => 1,
                    'title' => [
                        'ar' => 'ملابس اكثر نظافة ليوم اكثر حيوية',
                        'en' => 'Cleaner Clothes for a Livelier Day',
                    ],
                    'short_description' => [
                        'ar' => 'ملابس اكثر نظافة ليوم اكثر حيوية',
                        'en' => 'Cleaner Clothes for a Livelier Day',
                    ],
                ],
            ];

            DB::table('sliders')->truncate();

            foreach ($all as $k => $slider) {
                $s = Slider::create($slider);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
