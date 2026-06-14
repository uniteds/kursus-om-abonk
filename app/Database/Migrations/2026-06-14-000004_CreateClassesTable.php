<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'course_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'schedule'      => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'capacity'      => ['type' => 'INT', 'constraint' => 11, 'default' => 30],
            'status'        => ['type' => "ENUM('upcoming','ongoing','completed')", 'default' => 'upcoming'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('classes');
    }

    public function down()
    {
        $this->forge->dropTable('classes');
    }
}
