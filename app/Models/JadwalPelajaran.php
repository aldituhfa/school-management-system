<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mata_pelajaran_id',
        'hari',
        'jam_ke',
        'waktu_mulai',
        'waktu_selesai',
        'keterangan',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Guru (User)
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi ke Mata Pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Scope untuk filter berdasarkan guru
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    // Scope untuk filter berdasarkan kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk filter berdasarkan hari
    public function scopeByHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    // Cek apakah ada jadwal bentrok untuk guru
    public static function cekBentrokGuru($guruId, $hari, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('guru_id', $guruId)
            ->where('hari', $hari)
            ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                // Cek overlap waktu
                $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                  ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                  ->orWhere(function($q2) use ($waktuMulai, $waktuSelesai) {
                      $q2->where('waktu_mulai', '<=', $waktuMulai)
                         ->where('waktu_selesai', '>=', $waktuSelesai);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Cek apakah ada jadwal bentrok untuk kelas
    public static function cekBentrokKelas($kelasId, $hari, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->where(function($q) use ($waktuMulai, $waktuSelesai) {
                $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                  ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                  ->orWhere(function($q2) use ($waktuMulai, $waktuSelesai) {
                      $q2->where('waktu_mulai', '<=', $waktuMulai)
                         ->where('waktu_selesai', '>=', $waktuSelesai);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Get jam ke berikutnya untuk kelas dan hari tertentu
    public static function getNextJamKe($kelasId, $hari)
    {
        $maxJam = self::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->max('jam_ke');

        return $maxJam ? $maxJam + 1 : 1;
    }

    // Cek apakah waktu bentrok dengan istirahat
    public static function cekBentrokIstirahat($kelasId, $waktuMulai, $waktuSelesai)
    {
        $jamBelajar = JamBelajar::where('kelas_id', $kelasId)->first();
        
        if (!$jamBelajar) {
            return false;
        }

        // Cek istirahat 1
        if ($jamBelajar->waktu_istirahat_mulai && $jamBelajar->waktu_istirahat_selesai) {
            $istirahat1Mulai = Carbon::parse($jamBelajar->waktu_istirahat_mulai)->format('H:i');
            $istirahat1Selesai = Carbon::parse($jamBelajar->waktu_istirahat_selesai)->format('H:i');

            if (self::isTimeOverlap($waktuMulai, $waktuSelesai, $istirahat1Mulai, $istirahat1Selesai)) {
                return 'Waktu bentrok dengan istirahat 1 (' . $istirahat1Mulai . ' - ' . $istirahat1Selesai . ')';
            }
        }

        // Cek istirahat 2
        if ($jamBelajar->waktu_istirahat2_mulai && $jamBelajar->waktu_istirahat2_selesai) {
            $istirahat2Mulai = Carbon::parse($jamBelajar->waktu_istirahat2_mulai)->format('H:i');
            $istirahat2Selesai = Carbon::parse($jamBelajar->waktu_istirahat2_selesai)->format('H:i');

            if (self::isTimeOverlap($waktuMulai, $waktuSelesai, $istirahat2Mulai, $istirahat2Selesai)) {
                return 'Waktu bentrok dengan istirahat 2 (' . $istirahat2Mulai . ' - ' . $istirahat2Selesai . ')';
            }
        }

        return false;
    }

    // Helper function untuk cek overlap waktu
    private static function isTimeOverlap($start1, $end1, $start2, $end2)
    {
        return ($start1 < $end2 && $end1 > $start2);
    }

    // Validasi waktu dalam batas jam belajar kelas
    public static function validasiWaktuJamBelajar($kelasId, $waktuMulai, $waktuSelesai)
    {
        $jamBelajar = JamBelajar::where('kelas_id', $kelasId)->first();
        
        if (!$jamBelajar) {
            return 'Data jam belajar untuk kelas ini belum diatur';
        }

        $jamMulaiKelas = Carbon::parse($jamBelajar->jam_mulai)->format('H:i');
        $jamSelesaiKelas = Carbon::parse($jamBelajar->jam_selesai)->format('H:i');

        if ($waktuMulai < $jamMulaiKelas || $waktuSelesai > $jamSelesaiKelas) {
            return 'Waktu harus dalam rentang jam belajar kelas (' . $jamMulaiKelas . ' - ' . $jamSelesaiKelas . ')';
        }

        return true;
    }
}