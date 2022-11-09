<?php

namespace App\Traits;


use App\Utils\ActionButton;

trait ConfigurationsDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult($query): array
  {
    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('name', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $configurations = $query->take($this->searchConfig['length'])->skip( $this->searchConfig['start'] );
    $data = [];
    foreach($configurations as $configuration)
      $data[] = $this->getDataRow($configuration);
    return $this->getSearchResult($data, false);
  }

  private function getDataRow($configuration): array
  {
    $editBtn = ActionButton::getDefaultEditButton();
    $editBtn->addAttribute('data-id', $configuration->id);
    $editBtn->setClick('editConfiguration(this)');

    return [
      'name' => $configuration->name,
      'value' => $configuration->value,
      'actions' => $editBtn->getActionButton()
    ];
  }
}