<?php

namespace App\Traits;

use App\Models\Enums\CommissionStatus;
use App\Utils\ActionButton;

trait CommissionsDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('employee', 'like', '%'. $search .'%')
        ->orWhere('coalesce(reservation.customer,"")', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $commissions = $query->limit($this->searchConfig['length'])->offset( $this->searchConfig['start'] )
      ->orderBy('date', 'desc')->get();
    $data = [];
    foreach($commissions as $commission)
      $data[] = $this->getDataRow($commission);
    return $this->getSearchResult($data);
  }

  private function getDataRow($commission): array
  {
    $authorizeButton = ActionButton::getDefaultEditButton();
    $authorizeButton->addAttribute('data-id', $commission->id);

    $rejectButton = ActionButton::getDefaultButton('bi-clipboard-x', 'btn-danger', 'Rechazar');
    $rejectButton->addAttribute('data-id', $commission->id);

    $badge = "badge";
    switch(CommissionStatus::memberByKey($commission->status)) {
      case CommissionStatus::memberByKey('PENDIENTE'): $badge .= " badge-success"; break;
      case CommissionStatus::memberByKey('CANCELADO') || CommissionStatus::memberByKey('RECHAZADO'):
        $badge .= " badge-danger"; break;
      case CommissionStatus::memberByKey('PAGADO'): $badge = " badge-info"; break;
      default: $badge .= " badge-primary"; break;
    }

    return [
      'date'  => $commission->date,
      'employee'  => $commission->employee,
      'customer' => $commission->customer,
      'concept' => $commission->concept,
      'amount'  => '$ '.number_format($commission->amount,2),
      'status' => '<span class="'.$badge.'">'.CommissionStatus::memberByKey($commission->status)->message().'</span>',
      'actions' => $authorizeButton->getActionButton()."    ".$rejectButton->getActionButton()
    ];
  }
}