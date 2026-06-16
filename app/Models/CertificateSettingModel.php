<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateSettingModel extends Model
{
    protected $table = 'certificate_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'logo', 'signer_name', 'signer_title', 'certificate_title',
        'certificate_subtitle', 'border_color', 'accent_color',
    ];

    public function getFirst()
    {
        $row = $this->first();
        if (!$row) {
            $defaults = [
                'logo'                 => null,
                'signer_name'          => '',
                'signer_title'         => 'Kepala Platform',
                'certificate_title'   => 'Sertifikat Pelatihan',
                'certificate_subtitle'=> 'SELESAI PELATIHAN',
                'border_color'        => '#1a5276',
                'accent_color'        => '#d4ac0d',
            ];
            $this->insert($defaults);
            return $this->first();
        }
        return $row;
    }
}
