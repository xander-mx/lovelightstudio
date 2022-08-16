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
    new EventStatus('PENDIENTE', 'Pendiente');
    new EventStatus('LIQUIDADO', 'Liquidado');
    new EventStatus('CANCELADO', 'Cancelado');
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