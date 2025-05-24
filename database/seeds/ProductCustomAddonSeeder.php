<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCustomAddonSeeder extends Seeder
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
            $custom_addon_query = "
                INSERT INTO `custom_addons` (`id`, `title`, `status`, `sort`, `image`, `deleted_at`, `created_at`, `updated_at`) VALUES
                (1, '{\"ar\": \"غسيل و كوى\", \"en\": \"Wash & Iron\"}', 1, 1, 'admin/images/custom-addons/wash-iron-icon.png', NULL, '2022-11-20 12:56:19', '2022-11-20 12:56:19'),
                (2, '{\"ar\": \"غسيل جاف\", \"en\": \"Dray Clean\"}', 1, 2, 'admin/images/custom-addons/dryclean-icon.png', NULL, '2022-11-20 12:56:50', '2022-11-20 12:56:50'),
                (3, '{\"ar\": \"كوى\", \"en\": \"Iron\"}', 1, 3, 'admin/images/custom-addons/iron-icon.png', NULL, '2022-11-20 12:56:50', '2022-11-20 12:56:50');
            ";
            $this->insert($custom_addon_query);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function insert($string)
    {
        DB::statement($string);
    }

}
