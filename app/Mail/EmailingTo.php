<?php

namespace App\Mail;

use App\Models\Events;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailingTo extends Mailable
{
  use Queueable, SerializesModels;
  private Events $event;
  private String $toView;
  private String $affair;

  public function __construct(Events $event, String $toView, String $affair, String $fromAddress, String $name)
  {
    $this->event = $event;
    $this->toView = $toView;
    $this->affair = $affair;
    $this->from = array(array('address' => $fromAddress, 'name' => $name));
  }

  public function build(): EmailingTo
  {
    return $this->view($this->toView, array('event' => $this->event))->subject($this->affair);
  }
}