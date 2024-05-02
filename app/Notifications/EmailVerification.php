<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerification extends Notification
{
    // use Queueable;
    public $token;
    public $user;
    public $route_name = "student.email-verify";
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user  = $user;
        $this->token = $token;
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
            ->subject('Confirmation')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting('Verify your email address.')
            ->line('Please confirm that you want to use this as the '.env("OTP_APP_NAME").' account email address. Once it\'s done you will be allowed to proceed for application processing.')
            ->action('Verify my email', route($this->route_name, ["token" => $this->token, "email" => $this->user->email]))
            ->line('If you already verified, no further action is required.');
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
