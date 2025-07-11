<?php

use Database\Seeders\PagesTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardSeeder extends Seeder
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
            $this->insertSetting();
            $this->insertSliders();
            $this->insertCountries();
            $this->insertOption();
            $this->insertCities();
            $this->insertSates();
            $this->insertLtmTranslations();
            $this->inserPages();
            $this->insertUsers();
            $this->insertRoleAndPermissions();
            $this->insertShippingCompany();
            $this->insertOrders();
            $this->insertAds();

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

    public function insertSetting()
    {
        $data = "
        INSERT INTO `settings` (`id`, `key`, `value`, `locale`, `created_at`, `updated_at`) VALUES
        (1, 'locales', '[\"en\",\"ar\"]', NULL, NULL, NULL),
        (2, 'default_locale', 'ar', NULL, NULL, NULL),
        (3, 'rtl_locales', '[\"ar\"]', NULL, NULL, NULL),
        (4, 'translate', '{\"app_name\":\"High Voltage Laundry\"}', NULL, NULL, NULL),
        (5, 'contact_us', '{\"email\":\"info@hvlaunday.tocaank.com\",\"whatsapp\":\"96594971095\", \"mobile\":\"+(965) 94971095\", \"technical_support\":\"+(965) 94971095\"}', NULL, NULL, NULL),
        (6, 'social', '{\"facebook\":\"#\",\"twitter\":\"#\",\"instagram\":\"#\",\"linkedin\":\"#\",\"youtube\":\"#\",\"snapchat\":\"#\"}', NULL, NULL, NULL),
        (7, 'env', '{\"MAIL_DRIVER\":\"smtp\",\"MAIL_ENCRYPTION\":\"tls\",\"MAIL_HOST\":\"smtp.gmail.com\",\"MAIL_PORT\":\"587\",\"MAIL_FROM_ADDRESS\":\"info@hvlaunday.tocaank.com\",\"MAIL_FROM_NAME\":\"High Voltage\",\"MAIL_USERNAME\":\"info@hvlaunday.tocaank.com\",\"MAIL_PASSWORD\":\"HighVoltage147147\"}', NULL, NULL, NULL),
        (8, 'default_shipping', NULL, NULL, NULL, NULL),
        (9, 'other', '{\"privacy_policy\":\"3\",\"terms\":\"2\",\"shipping_company\":\"1\",\"about_us\":\"1\",\"force_update\":\"0\",\"enable_website\":\"1\",\"is_multi_vendors\":\"1\",\"select_shipping_provider\":\"shipping_company\"}', NULL, NULL, NULL),
        (10, 'images', '{\"logo\":\"storage/photos/shares/logo/logo.png\",\"white_logo\":\"storage/photos/shares/logo/footer/logo.png\",\"favicon\":\"storage/photos/shares/favicon/favicon.png\"}', NULL, NULL, NULL),
        (11, 'default_vendor', '1', NULL, NULL, NULL),
        (12, 'app_name', '{\"en\":\"High Voltage Laundry\",\"ar\":\"High Voltage Laundry\"}', NULL, NULL, NULL),
        (13, 'app_address', '{\"en\":\"Kuwait City - Kuwait\",\"ar\":\"مدينة الكويت - الكويت\"}', NULL, NULL, NULL),
        (14, 'fast_delivery_message', '{\"en\":\"Delivery of clothes on the same day after 4 hours of receipt and double the price\",\"ar\":\"توصيل الملابس في نفس اليوم بعد 4 ساعات من الاستلام و مضاعفة السعر\"}', NULL, NULL, NULL),
        (15, 'app_description', '{\"en\":\"High Voltage Laundry Provides premium laundry, iron and dry cleaning services\",\"ar\":\"High Voltage Laundry Provides premium laundry, iron and dry cleaning services\"}', NULL, NULL, NULL),
        (16, 'supported_payments', '{\"cash\":{\"title\":{\"en\":\"Cash\",\"ar\":\"الدفع عند الإستلام\"},\"status\":\"on\"},\"upayment\":{\"payment_mode\":\"test_mode\", \"live_mode\":{\"merchant_id\":\"679\",\"api_key\":\"nLuf1cAgcx2KFEViDSzxN785vXqlNx4FawQaQ086\",\"username\":\"tocaan\",\"password\":\"ml4nf9wx2utuogcr\",\"iban\":\"KW76NBOK0000000000002019539572\"},\"test_mode\":{\"merchant_id\":\"1201\",\"api_key\":\"jtest123\",\"username\":\"test\",\"password\":\"test\"},\"title\":{\"en\":\"Upayment\",\"ar\":\"يو باى\"},\"account_type\":\"client_account\",\"commissions\":{\"knet\":{\"fixed_app_commission\":null,\"percentage_app_commission\":null},\"cc\":{\"fixed_app_commission\":null,\"percentage_app_commission\":null}},\"client_commissions\":{\"knet\":{\"commission_type\":\"fixed\",\"commission\":\"0.350\"},\"cc\":{\"commission_type\":\"percentage\",\"commission\":\"2.7\"}},\"status\":\"on\"},\"myfatourah\":{\"payment_mode\":\"test_mode\",\"live_mode\":{\"api_key\":\"\"},\"test_mode\":{\"api_key\":\"rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL\"},\"title\":{\"en\":\"MyFatourah\",\"ar\":\"ماى فاتورة\"},\"status\":\"on\"}}', NULL, NULL, NULL),
        (17, 'countries', '[\"KW\"]', NULL, NULL, NULL),
        (18, 'theme_sections', '{\"top_header\":\"on\",\"middle_header\":\"on\",\"bottom_header\":\"on\",\"side_menu\":\"on\",\"top_footer\":\"on\",\"bottom_footer\":\"on\",\"footer_social_media\":\"on\"}', NULL, NULL, NULL),
        (19, 'products', '{\"toggle_addons\":\"0\",\"toggle_variations\":\"0\",\"minimum_products_qty\":\"0\"}', NULL, NULL, NULL);
        ";
        $this->insert($data);
    }

    public function insertCities()
    {
        $this->call(\Database\Seeders\CitiesTableSeeder::class);
    }

    public function insertCountries()
    {
        $this->call(\Database\Seeders\CountriesTableSeeder::class);
    }

    public function insertLtmTranslations()
    {
        $data = "
            INSERT INTO `ltm_translations` (`id`, `status`, `locale`, `group`, `key`, `value`, `created_at`, `updated_at`, `saved_value`, `is_deleted`, `was_used`, `source`, `is_auto_added`) VALUES
            (1, 0, 'ar', 'setting::dashboard', 'password.email.required', NULL, '2020-09-02 12:20:25', '2020-09-02 12:20:25', NULL, 0, 0, NULL, 0),
            (2, 0, 'ar', 'setting::dashboard', 'password.email.email', NULL, '2020-09-02 12:20:25', '2020-09-02 12:20:25', NULL, 0, 0, NULL, 0),
            (3, 0, 'ar', 'setting::dashboard', 'password.email.exists', NULL, '2020-09-02 12:20:25', '2020-09-02 12:20:25', NULL, 0, 0, NULL, 0);
        ";
        $this->insert($data);
    }

    public function inserPages()
    {
        $this->call(PagesTableSeeder::class);
    }

    public function insertRoleAndPermissions()
    {
        $this->call([
            RolesTableSeeder::class,
        ]);
    }

    public function insertUsers()
    {
        $data = "
            INSERT INTO `users` (`id`, `name`, `image`, `calling_code`, `mobile`, `country_id`, `company_id`, `email`, `email_verified_at`, `password`, `remember_token`, `is_verified`, `tocaan_perm`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, 'Super Admin', '/uploads/users/user.png', '965', '12345678', 1, Null, 'admin@admin.com', NULL, '$2y$10\$ZZ92yXvy5ncN1tHUCTCF4.gpaZ2SHPGkwEZmzs0NXsm.JAU63gnWq', '$2y$10\$ZZ92yXvy5ncN1tHUCTCF4.gpaZ2SHPGkwEZmzs0NXsm.JAU63gnWq', 1, 1, NULL, '2019-12-26 07:14:17', '2020-06-30 10:43:06'),
            (2, 'Test Driver 1', '/uploads/users/user.png', '965', '876548765', 1, 1, 'test_driver1@example.com', NULL, '$2y$10\$V4HCpcSYwDZGISxGHxbrYu6OOKzU0TmFksOCnYWHg8TJTZKLLhRVC', NULL, 1, 0, NULL, '2020-08-16 05:50:42', '2020-08-16 05:50:42'),
            (3, 'Test User 1', '/uploads/users/user.png', '965', '12341234', 1, Null, 'test_user1@example.com', NULL, '$2y$10\$93qD4oTvWmG3csxXB50oCeb8Hb8i1WK8tzeufhF.v6rguXmqlJ64u', NULL, 1, 0, NULL, '2020-08-16 05:50:42', '2020-08-16 05:50:42');

        ";
        $this->insert($data);

        $addressQuery = "
            INSERT INTO `addresses` (`id`, `email`, `username`, `mobile`, `block`, `street`, `building`, `address`, `state_id`, `user_id`, `avenue`, `floor`, `flat`, `automated_number`, `is_default`, `created_at`, `updated_at`) VALUES
            (1, 'user1@example.com', 'Omar Mahmoud', '96512345678', '546', 'Omar bin al-khattab', '789', 'Building 12 - Fourth Floor - Apartment 4', 85, 3, 'test aventue', 'test floor', 'test flat', 'test automated number', 1, '2022-07-05 13:11:16', '2022-07-05 13:11:16');
        ";

        $this->insert($addressQuery);
    }

    public function insertSates()
    {
        $this->call(\Database\Seeders\StatesTableSeeder::class);
    }

    public function insertOption()
    {
        $this->call(\Database\Seeders\OptionsTableSeeder::class);
        $this->call(\Database\Seeders\OptionValuesTableSeeder::class);
    }

    public function insertShippingCompany()
    {
        $this->call(\Database\Seeders\CompaniesTableSeeder::class);
        $this->call(\Database\Seeders\CompanyAvailabilitiesTableSeeder::class);
    }

    public function insertOrders()
    {
        $this->call(\Database\Seeders\OrderStatusSeeder::class);
        $this->call(\Database\Seeders\PaymentStatusSeeder::class);
        // $this->call(\Database\Seeders\OrderTableSeeder::class);
    }

    public function insertAds()
    {
        $this->call(\Database\Seeders\AdsTableSeeder::class);
    }

    public function insertSliders()
    {
        $this->call(\Database\Seeders\SliderSeeder::class);
    }
}
