<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    protected $fillable = [
        'kode',
        'unit',
        'kategori',
        'risiko',
        'dampak_deskripsi',
        'penyebab',
        'awal_d',
        'awal_p',
        'pengendalian',
        'evaluasi',
        'residual_d',
        'residual_p',
        'pj',
        'status',
        'validasi',
        'validator',
        'triwulan',
        'period_year',
    ];

    protected $appends = [
        'awal_skor',
        'awal_level',
        'residual_skor',
        'residual_level',
    ];

    /**
     * Calculate the initial risk score (Impact × Probability)
     */
    public function getAwalSkorAttribute(): int
    {
        return $this->awal_d * $this->awal_p;
    }

    /**
     * Calculate the initial risk level based on score
     */
    public function getAwalLevelAttribute(): string
    {
        return self::calculateLevel($this->awal_skor);
    }

    /**
     * Calculate the residual risk score (Impact × Probability)
     */
    public function getResidualSkorAttribute(): int
    {
        return $this->residual_d * $this->residual_p;
    }

    /**
     * Calculate the residual risk level based on score
     */
    public function getResidualLevelAttribute(): string
    {
        return self::calculateLevel($this->residual_skor);
    }

    /**
     * Calculate risk level based on score
     */
    public static function calculateLevel(int $score): string
    {
        if ($score >= 15) return 'Kritis';
        if ($score >= 10) return 'Tinggi';
        if ($score >= 5) return 'Sedang';
        return 'Rendah';
    }

    /**
     * Generate the next risk code
     */
    public static function generateNextKode(): string
    {
        $lastRisk = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastRisk ? intval(substr($lastRisk->kode, 2)) + 1 : 1;
        return 'R-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
