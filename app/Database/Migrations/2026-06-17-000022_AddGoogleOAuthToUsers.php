<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleOAuthToUsers extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `users`
            ADD COLUMN `google_id` VARCHAR(255) NULL AFTER `email`,
            ADD COLUMN `avatar_url` VARCHAR(500) NULL AFTER `avatar`
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `users`
            DROP COLUMN `google_id`,
            DROP COLUMN `avatar_url`
        ");
    }
}
