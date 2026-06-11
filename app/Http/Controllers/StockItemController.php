<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockItem;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Manufacturer;

class StockItemController extends Controller
{
  public function index()
  {
    $unit = new Unit();
    $allUnits = $unit->getAllUnits();
    $group = new Category();
    $allGroup = $group->getAll();
    $manufacturer = new Manufacturer();
    $allManufacturer = $manufacturer->getAll();
    $stockItem = StockItem::orderBy('name', 'ASC')->orderBy('alias', 'ASC')->paginate(10);
    // dd($stockItem);
    return view('application.stockItem.index', compact('stockItem', 'allUnits','allGroup','allManufacturer'));
  }

  public function getStockItem(Request $request, $id) {
      $items = StockItem::where('id',$request->id)->first()->toArray();
      return json_encode($items);
  }

  public function create()
  {
    $unit = new Unit();
    $allUnits = $unit->getAllUnits();
    $group = new Category();
    $allGroup = $group->getAll();
    $manufacturer = new Manufacturer();
    $allManufacturer = $manufacturer->getAll();
    return view('application.stockItem.create', compact('allUnits','allGroup','allManufacturer'));
  }

  public function createAction(Request $request)
  {
    $request->validate(
      [
        'name' => 'required|string|max:255',
        'unit' => 'required',
        'barcode' => 'required|unique:stock_items',
      ],
      [
        'name.required' => 'Name is required',
        'unit.required' => 'Unit is required',
        'barcode.required' => 'Barcode is required',
      ]
    );
    StockItem::create($request->all());
    return redirect()
      ->back()
      ->with('success', 'Your message has been sent!');
  }

  public function update($id = null)
  {
    $unit = new Unit();
    $allUnits = $unit->getAllUnits();
    $stockItem = StockItem::where('id', $id)->first();
    return view('application.stockItem.update', compact('allUnits', 'stockItem'));
  }

  public function editAction(Request $request, $id = null)
  {
    $request->validate(
      [
        'name' => 'required|string|max:255',
        'baseUnits' => 'required',
      ],
      [
        'name.required' => 'Name is required',
        'baseUnits.required' => 'Base unit is required',
      ]
    );
    $stockItem = StockItem::findOrFail($id);
    // dd($stockItem);
    $input = $request->except(['_token', '_method', '_url']);
    StockItem::where('id', $id)->update($input);
    return redirect()
      ->back()
      ->with('success', 'Your message has been sent!');
  }

  public function importAction($id = null)
  {
    $stockItem = StockItem::findOrFail($id);

    $unit = new Unit();
    $allUnits = $unit->getAllUnits();

    $companyName = 'Smart Polymer Industries Ltd';

    $xml = <<<XML
  <ENVELOPE>
    <HEADER>
        <TALLYREQUEST>Import Data</TALLYREQUEST>
    </HEADER>
    <BODY>
        <IMPORTDATA>
            <REQUESTDESC>
                <REPORTNAME>All Masters</REPORTNAME>
            </REQUESTDESC>
            <REQUESTDATA>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <STOCKITEM NAME="{$stockItem->name}" ACTION="Create">
                        <NAME.LIST>
                            <NAME>{$stockItem->name}</NAME>
                        </NAME.LIST>
                        <PARENT/>
                        <CATEGORY/>
                        <TAXCLASSIFICATIONNAME/>
                        <COSTINGMETHOD>{$stockItem->costingMethod}</COSTINGMETHOD>
                        <VALUATIONMETHOD>{$stockItem->valuationMethod}</VALUATIONMETHOD>
                        <BASEUNITS>{$allUnits[$stockItem->baseUnits]}</BASEUNITS>
                        <ADDITIONALUNITS/>
                        <CONVERSION/>
                        <OPENINGBALANCE>{$stockItem->openingBalance} {$allUnits[$stockItem->baseUnits]}</OPENINGBALANCE>
                        <OPENINGVALUE>{$stockItem->openingValue}</OPENINGVALUE>
                        <OPENINGRATE>{$stockItem->openingRate}</OPENINGRATE>
                    </STOCKITEM>
                </TALLYMESSAGE>
            </REQUESTDATA>
        </IMPORTDATA>
    </BODY>
</ENVELOPE>
XML;

    // URL of the Tally Prime server
    $tallyURL = 'http://localhost:9000';

    // The XML data
    $data = $xml;

    // Initialize cURL
    $ch = curl_init($tallyURL);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        echo 'Response: ' . $response;
    }

    // Close cURL
    curl_close($ch);

    return json_encode($response);
    }
}
