<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
    ];

    /**
     * Kolom yang disembunyikan ketika serialisasi (misal: ke JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi dengan tabel Finance.
     */
    public function finances()
    {
        return $this->hasMany(Finance::class);
    }

    /**
     * Relasi dengan tabel FinanceLog.
     */
    public function financeLogs()
    {
        return $this->hasMany(FinanceLog::class);
    }

    /**
     * Cek role user.
     */
    public function hasRole(...$roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * Accessor untuk mendapatkan URL foto profil.
     * Akan otomatis mengembalikan gambar default jika tidak ada foto profil.
     */
    protected $appends = ['profile_photo_url'];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }

        // gunakan gambar default di public/images/default-profile.png
        return asset('images/default-profile.png');
    }

    /**
     * Hapus foto profil dari storage (dipakai saat reset foto profil).
     */
    public function deleteProfilePhoto()
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            Storage::disk('public')->delete($this->profile_photo);
        }

        $this->update(['profile_photo' => null]);
    }

    public function mataPelajaran()
{
    return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajaran', 'guru_id', 'mata_pelajaran_id');
}

}
