<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction,$user,$participant,$contest;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaction,$user,$participant,$contest)
    {
        //dd($user,$participant->amount,$contest);
        $this->user = $user;
        $this->participant = $participant;
        $this->contest = $contest;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.transaction');
    }
}
