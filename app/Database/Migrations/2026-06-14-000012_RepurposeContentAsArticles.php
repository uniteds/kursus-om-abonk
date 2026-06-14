<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepurposeContentAsArticles extends Migration
{
    public function up()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE content DROP FOREIGN KEY content_class_id_foreign');

        // Make class_id nullable
        $this->db->query('ALTER TABLE content MODIFY class_id INT(11) UNSIGNED NULL');

        // Add article columns
        $this->db->query('ALTER TABLE content
            ADD COLUMN slug VARCHAR(255) NULL AFTER title,
            ADD COLUMN excerpt TEXT NULL AFTER description,
            ADD COLUMN body LONGTEXT NULL AFTER excerpt,
            ADD COLUMN category ENUM(\'berita\',\'tutorial\',\'artikel\') DEFAULT \'artikel\' AFTER body,
            ADD COLUMN thumbnail VARCHAR(255) NULL AFTER category,
            ADD COLUMN is_published TINYINT(1) DEFAULT 0 AFTER thumbnail,
            ADD COLUMN published_at DATETIME NULL AFTER is_published,
            ADD COLUMN views INT(11) DEFAULT 0 AFTER published_at
        ');

        // Add unique key on slug
        $this->db->query('ALTER TABLE content ADD UNIQUE KEY idx_slug (slug)');

        // Change description to be alias/excerpt-friendly (keep for backward compat)
        $this->db->query('ALTER TABLE content MODIFY description TEXT NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE content DROP COLUMN slug, DROP COLUMN excerpt, DROP COLUMN body, DROP COLUMN category, DROP COLUMN thumbnail, DROP COLUMN is_published, DROP COLUMN published_at, DROP COLUMN views');
        $this->db->query('ALTER TABLE content MODIFY class_id INT(11) UNSIGNED NOT NULL');
        $this->db->query('ALTER TABLE content DROP KEY idx_slug');
        $this->db->query('ALTER TABLE content ADD FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
