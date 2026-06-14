<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpgradeAnnouncementsForPublic extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE announcements DROP FOREIGN KEY announcements_class_id_foreign');
        $this->db->query('ALTER TABLE announcements MODIFY class_id INT(11) UNSIGNED NULL');

        $this->db->query('ALTER TABLE announcements
            ADD COLUMN type ENUM(\'umum\',\'kelas\',\'diskon\',\'event\',\'lainnya\') DEFAULT \'umum\' AFTER title,
            ADD COLUMN icon VARCHAR(50) DEFAULT \'fas fa-bullhorn\' AFTER type,
            ADD COLUMN color VARCHAR(20) DEFAULT \'primary\' AFTER icon,
            ADD COLUMN target ENUM(\'semua\',\'member\') DEFAULT \'semua\' AFTER color,
            ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER target,
            ADD COLUMN published_at DATETIME NULL AFTER is_active
        ');

        $this->db->query('UPDATE announcements SET published_at = created_at WHERE published_at IS NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE announcements
            DROP COLUMN type, DROP COLUMN icon, DROP COLUMN color,
            DROP COLUMN target, DROP COLUMN is_active, DROP COLUMN published_at
        ');
        $this->db->query('ALTER TABLE announcements MODIFY class_id INT(11) UNSIGNED NOT NULL');
        $this->db->query('ALTER TABLE announcements ADD FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
