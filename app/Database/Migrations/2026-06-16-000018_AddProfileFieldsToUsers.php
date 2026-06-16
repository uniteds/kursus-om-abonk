<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'phone',
            ],
            'bio' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'whatsapp',
            ],
            'address' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'bio',
            ],
            'date_of_birth' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'address',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['whatsapp', 'bio', 'address', 'date_of_birth']);
    }
}
