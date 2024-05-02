<?php

namespace App\Mail;

use App\Models\MeritList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SeatWithdrawalRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $merit_list;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MeritList $meritList)
    {
        $meritList->load("admissionCategory", "course", "application");
        $this->merit_list = $meritList;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.withdrawal_request', ["merit_list" => $this->merit_list]);
    }
}
