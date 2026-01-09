<?php

// app/Http/Requests/StoreMateriRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMateriRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    public function rules()
    {
        return [
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe_materi' => 'required|in:pdf,video,dokumen,presentasi,lainnya',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,avi,mkv,jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'jadwal_id.required' => 'Jadwal harus dipilih',
            'jadwal_id.exists' => 'Jadwal tidak valid',
            'judul.required' => 'Judul materi harus diisi',
            'tipe_materi.required' => 'Tipe materi harus dipilih',
            'file.required' => 'File harus diupload',
            'file.max' => 'Ukuran file maksimal 10MB',
            'file.mimes' => 'Format file tidak didukung'
        ];
    }
}