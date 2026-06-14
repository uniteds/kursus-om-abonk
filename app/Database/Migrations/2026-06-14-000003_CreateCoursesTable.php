<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 200, 'unique' => true],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'thumbnail'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id', false, false, 'FK_courses_category');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('courses');
    }

    public function down()
    {
        $this->forge->dropTable('courses');
    }
}
