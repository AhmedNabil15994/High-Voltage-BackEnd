<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Category;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $count = Category::count();
        if ($count == 0) {
            $query = "
            INSERT INTO `categories` (`id`, `slug`, `title`, `seo_keywords`, `seo_description`, `image`, `cover`, `status`, `show_in_home`, `category_id`, `color`, `sort`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, '{\"ar\": \"الرجالي\", \"en\": \"men\"}', '{\"ar\": \"الرجالي\", \"en\": \"Men\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 1, NULL, '2022-11-16 11:15:19', '2022-11-16 11:15:19'),
            (2, '{\"ar\": \"النسائي\", \"en\": \"womens\"}', '{\"ar\": \"النسائي\", \"en\": \"Women\'s\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 2, NULL, '2022-11-16 11:16:10', '2022-11-16 11:16:10'),
            (3, '{\"ar\": \"البدل\", \"en\": \"suits\"}', '{\"ar\": \"البدل\", \"en\": \"Suits\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 3, NULL, '2022-11-16 11:16:57', '2022-11-16 11:16:57'),
            (4, '{\"ar\": \"الأطفال\", \"en\": \"children\"}', '{\"ar\": \"الأطفال\", \"en\": \"Children\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 4, NULL, '2022-11-16 11:17:29', '2022-11-16 11:17:29'),
            (5, '{\"ar\": \"البيت\", \"en\": \"house\"}', '{\"ar\": \"البيت\", \"en\": \"House\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 5, NULL, '2022-11-16 11:17:59', '2022-11-16 11:17:59'),
            (6, '{\"ar\": \"الملابس-الداخلية\", \"en\": \"underwear\"}', '{\"ar\": \"الملابس الداخلية\", \"en\": \"Underwear\"}', '{\"ar\": null}', '{\"ar\": null}', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 'http://127.0.0.1:8000/storage/photos/shares/logo/logo.png', 1, 1, NULL, '#000000', 6, NULL, '2022-11-16 11:18:29', '2022-11-16 11:18:29');
            ";

            $this->insert($query);
        }
    }

    public function insert($string)
    {
        DB::statement($string);
    }
}
