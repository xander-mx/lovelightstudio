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
      ->orderBy('status')->orderBy('date', 'desc')->get();
    $data = [];
    foreach($commissions as $commission)
      $data[] = $this->getDataRow($commission);
    return $this->getSearchResult($data, false);
  }

  private function getDataRow($commission): array
  {
    $authorizeButton = ActionButton::getDefaultButton('bi-hand-thumbs-up', 'btn-primary', 'Autorizar');
    $authorizeButton->addAttribute('data-id', $commission->id);

    $rejectButton = ActionButton::getDefaultButton('bi-hand-thumbs-down', 'btn-danger', 'Rechazar');
    $rejectButton->addAttribute('data-id', $commission->id);

    $badge = "badge";
    $showButtons = "";
    switch(CommissionStatus::memberByKey($commission->status)) {
      case CommissionStatus::memberByKey('PENDIENTE'): $badge .= " badge-success";
        $showButtons = $authorizeButton->getActionButton()."    ".$rejectButton->getActionButton();break;
      case CommissionStatus::memberByKey('CANCELADO'):
      case CommissionStatus::memberByKey('RECHAZADO'):
        $badge .= " badge-danger"; break;
      case CommissionStatus::memberByKey('PAGADO'): $badge = " badge-info"; break;
      default: $badge .= " badge-primary"; break;
    }

    return [
      'date'  => date('d-m-Y',strtotime($commission->date)),
      'employee'  => $commission->employee,
      'customer' => $commission->customer,
      'concept' => $commission->concept,
      'amount'  => '$ '.number_format($commission->amount,2),
      'status' => '<span class="'.$badge.'">'.CommissionStatus::memberByKey($commission->status)->message().'</span>',
      'actions' => $showButtons
    ];
  }
}