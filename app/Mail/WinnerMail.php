<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WinnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user,$participant,$contest,$amount;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$participant,$contest)
    {
        //dd($user,$participant->amount,$contest);
        $this->user = $user;
        $this->participant = $participant;
        $this->contest = $contest;
        $this->amount = $participant->amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.winner');
    }
}
