<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlipGajiDikirimNotification extends Notification
{
    use Queueable;

    protected $slip;

    public function __construct($slip)
    {
        $this->slip = $slip;
    }

    public function via($notifiable)
    {
        return ['database']; // hanya notif database
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Slip Gaji Baru',
            'message' => 'Slip gaji periode '
                . $this->slip->period->bulan . '/'
                . $this->slip->period->tahun
                . ' sudah tersedia.',
            'slip_id' => $this->slip->id,
            'lihat_url' => route('slip_gaji.index', [
                'selected' => $this->slip->id
            ]),
            'unduh_url' => route('slip_gaji.download', $this->slip->id),
        ];
    }
}
