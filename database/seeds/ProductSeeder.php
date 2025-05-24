<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Product;

class ProductSeeder extends Seeder
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
            $count = Product::count();

            if ($count == 0) {
                $query = "
                INSERT INTO `products` (`id`, `slug`, `title`, `description`, `seo_keywords`, `seo_description`, `product_flag`, `image`, `price`, `sku`, `qty`, `status`, `sort`, `deleted_at`, `created_at`, `updated_at`) VALUES
                (1, '{\"ar\": \"دشداشة\", \"en\": \"dishdasha\"}', '{\"ar\": \"دشداشة\", \"en\": \"Dishdasha\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/Zz9lRkNEvT6TI8a2Jcqkz8ZHCrcmrSKFaXp5ckGr.png', '0.000', NULL, NULL, 1, 1, NULL, '2022-11-16 11:28:16', '2022-11-16 11:28:16'),
                (2, '{\"ar\": \"غطرة\", \"en\": \"ghatra\"}', '{\"ar\": \"غطرة\", \"en\": \"Ghatra\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/P097bXIeqFzFCCUDKz6IZbsWgmR2KBiZDnKvJw7D.jpg', '0.000', NULL, NULL, 1, 2, NULL, '2022-11-16 11:32:15', '2022-11-16 11:32:15'),
                (3, '{\"ar\": \"شماغ\", \"en\": \"shemagh\"}', '{\"ar\": \"شماغ\", \"en\": \"Shemagh\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/CpXhCLAjQq8Se316n2S17hsdYXZDYDxn7cs157z6.jpg', '0.000', NULL, NULL, 1, 3, NULL, '2022-11-16 11:35:40', '2022-11-16 11:35:40'),
                (4, '{\"ar\": \"بنطلون\", \"en\": \"trouser\"}', '{\"ar\": \"بنطلون\", \"en\": \"Trouser\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/53S9UmGtmfHXemj5kJcKXtTgpPYcCF5ANdJDnqVt.png', '0.000', NULL, NULL, 1, 4, NULL, '2022-11-16 11:37:24', '2022-11-16 11:37:24'),
                (5, '{\"ar\": \"قميص\", \"en\": \"shirt\"}', '{\"ar\": \"قميص\", \"en\": \"Shirt\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/b4CgBiDzlP9VBcPlTNdEVwe0c4Fal85qkbLoWy7u.jpg', '0.000', NULL, NULL, 1, 5, NULL, '2022-11-16 11:38:40', '2022-11-16 11:38:40'),
                (6, '{\"ar\": \"عباية\", \"en\": \"abaya\"}', '{\"ar\": \"عباية\", \"en\": \"Abaya\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/tTykGSmlYoLdRrOQYjRy5aijDPwiPNOoy2zN3PWD.png', '0.000', NULL, NULL, 1, 6, NULL, '2022-11-16 11:39:54', '2022-11-16 11:39:54'),
                (7, '{\"ar\": \"سارى\", \"en\": \"saree\"}', '{\"ar\": \"سارى\", \"en\": \"Saree\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/blJsMUX6srRjXVY1YSdHpOcvPXsMFd8C5e9vWUCr.png', '0.000', NULL, NULL, 1, 7, NULL, '2022-11-16 11:41:33', '2022-11-16 11:41:33'),
                (8, '{\"ar\": \"نقاب\", \"en\": \"veil\"}', '{\"ar\": \"نقاب\", \"en\": \"Veil\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/BudK6i4nzeIaoI513y1k8lOsHOZbdoXHON9CQHHv.jpg', '0.000', NULL, NULL, 1, 8, NULL, '2022-11-16 11:42:39', '2022-11-16 11:42:39'),
                (9, '{\"ar\": \"فوطة\", \"en\": \"towel\"}', '{\"ar\": \"فوطة\", \"en\": \"Towel\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/BUAftKmiMQENyfdnC2iV4FGWCutVEsMeAk8tIyEB.png', '0.000', NULL, NULL, 1, 9, NULL, '2022-11-16 11:44:08', '2022-11-16 11:44:08'),
                (10, '{\"ar\": \"بيجامة\", \"en\": \"pajamas\"}', '{\"ar\": \"بيجامة\", \"en\": \"Pajamas\"}', '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', 'single', 'uploads/products/tyBNHjYuKeC4UPsZCd0wScaJr2sQmdyh9yVnER2j.png', '0.000', NULL, NULL, 1, 10, NULL, '2022-11-16 11:45:51', '2022-11-16 11:45:51');
                ";

                $this->insert($query);

                $productCategoriesQuery = "
                INSERT INTO `product_categories` (`id`, `category_id`, `product_id`, `created_at`, `updated_at`) VALUES
                (1, 1, 1, NULL, NULL),
                (2, 1, 2, NULL, NULL),
                (3, 1, 3, NULL, NULL),
                (4, 1, 4, NULL, NULL),
                (5, 1, 5, NULL, NULL),
                (6, 2, 6, NULL, NULL),
                (7, 2, 7, NULL, NULL),
                (8, 2, 8, NULL, NULL),
                (9, 5, 9, NULL, NULL),
                (10, 6, 10, NULL, NULL);
                ";

                $this->insert($productCategoriesQuery);
            }

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
