<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WarningMail extends Notification
{
    use Queueable;

    public $user;
    public $time_period;
    public $full_name;
    public $course_name;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $time_period, $full_name, $course_name, $message)
    {
        $this->user  = $user;
        $this->time_period = $time_period;
        $this->full_name = $full_name;
        $this->course_name = $course_name;
        $this->message = $message;
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
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return array
    //  */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }
    public function toMail($notifiable)
    {
        // dd(route($this->route_name));
        return (new MailMessage)
            ->subject('Cancellation Warning')
            ->level("success")
            ->from(env('MAIL_USERNAME'), env("INSTITUTE_NAME"))
            ->greeting('Dear Applicant')
            // ->line('Your application for ' . $this->course_name . ' is cancelled.')
            // ->line('This ia a warning mail that your application for ' . $this->course_name . ' will be cancelled.')
            ->line($this->message)
            ->line('Kindly Note : Reporting does not ensure a seat for the candidates and admission will be strictly based on merit.')
            ->line('Best wishes,')
            ->line('Online Support Tezupur University Registration and Admission Portal');
            // ->line('Please do not reply to this mail.');
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
