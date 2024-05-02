<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class sendMailAHR extends Notification
{
    // use Queueable;
    // public $token;
    public $user;
    // public $route_name = "student.email-verify";
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user  = $user;
        // $this->token = $token;
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
            ->subject('Application Verification')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting('Your application is in hold')
            ->line('Please login to tezuadmissions portal and update details as per the requirements.') 
            ->line('Best wishes,')
            ->line('Online Support Tezupur University Registration and Admission Portal')            
            ->line('Please do not reply to this mail.');    
            // ->line('Please do not replay to this mail, best wishes online support tezupur university registration and admission portal.');
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
