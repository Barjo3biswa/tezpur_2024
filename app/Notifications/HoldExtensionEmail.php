<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class HoldExtensionEmail extends Notification
{
    // use Queueable;
    // public $token;
    public $user;
    public $time_period;
    public $full_name;
    public $course_name;
    // public $route_name = "student.email-verify";
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,$time_period,$full_name,$course_name)
    {
        $this->user  = $user;
        $this->time_period = $time_period;
        $this->full_name = $full_name;
        $this->course_name = $course_name;
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
            ->subject('Extension Of Admission Time Period')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting($this->full_name)
            ->line('Your admission process for the course '.$this->course_name.' is extended, time period as follows:') 
            ->line($this->time_period)
            ->line('However, ‘Reporting’ does not ensure a seat for the candidates and admission will be strictly based on merit.')
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
