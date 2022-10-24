<?php

namespace App\Traits;

use App\Utils\ActionButton;

trait PayrollDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('concept', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $payrolls = $query->take($this->searchConfig['length'])->skip( $this->searchConfig['start'] );
    $data = [];
    foreach($payrolls as $payroll)
      $data[] = $this->getDataRow($payroll);
    return $this->getSearchResult($data, false);
  }

  private function getDataRow($payroll): array
  {
    $authorizeButton = ActionButton::getDefaultButton('bi-hand-thumbs-up', 'btn-primary', 'Autorizar');

    return [
      'created_at' => date('d-m-Y', $payroll->created_at),
      'concept' => $payroll->concept,
      'closeDate' => date('d-m-Y', $payroll->closeDate),
      'total' => '$'.number_format($payroll->total, 2),
      'actions' => $authorizeButton->getActionButton()
    ];

  }
}