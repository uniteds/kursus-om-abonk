<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'class_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'amount'          => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'payment_method'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bank_name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'account_name'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'proof_image'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'          => ['type' => "ENUM('pending','approved','rejected')", 'default' => 'pending'],
            'notes'           => ['type' => 'TEXT', 'null' => true],
            'admin_notes'     => ['type' => 'TEXT', 'null' => true],
            'paid_at'         => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
