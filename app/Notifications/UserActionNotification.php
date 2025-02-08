<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserActionNotification extends Notification
{
    use Queueable;

    public $action;
    public $details;

    public function __construct($action, $details)
    {
        $this->action = $action;
        $this->details = $details;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Actividad en tu cuenta')
            ->line('Se ha detectado la siguiente actividad en tu cuenta:')
            ->line($this->action)
            ->line($this->details)
            ->action('Ver mi perfil', url('/profile'));
    }
}
