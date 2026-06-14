<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationToUsers extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `users`
            ADD COLUMN `email_verified_at` DATETIME NULL AFTER `is_active`,
            ADD COLUMN `reset_token` VARCHAR(255) NULL AFTER `email_verified_at`,
            ADD COLUMN `reset_expires` DATETIME NULL AFTER `reset_token`
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `users`
            DROP COLUMN `email_verified_at`,
            DROP COLUMN `reset_token`,
            DROP COLUMN `reset_expires`
        ");
    }
}
