<?php

namespace App\Models\Enums;

use Eloquent\Enumeration\AbstractMultiton;

class PayrollStatus extends AbstractMultiton
{
  private string $message;

  protected function __construct($key, $message)
  {
    parent::__construct($key);
    $this->message = $message;
  }

  protected static function initializeMembers()
  {
    new PayrollStatus('PENDIENTE', 'En proceso');
    new PayrollStatus('PAGADO', 'Pagado');
  }

  public function message(): string
  {
    return $this->message;
  }

  public static function allMessages(): array
  {
    $messages = array();
    foreach(PayrollStatus::members() as $member)
    {
      $messages[] = $member->message;
    }
    return $messages;
  }
}