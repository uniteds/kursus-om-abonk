<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceToCourses extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `courses`
            ADD COLUMN `price` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `curriculum`
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `courses`
            DROP COLUMN `price`
        ");
    }
}
