<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class sendToZZZ extends Notification
{
    // use Queueable;
    public $message;
    public $user;
    public $cc;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $message, $cc)
    {
        $this->user  = $user;
        $this->message = $message;
        $this->cc = json_decode($cc);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // dd(route($this->route_name));
        return (new MailMessage)
            ->subject('Information')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting('Information')
            ->line($this->message)
            ->line('Do Not Reply To This Mail.')
            ->cc($this->cc);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
