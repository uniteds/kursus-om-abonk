<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip_address'    => ['type' => 'VARCHAR', 'constraint' => 45],
            'user_agent'    => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'url'           => ['type' => 'VARCHAR', 'constraint' => 500],
            'method'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'referer'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'is_unique'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'comment' => '1=first visit today from this IP'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('created_at');
        $this->forge->addKey('ip_address');
        $this->forge->addKey('url');
        $this->forge->createTable('visitor_logs');
    }

    public function down()
    {
        $this->forge->dropTable('visitor_logs');
    }
}
