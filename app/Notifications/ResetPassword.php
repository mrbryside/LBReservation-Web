<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Reset Password')
            ->line('คุณได้รับอีเมลล์ฉบับนี้ เนื่องจากคุณได้ร้องขอเพื่อเปลี่ยนพาสเวิร์ดของ account ของคุณ โปรดคลิกที่ปุ่มด้านล่าง เพื่อเข้าสู่กระบวนการเปลี่ยนพาสเวิร์ด')
            ->action('Reset Password', url('password/reset', $this->token))
            ->line('หากคุณไม่ได้ขอให้รีเซ็ตรหัสผ่านคุณไม่จำเป็นต้องดำเนินการใดๆ อีก');
    }
}