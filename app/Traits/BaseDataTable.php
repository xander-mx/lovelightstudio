<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait BaseDataTable
{
  protected $searchConfig = [
    'draw'            => null,
    'start'           => null,
    'length'          => null,
    'columns'         => null,
    'order'           => null,
    'search'          => null,
    'orderBy'         => null,
    'orderByColumn'   => null,
    'orderByDir'      => null,
    'filtered'        => false,
    'recordsTotal'    => 0,
    'recordsFiltered' => 0,
    'status'
  ];

  protected function setUpSearchConfig(&$request)
  {
    $this->searchConfig['draw']                 = $request->get('draw');
    $this->searchConfig['start']                = $request->get('start');
    $this->searchConfig['length']               = $request->get('length');
    $this->searchConfig['columns']              = $request->get('columns');
    $this->searchConfig['order']                = $request->get('order');
    $this->searchConfig['search']               = $request->get('search')['value'];
    $this->searchConfig['orderBy']              = $this->searchConfig['columns'][ $this->searchConfig['order'][0]['column'] ];
    $this->searchConfig['orderByColumn']        = $this->searchConfig['orderBy']['data'];
    $this->searchConfig['orderByDir']           = $this->searchConfig['order'][0]['dir'];
    $this->searchConfig['status']               = $request->get('status');
    $this->searchConfig['filtered']             = false;
  }

  protected function getSortedData(array &$data): array
  {
    $dataCollection = collect($data);
    if ($this->searchConfig['orderByDir'] == 'asc')
      $sortedData = $dataCollection->sortBy( $this->searchConfig['orderByColumn'] );
    else
      $sortedData = $dataCollection->sortByDesc( $this->searchConfig['orderByColumn'] );

    $data = [];
    foreach ($sortedData as $row) {
      $data[] = $row;
    }
    return $data;
  }

  protected function getEmptyDataTableResponse(string $message = "", int $status = 200): JsonResponse
  {
    return new JsonResponse([
      'draw' => 1,
      'recordsTotal' => 0,
      'recordsFiltered' => 0,
      'data' => [],
      'message' => $message
    ], $status);
  }

  protected function getSearchResult(array &$data,$orderDefault = true): array
  {
    if($orderDefault)
      $data = $this->getSortedData($data);
    $result                    = [];
    $result['draw']            = $this->searchConfig['draw'];
    $result['recordsTotal']    = $this->searchConfig['recordsTotal'];
    $result['recordsFiltered'] = $this->searchConfig['recordsFiltered'];
    $result['data']            = $data;
    return $result;
  }

  abstract protected function getSearchDataResult(array &$data);
}