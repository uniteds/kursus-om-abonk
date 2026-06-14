<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['setting_key', 'setting_value'];

    public function getSetting(string $key): ?string
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row->setting_value : null;
    }

    public function setSetting(string $key, string $value): void
    {
        $existing = $this->where('setting_key', $key)->first();
        if ($existing) {
            $this->update($existing->id, ['setting_value' => $value]);
        } else {
            $this->insert(['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public function getAllSettings(): array
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $s) {
            $result[$s->setting_key] = $s->setting_value;
        }
        return $result;
    }
}
