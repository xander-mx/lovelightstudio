<?php

namespace App\Models\Enums;

use Eloquent\Enumeration\AbstractMultiton;

final class EventStatus extends AbstractMultiton
{
  private string $message;

  protected function __construct($key, $message)
  {
    parent::__construct($key);
    $this->message = $message;
  }

  protected static function initializeMembers()
  {
    new EventStatus('EN_PROCESO', 'En proceso');
    new EventStatus('LIQUIDADO', 'Vendidas');
    new EventStatus('CANCELADO', 'Cancelado');
    new EventStatus('FINALIZADO','Finalizado');
  }

  public function message(): string
  {
    return $this->message;
  }

  public static function allMessages(): array
  {
    $messages = array();
    foreach(EventStatus::members() as $member)
    {
      $messages[] = $member->message;
    }
    return $messages;
  }
}