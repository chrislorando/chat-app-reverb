<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushMessageBrowser extends Notification 
{
    use Queueable;

    public $icon, $title, $body;

    /**
     * Create a new notification instance.
     */
    public function __construct($icon, $title, $body)
    {
        $this->icon = $icon;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
        ->title($this->title)
            ->body($this->body)
            ->icon($this->icon)
            ->badge($this->icon)
            ->action('View App', 'notification_action')
            // ->data(['id' => $notification->id])
            ->options(['TTL' => 3600]);
            // ->title('Approved!')
            
            // ->body('Your account was approved!')
            // ->action('View account', 'view_account')
            // ->options(['TTL' => 1000]);
            // ->data(['id' => $notification->id])
            // ->badge()
            // ->dir()
            // ->image()
            // ->lang()
            // ->renotify()
            // ->requireInteraction()
            // ->tag()
            // ->vibrate()
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
