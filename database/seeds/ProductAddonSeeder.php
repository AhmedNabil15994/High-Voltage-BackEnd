<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAddonSeeder extends Seeder
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
            $addon_categories_query = "
                INSERT INTO `addon_categories` (`id`, `slug`, `title`, `sort`, `deleted_at`, `created_at`, `updated_at`) VALUES
                (1, '{\"ar\": \"غسيل\", \"en\": \"laundry\"}', '{\"ar\": \"غسيل\", \"en\": \"Laundry\"}', 1, NULL, '2022-11-16 12:56:19', '2022-11-16 12:56:19'),
                (2, '{\"ar\": \"كوى\", \"en\": \"presser\"}', '{\"ar\": \"كوى\", \"en\": \"Presser\"}', 2, NULL, '2022-11-16 12:56:50', '2022-11-16 12:56:50');
            ";
            $this->insert($addon_categories_query);

            $addon_options_query = "
                INSERT INTO `addon_options` (`id`, `addon_category_id`, `title`, `price`, `qty`, `image`, `deleted_at`, `created_at`, `updated_at`) VALUES
                (1, 1, '{\"ar\": \"غسيل وكوى\", \"en\": \"Laundry and ironing\"}', 15.00, NULL, 'uploads/addons/AIgMkO2ux7Za1T9lKGfOG39820vchBgAFyZ4chc9.png', NULL, '2022-11-16 13:07:50', '2022-11-16 13:07:50'),
                (2, 1, '{\"ar\": \"غسيل جاف\", \"en\": \"Dry wash\"}', 10.00, NULL, 'uploads/addons/OHg5MM9IrI5nWQ6TbyljlZlpWleSDas4dkQi51Zf.png', NULL, '2022-11-16 13:08:58', '2022-11-16 13:08:58'),
                (3, 1, '{\"ar\": \"كوى\", \"en\": \"Presser\"}', 8.50, NULL, 'uploads/addons/7ZGBderrtI9RxskMDQ5xZP1rAPgkSNLHWEogiWJi.png', NULL, '2022-11-16 13:10:07', '2022-11-16 13:10:07');
            ";
            $this->insert($addon_options_query);

            $product_addons_query = "
                INSERT INTO `product_addons` (`id`, `product_id`, `addon_category_id`, `type`, `min_options_count`, `max_options_count`, `is_required`, `created_at`, `updated_at`) VALUES
                (1, 1, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (2, 2, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (3, 3, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (4, 4, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (5, 5, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (6, 6, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (7, 7, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (8, 8, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (9, 9, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05'),
                (10, 10, 1, 'single', NULL, NULL, 0, '2022-11-16 13:11:05', '2022-11-16 13:11:05');
            ";
            $this->insert($product_addons_query);

            $product_addon_options_query = "
                INSERT INTO `product_addon_options` (`id`, `product_addon_id`, `addon_option_id`, `default`) VALUES
                (1, 1, 3, 0),
                (2, 1, 2, 0),
                (3, 1, 1, 0),
                (4, 2, 3, 0),
                (5, 2, 2, 0),
                (6, 2, 1, 0),
                (7, 3, 3, 0),
                (8, 3, 2, 0),
                (9, 3, 1, 0),
                (10, 4, 3, 0),
                (11, 4, 2, 0),
                (12, 4, 1, 0),
                (13, 5, 3, 0),
                (14, 5, 2, 0),
                (15, 5, 1, 0),
                (16, 6, 3, 0),
                (17, 6, 2, 0),
                (18, 6, 1, 0),
                (19, 7, 3, 0),
                (20, 7, 2, 0),
                (21, 7, 1, 0),
                (22, 8, 3, 0),
                (23, 8, 2, 0),
                (24, 8, 1, 0),
                (25, 9, 3, 0),
                (26, 9, 2, 0),
                (27, 9, 1, 0),
                (28, 10, 3, 0),
                (29, 10, 2, 0),
                (30, 10, 1, 0);
            ";
            $this->insert($product_addon_options_query);

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
