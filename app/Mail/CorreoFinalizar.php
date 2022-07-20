<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreoFinalizar extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reservainfo;
    public $reserva;
    public function __construct($reservainfo3,$reserva)
    {
        //
        $this->reservainfo =$reservainfo3;
        $this->reserva =$reserva;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.finalizar')
        ->subject("Reserva finalizada");

    }
}
