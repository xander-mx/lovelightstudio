<?php

namespace App\Traits;

use App\Models\Enums\PayrollStatus;
use App\Utils\ActionButton;
use Illuminate\Support\Facades\DB;

trait PayrollDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult($query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('concept', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $payrolls = $query->take($this->searchConfig['length'])->skip( $this->searchConfig['start']);
    $data = [];
    foreach($payrolls as $payroll)
      $data[] = $this->getDataRow($payroll);
    return $this->getSearchResult($data, false);
  }

  private function getDataRow($payroll): array
  {
    $badge = "badge ";
    $authorizeButton = ActionButton::getDefaultButton('bi-hand-thumbs-up', 'btn-primary', 'Autorizar');
    $authorizeButton->setClick('updateStatus(this)');
    $authorizeButton->addAttribute('data-id', $payroll->id);
    $authorizeButton->addAttribute('data-status', 'PAGADO');

    $showButtons = $payroll->status == 'PENDIENTE'
      ? $authorizeButton->getActionButton() : '';

    $badge .= PayrollStatus::memberByKey($payroll->status) == 'PAGADO' ? 'badge-success' : 'badge-info';

    return [
      'created_at' => date('d-m-Y', strtotime($payroll->created_at)),
      'concept' => $payroll->concept,
      'closeDate' => date('d-m-Y', strtotime($payroll->closeDate)),
      'status' => '<span class="'.$badge.'">'.PayrollStatus::memberByKey($payroll->status)->message().'</span>',
      'total' => '$'.number_format($payroll->total, 2),
      'actions' => $showButtons
    ];
  }

  protected function getSearchDataResultNext($query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where(DB::raw('CONCAT(users.name, " ", COALESCE(users.lastname, ""))'), 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $commissions = $query->take($this->searchConfig['length'])->skip( $this->searchConfig['start'] )->get();
    $data = [];
    foreach($commissions as $byUser)
      $data[] = $this->getNextDataRow($byUser);
    return $this->getSearchResult($data);
  }

  private function getNextDataRow($payroll): array
  {
    return [
      'employee' => $payroll->employee,
      'total' => '$'.number_format($payroll->amount, 2)
    ];
  }
}