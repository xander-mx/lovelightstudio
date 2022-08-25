<?php

namespace App\Mail;

use App\Models\Reservations;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailingTo extends Mailable
{
  use Queueable, SerializesModels;
  private Reservations $reserve;
  private String $toView;
  private String $affair;

  public function __construct(Reservations $reserve, String $toView, String $affair)
  {
    $this->reserve = $reserve;
    $this->toView = $toView;
    $this->affair = $affair;
  }

  public function build(): EmailingTo
  {
    return $this->view($this->toView)->subject($this->affair);
  }
}