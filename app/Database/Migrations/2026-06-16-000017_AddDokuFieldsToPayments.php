<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDokuFieldsToPayments extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `payments`
            ADD COLUMN `invoice_number` VARCHAR(64) NULL AFTER `class_id`,
            ADD COLUMN `doku_session_id` VARCHAR(128) NULL AFTER `invoice_number`,
            ADD COLUMN `doku_token_id` VARCHAR(128) NULL AFTER `doku_session_id`,
            ADD COLUMN `doku_payment_url` TEXT NULL AFTER `doku_token_id`,
            ADD COLUMN `payment_channel` VARCHAR(50) NULL AFTER `doku_payment_url`,
            ADD COLUMN `external_id` VARCHAR(64) NULL AFTER `payment_channel`
        ");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `payments`
            DROP COLUMN `invoice_number`,
            DROP COLUMN `doku_session_id`,
            DROP COLUMN `doku_token_id`,
            DROP COLUMN `doku_payment_url`,
            DROP COLUMN `payment_channel`,
            DROP COLUMN `external_id`
        ");
    }
}
