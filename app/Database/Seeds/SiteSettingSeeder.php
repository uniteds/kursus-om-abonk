<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['setting_key' => 'site_name', 'setting_value' => 'Belajar IT Bersama Om Abonk', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'site_description', 'setting_value' => 'Platform kursus IT untuk pemula hingga mahir', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'site_tagline', 'setting_value' => 'Platform belajar IT yang mudah, seru, dan terstruktur untuk semua kalangan.', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'site_footer_about', 'setting_value' => 'Hubungi kami: info@ayodaftar.web.id | WhatsApp: 0818-72-11-12', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'site_logo', 'setting_value' => '', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'site_footer', 'setting_value' => '2026 Belajar IT Bersama Om Abonk. All rights reserved.', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('site_settings')->insertBatch($data);
    }
}
