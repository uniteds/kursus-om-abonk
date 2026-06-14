<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseDetails extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `courses` 
            ADD COLUMN `meetings_count` INT(11) UNSIGNED NULL AFTER `thumbnail`,
            ADD COLUMN `meeting_duration` INT(11) UNSIGNED NULL COMMENT 'menit' AFTER `meetings_count`,
            ADD COLUMN `curriculum` TEXT NULL AFTER `meeting_duration`
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `courses` 
            DROP COLUMN `meetings_count`,
            DROP COLUMN `meeting_duration`,
            DROP COLUMN `curriculum`
        ");
    }
}
