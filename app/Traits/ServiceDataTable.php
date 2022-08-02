<?php

namespace App\Traits;

use App\Utils\ActionButton;

trait ServiceDataTable
{
  use BaseDataTable;

  protected function getSearchDataResult(&$query) {

    if ( !is_null( $this->searchConfig['search'] ) ) {
      $search = strtoupper( $this->searchConfig['search'] );
      $query->where('name', 'like', '%'. $search .'%');
    }

    $this->searchConfig['recordsFiltered'] = $query->count();
    $services = $query->limit( $this->searchConfig['length'] )->offset( $this->searchConfig['start'] )
      ->orderBy('name', 'desc')->get();
    $data = [];
    foreach($services as $service)
      $data[] = $this->getDataRow($service);
    return $this->getSearchResult($data);
  }

  private function getDataRow($service) {
    $editButton = new ActionButton();
    $editButton->setIcon(" bi bi-pencil-square");
    $editButton->removeClassButton('glyphicon');
    $editButton->removeClassButton('btn-info');
    $editButton->removeClassButton('btn-xs');
    $editButton->addClassButton(' btn-warning');
    $editButton->setToolTip("Editar");
    $editButton->addAttribute('data-id', $service->id);
    $editButton->setClick("editService(this)");

    $deleteButton = new ActionButton();
    $deleteButton->setIcon(" bi bi-trash");
    $deleteButton->removeClassButton('glyphicon');
    $deleteButton->removeClassButton('btn-info');
    $deleteButton->removeClassButton('btn-xs');
    $deleteButton->addClassButton(' btn-danger');
    $deleteButton->setToolTip("Eliminar");
    $deleteButton->addAttribute('data-id', $service->id);
    $deleteButton->setClick("deleteService(this)");

    return [
      'name' => $service->name,
      'description' => $service->description,
      'price' => '$ '.number_format($service->price,2),
      'dlls' => '$ '.number_format($service->price_dlls,2),
      'actions' => $editButton->getIconButton()."    ". $deleteButton->getIconButton()
    ];
  }

}