<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLogoToCertificateSettings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('certificate_settings', [
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('certificate_settings', ['logo']);
    }
}
