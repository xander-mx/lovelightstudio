<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payrolls;
use App\Traits\PayrollDataTable;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
  use PayrollDataTable;

  public static array $headers = array('Fecha Creación', 'Concepto', 'Fecha Cierre', 'Total', 'Acciones');
  public static array $fields = array('created_at', 'concept', 'closeDate', 'total', 'actions');
  public static string $routeTable = 'web.payrolls.table';

  public function loadTable(Request $request): array
  {
    $this->setUpSearchConfig($request);
    $payrolls = Payrolls::all()->sortByDesc('closedDate');
    return $this->getSearchDataResult($payrolls);
  }

  public function index()
  {
    return view('system.payroll.index');
  }
}