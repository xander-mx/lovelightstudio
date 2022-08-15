<?php

namespace App\Models\Enums;

use Eloquent\Enumeration\AbstractMultiton;

final class PaymentType extends AbstractMultiton
{
  private string $message;

  protected function __construct($key, $message)
  {
    parent::__construct($key);
    $this->message = $message;
  }

  protected static function initializeMembers()
  {
    new PaymentType('EFECTIVO', 'Efectivo');
    new PaymentType('DEBITO', 'Tarjeta de Débito');
    new PaymentType('CREDITO', 'Tarjeta de Crédito');
    new PaymentType('DEPOSITO', 'Depósito');
    new PaymentType('PAYPAL', 'PayPal');
    new PaymentType('MISCELANEO', 'Miscelaneo');
  }

  public function message(): string
  {
    return $this->message;
  }

  public static function allMessages(): array
  {
    $messages = array();
    foreach(PaymentType::members() as $member)
    {
      $messages[] = $member->message;
    }
    return $messages;
  }
}