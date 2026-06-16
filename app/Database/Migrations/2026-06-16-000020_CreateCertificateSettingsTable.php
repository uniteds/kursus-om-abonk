<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCertificateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'signer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => '',
            ],
            'signer_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => 'Kepala Platform',
            ],
            'certificate_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => 'Sertifikat Pelatihan',
            ],
            'certificate_subtitle' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'default'    => 'SELESAI PELATIHAN',
            ],
            'border_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '#1a5276',
            ],
            'accent_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => '#d4ac0d',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('certificate_settings');
    }

    public function down()
    {
        $this->forge->dropTable('certificate_settings');
    }
}
