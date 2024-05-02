<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class sendOTPViaEmail extends Notification
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
    return (new MailMessage)
            ->subject('Confirmation')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting('One Time Password.')
            ->line("Dear Applicant, {$this->user->otp} is the OTP for Registration to apply at " . env("OTP_APP_NAME"))
            ->line('Don`t share your OTP with anyone.');
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
