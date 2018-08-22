<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActivateUserEmail2 extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [
            'mail'
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = 'Tài khoản của bạn vừa mới bị block bởi admin';
        if ($this->status == USER_STATUS_ACTIVE) {
            $msg = 'Tài khoản của bạn vừa mới được kích hoạt bởi admin';
        }

        return (new MailMessage)->line($msg);
    }
}