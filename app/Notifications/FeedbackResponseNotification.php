<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackResponseNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $response;
  

    public function __construct($response)
    {
        //
        $this->response = $response;
     
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Réponse à votre feedback')
        ->line('Nous avons répondu à votre feedback.')
        ->line('Voici notre réponse :')
        ->line($this->response)
        // ->action('Voir votre feedback', url('/feedbacks')) // Lien vers la page des feedbacks
        ->line('Merci d\'avoir pris le temps de nous donner votre avis !');
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
