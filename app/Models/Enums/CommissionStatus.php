<?php

namespace App\Models\Enums;

use Eloquent\Enumeration\AbstractMultiton;

class CommissionStatus extends AbstractMultiton
{
  private string $message;

  protected function __construct($key, $message)
  {
    parent::__construct($key);
    $this->message = $message;
  }

  protected static function initializeMembers()
  {
    new CommissionStatus('PENDIENTE', 'En proceso');
    new CommissionStatus('AUTORIZADO', 'Autorizado');
    new CommissionStatus('RECHAZADO', 'Rechazado');
    new CommissionStatus('CANCELADO', 'Rechazado');
    new CommissionStatus('PAGADO', 'Pagado');
  }

  public function message(): string
  {
    return $this->message;
  }

  public static function allMessages(): array
  {
    $messages = array();
    foreach(CommissionStatus::members() as $member)
    {
      $messages[] = $member->message;
    }
    return $messages;
  }
}