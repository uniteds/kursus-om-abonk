<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassMaterialsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'class_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'type'          => ['type' => "ENUM('document','video','link','slide','tugas','other')", 'default' => 'document'],
            'file_path'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'external_url'  => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'sort_order'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'downloads'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_published'  => ['type' => 'TINYINT(1)', 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_materials');
    }

    public function down()
    {
        $this->forge->dropTable('class_materials');
    }
}
