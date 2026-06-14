<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorLogModel extends Model
{
    protected $table = 'visitor_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    protected $allowedFields = ['ip_address', 'user_agent', 'url', 'method', 'referer', 'user_id', 'is_unique', 'created_at'];

    public function logVisit(array $data): void
    {
        $this->insert($data);
    }

    public function countToday(): int
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
    }

    public function countThisMonth(): int
    {
        return $this->where('MONTH(created_at)', date('m'))
                     ->where('YEAR(created_at)', date('Y'))
                     ->countAllResults();
    }

    public function countUniqueToday(): int
    {
        return $this->select('COUNT(DISTINCT ip_address) as total')
                     ->where('DATE(created_at)', date('Y-m-d'))
                     ->get()->getRow()->total ?? 0;
    }

    public function countUniqueThisMonth(): int
    {
        return $this->select('COUNT(DISTINCT ip_address) as total')
                     ->where('MONTH(created_at)', date('m'))
                     ->where('YEAR(created_at)', date('Y'))
                     ->get()->getRow()->total ?? 0;
    }

    public function getDailyStats(int $days = 7): array
    {
        $results = $this->select('DATE(created_at) as date, COUNT(*) as total, COUNT(DISTINCT ip_address) as unique_visitors')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime("-{$days} days")))
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'ASC')
            ->findAll();

        $stats = [];
        foreach ($results as $row) {
            $stats[$row->date] = [
                'total'   => (int)$row->total,
                'unique'  => (int)$row->unique_visitors,
            ];
        }
        return $stats;
    }

    public function getTopPages(int $limit = 10): array
    {
        return $this->select('url, COUNT(*) as hits')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-30 days')))
            ->where('method', 'GET')
            ->groupBy('url')
            ->orderBy('hits', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getTopReferers(int $limit = 10): array
    {
        return $this->select('referer, COUNT(*) as hits')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-30 days')))
            ->where('referer IS NOT NULL')
            ->where('referer !=', '')
            ->groupBy('referer')
            ->orderBy('hits', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getHourlyStats(): array
    {
        $results = $this->select('HOUR(created_at) as hour, COUNT(*) as total')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->groupBy('HOUR(created_at)')
            ->orderBy('hour', 'ASC')
            ->findAll();

        $stats = array_fill(0, 24, 0);
        foreach ($results as $row) {
            $stats[(int)$row->hour] = (int)$row->total;
        }
        return $stats;
    }

    public function getTotalAllTime(): int
    {
        return $this->countAllResults();
    }

    public function getUniqueAllTime(): int
    {
        return $this->select('COUNT(DISTINCT ip_address) as total')
                     ->get()->getRow()->total ?? 0;
    }
}
