@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
<div class="row">
  <!-- Welcome & Quick Actions -->
  <div class="col-lg-8 mb-4 order-0">
    <div class="card h-100">
      <div class="d-flex align-items-end row h-100">
        <div class="col-sm-8">
          <div class="card-body">
            <h5 class="card-title text-primary mb-1">Welcome, {{ Auth::user()->name }}!</h5>
            <p class="mb-3 text-muted">
              Role: <strong class="text-body">{{ str_replace(['["', '"]'], '', Auth::user()->getRoleNames()) }}</strong>
              &middot; {{ date('d M, Y') }}
            </p>
            <div class="d-flex flex-wrap gap-2">
              @can('sales')
                <a href="{{ route('sales-new-desktop') }}" class="btn btn-sm btn-primary">
                  <i class="bx bx-plus-circle me-1"></i> New Invoice
                </a>
                <a href="{{ route('challan-create') }}" class="btn btn-sm btn-outline-primary">
                  <i class="bx bx-package me-1"></i> New Challan
                </a>
              @endcan
              @can('inventory')
                <a href="{{ route('inventory-mrr-entry') }}" class="btn btn-sm btn-outline-info">
                  <i class="bx bx-import me-1"></i> MRR Entry
                </a>
                <a href="{{ route('inventory-production-entry') }}" class="btn btn-sm btn-outline-success">
                  <i class="bx bx-cog me-1"></i> Production
                </a>
              @endcan
              @can('report')
                <a href="{{ route('dayBook') }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bx bx-book-open me-1"></i> Day Book
                </a>
              @endcan
            </div>
          </div>
        </div>
        <div class="col-sm-4 text-center text-sm-left d-none d-sm-block">
          <div class="card-body pb-0 px-0 px-md-3">
            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="130" alt="Dashboard User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Today Sales & Today Voucher -->
  <div class="col-lg-4 col-md-12 mb-4 order-1">
    <div class="row h-100">
      <div class="col-6 mb-4 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-2">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-trending-up"></i></span>
              </div>
              <span class="badge bg-label-success">Today</span>
            </div>
            <span class="fw-semibold d-block mb-1">Today's Sales</span>
            <h4 class="card-title mb-1">{{ number_format((float) $sales->sales_value, 0) }} ৳</h4>
            <small class="text-muted">{{ (int) $sales->sales_qty }} {{ (int) $sales->sales_qty === 1 ? 'invoice' : 'invoices' }}</small>
          </div>
        </div>
      </div>
      <div class="col-6 mb-4 mb-lg-0">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-2">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-receipt"></i></span>
              </div>
              <span class="badge bg-label-info">Today</span>
            </div>
            <span class="fw-semibold d-block mb-1">Today's Vouchers</span>
            <h4 class="card-title mb-1">{{ number_format((float) $receipt->receipt_value, 0) }} ৳</h4>
            <small class="text-muted">{{ (int) $receipt->receipt_qty }} {{ (int) $receipt->receipt_qty === 1 ? 'voucher' : 'vouchers' }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Monthly & Inventory KPI Strip -->
<div class="row">
  <div class="col-sm-6 col-xl-3 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted small d-block mb-1">Sales ({{ date('M Y') }})</span>
            <h4 class="mb-1">{{ number_format((float) $monthSales->value, 2) }} ৳</h4>
            <small class="text-success fw-medium">{{ (int) $monthSales->qty }} invoices &middot; Paid {{ number_format((float) $monthSales->paid, 0) }} ৳</small>
          </div>
          <span class="avatar-initial rounded bg-label-primary p-2"><i class="bx bx-cart fs-4"></i></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted small d-block mb-1">Purchase ({{ date('M Y') }})</span>
            <h4 class="mb-1">{{ number_format((float) $monthPurchase->value, 2) }} ৳</h4>
            <small class="text-muted">{{ (int) $monthPurchase->qty }} bills &middot; Today: {{ number_format((float) $todayPurchase->value, 0) }} ৳</small>
          </div>
          <span class="avatar-initial rounded bg-label-warning p-2"><i class="bx bx-shopping-bag fs-4"></i></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted small d-block mb-1">Cash/Bank Flow ({{ date('M') }})</span>
            <h4 class="mb-1 text-success">+{{ number_format($monthCashReceive, 0) }} ৳</h4>
            <small class="text-danger fw-medium">Paid: -{{ number_format($monthCashPayment, 0) }} ৳</small>
          </div>
          <span class="avatar-initial rounded bg-label-info p-2"><i class="bx bx-wallet fs-4"></i></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="text-muted small d-block mb-1">Delivery Challans</span>
            <h4 class="mb-1">{{ $challanStats['total'] }}</h4>
            <small class="text-muted">
              <span class="text-warning">{{ $challanStats['Pending'] }} Pending</span> &middot;
              <span class="text-info">{{ $challanStats['Dispatched'] }} Dispatched</span> &middot;
              <span class="text-success">{{ $challanStats['Delivered'] }} Delivered</span>
            </small>
          </div>
          <span class="avatar-initial rounded bg-label-secondary p-2"><i class="bx bx-package fs-4"></i></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row">
  <!-- Monthly Sales vs Purchase Chart -->
  <div class="col-12 col-lg-8 mb-4">
    <div class="card h-100">
      <div class="row row-bordered g-0 h-100">
        <div class="col-md-8">
          <div class="card-header d-flex align-items-center justify-content-between pb-2">
            <div>
              <h5 class="card-title m-0">Monthly Sales vs Purchase</h5>
              <small class="text-muted">Year {{ $selectedYear }} overview (৳)</small>
            </div>
            <form method="GET" action="{{ route('dashboard-analytics') }}">
              <select name="year" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Select Year">
                @foreach($availableYears as $yr)
                  <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
              </select>
            </form>
          </div>
          <div class="card-body pt-0">
            <div id="salesPurchaseChart"></div>
          </div>
        </div>
        <div class="col-md-4 d-flex flex-column justify-content-between">
          <div class="card-body text-center pb-0">
            <h6 class="text-muted mb-2">Invoice Collection Rate ({{ $selectedYear }})</h6>
            <div id="collectionRateChart"></div>
            <div class="fw-medium text-muted small mt-1">
              Collected {{ number_format($yearSalesPaid, 0) }} ৳ of {{ number_format($yearSalesTotal, 0) }} ৳
            </div>
          </div>
          <div class="card-body pt-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center">
                <span class="badge bg-label-primary p-2 me-2"><i class="bx bx-trending-up text-primary"></i></span>
                <div>
                  <small class="text-muted d-block">{{ $selectedYear }} Sales</small>
                  <h6 class="mb-0">{{ number_format($yearSalesTotal, 0) }} ৳</h6>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center">
                <span class="badge bg-label-info p-2 me-2"><i class="bx bx-shopping-bag text-info"></i></span>
                <div>
                  <small class="text-muted d-block">{{ $selectedYear }} Purchase</small>
                  <h6 class="mb-0">{{ number_format($yearPurchaseTotal, 0) }} ৳</h6>
                </div>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <span class="badge bg-label-success p-2 me-2"><i class="bx bx-wallet text-success"></i></span>
                <div>
                  <small class="text-muted d-block">Receipts / Payments</small>
                  <h6 class="mb-0">{{ number_format($yearReceiptsTotal, 0) }} / {{ number_format($yearPaymentsTotal, 0) }} ৳</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cashflow Chart + Inventory Health -->
  <div class="col-12 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-2">
        <div>
          <h5 class="card-title m-0">Receipts vs Payments</h5>
          <small class="text-muted">Monthly Cash &amp; Bank Vouchers ({{ $selectedYear }})</small>
        </div>
      </div>
      <div class="card-body pt-0">
        <div id="cashflowChart"></div>
        <hr class="my-3">
        <div class="row text-center g-2">
          <div class="col-4">
            <small class="text-muted d-block">Finished Goods</small>
            <h6 class="mb-0">{{ $inventoryStats['stock_items_count'] }} items</h6>
            <small class="text-primary">{{ number_format($inventoryStats['stock_total_qty'], 0) }} qty</small>
          </div>
          <div class="col-4">
            <small class="text-muted d-block">Raw Materials</small>
            <h6 class="mb-0">{{ $inventoryStats['material_items_count'] }} items</h6>
            <small class="text-info">{{ number_format($inventoryStats['material_total_qty'], 0) }} qty</small>
          </div>
          <div class="col-4">
            <small class="text-muted d-block">Production</small>
            <h6 class="mb-0">{{ $inventoryStats['production_count'] }} runs</h6>
            <small class="text-success">{{ $inventoryStats['mrr_count'] }} MRR</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Category Breakdown, Top Customers, Top Items & Low Stock -->
<div class="row">
  <!-- Category Breakdown -->
  <div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-2">
        <div>
          <h5 class="card-title m-0">Stock by Category</h5>
          <small class="text-muted">{{ $inventoryStats['stock_items_count'] }} Total Stock Items</small>
        </div>
        <a href="{{ route('stockitem') }}" class="btn btn-xs btn-outline-primary">View All</a>
      </div>
      <div class="card-body">
        @if($categoryBreakdown->count() > 0)
          <div id="categoryDonutChart" class="mb-3"></div>
          <ul class="p-0 m-0">
            @foreach($categoryBreakdown->take(5) as $cat)
              <li class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <span class="fw-medium">{{ $cat->category_name }}</span>
                  <small class="text-muted d-block">{{ $cat->item_count }} items</small>
                </div>
                <span class="badge bg-label-primary">{{ number_format((float) $cat->total_qty, 2) }} qty</span>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted text-center my-4">No stock items available.</p>
        @endif
      </div>
    </div>
  </div>

  <!-- Top Customers & Top Selling Items -->
  <div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header pb-2">
        <ul class="nav nav-pills nav-fill" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active btn-sm" role="tab" data-bs-toggle="tab" data-bs-target="#tab-top-customers" aria-selected="true">
              Top Customers
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link btn-sm" role="tab" data-bs-toggle="tab" data-bs-target="#tab-top-items" aria-selected="false">
              Top Selling Items
            </button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="tab-top-customers" role="tabpanel">
            @forelse($topCustomers as $cust)
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="me-2">
                  <h6 class="mb-0">{{ $cust->name }}</h6>
                  <small class="text-muted">{{ $cust->invoice_count }} {{ $cust->invoice_count == 1 ? 'invoice' : 'invoices' }} ({{ $selectedYear }})</small>
                </div>
                <div class="text-end">
                  <span class="fw-bold text-primary">{{ number_format((float) $cust->total_sales, 2) }} ৳</span>
                </div>
              </div>
            @empty
              <p class="text-muted text-center my-4">No sales recorded for {{ $selectedYear }}.</p>
            @endforelse
          </div>

          <div class="tab-pane fade" id="tab-top-items" role="tabpanel">
            @forelse($topSellingItems as $tItem)
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="me-2">
                  <h6 class="mb-0">{{ $tItem->name }}</h6>
                  <small class="text-muted">Sold: {{ number_format((float) $tItem->total_qty, 2) }} {{ $tItem->unit_name }}</small>
                </div>
                <div class="text-end">
                  <span class="fw-bold text-success">{{ number_format((float) $tItem->total_amount, 2) }} ৳</span>
                </div>
              </div>
            @empty
              <p class="text-muted text-center my-4">No item sales recorded for {{ $selectedYear }}.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Low Stock Alerts -->
  <div class="col-md-12 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-2">
        <div>
          <h5 class="card-title m-0">Stock Level Watch</h5>
          <small class="text-muted">Lowest quantity items</small>
        </div>
        <span class="badge bg-label-danger">{{ $inventoryStats['low_stock_count'] + $inventoryStats['low_material_count'] }} Low (&le;5)</span>
      </div>
      <div class="card-body">
        <small class="text-uppercase text-muted fw-semibold d-block mb-2">Finished Goods</small>
        @forelse($lowStockItems as $ls)
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-truncate me-2">{{ $ls->name }}</span>
            <span class="badge {{ (float) $ls->quantity <= 5 ? 'bg-label-danger' : 'bg-label-success' }}">
              {{ number_format((float) $ls->quantity, 2) }} {{ $ls->unitDetail->name ?? '' }}
            </span>
          </div>
        @empty
          <p class="text-muted small mb-2">No stock items found.</p>
        @endforelse

        <hr class="my-3">

        <small class="text-uppercase text-muted fw-semibold d-block mb-2">Raw Materials</small>
        @forelse($lowMaterialItems as $lm)
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-truncate me-2">{{ $lm->name }}</span>
            <span class="badge {{ (float) $lm->quantity <= 5 ? 'bg-label-danger' : 'bg-label-info' }}">
              {{ number_format((float) $lm->quantity, 2) }} {{ $lm->unitDetail->name ?? '' }}
            </span>
          </div>
        @empty
          <p class="text-muted small mb-0">No raw materials found.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Recent Sales, Challans & Vouchers -->
<div class="row">
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Sales Invoices</h5>
        <a href="{{ route('sales-list') }}" class="btn btn-xs btn-outline-primary">View All</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Voucher</th>
              <th>Date</th>
              <th>Customer</th>
              <th class="text-end">Amount (৳)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentSales as $rs)
              <tr>
                <td>
                  <a href="{{ url('/sales/details-pp/' . $rs->id) }}" target="_blank" class="fw-semibold">{{ $rs->voucher_no }}</a>
                </td>
                <td>{{ date('d-m-Y', strtotime($rs->date)) }}</td>
                <td>{{ $rs->Ledger->name ?? '-' }}</td>
                <td class="text-end fw-semibold">{{ number_format((float) $rs->gross_amount, 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">No sales invoices found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Challans &amp; Vouchers</h5>
        <a href="{{ route('challan-list') }}" class="btn btn-xs btn-outline-primary">Challans</a>
      </div>
      <div class="card-body pt-2">
        <small class="text-uppercase text-muted fw-semibold d-block mb-2">Delivery Challans</small>
        @forelse($recentChallans as $rc)
          <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
            <div>
              <a href="{{ route('challan-details', $rc->id) }}" class="fw-semibold">{{ $rc->challan_no }}</a>
              <small class="text-muted ms-1">{{ $rc->ledger->name ?? '-' }}</small>
            </div>
            <div>
              <small class="text-muted me-2">{{ date('d-m-Y', strtotime($rc->challan_date)) }}</small>
              <span class="badge bg-label-info">{{ $rc->status }}</span>
            </div>
          </div>
        @empty
          <p class="text-muted small mb-2">No challans created yet.</p>
        @endforelse

        <small class="text-uppercase text-muted fw-semibold d-block mt-3 mb-2">Latest Vouchers</small>
        @forelse($recentVouchers as $rv)
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <a href="{{ url('/voucher/details/' . $rv->id) }}" target="_blank" class="fw-medium">{{ $rv->voucher_no }}</a>
              <small class="text-muted ms-1">{{ $rv->VoucherType->voucher_name ?? '' }}</small>
            </div>
            <div class="text-end">
              <span class="fw-semibold">{{ number_format((float) $rv->total_amount, 2) }} ৳</span>
            </div>
          </div>
        @empty
          <p class="text-muted small mb-0">No recent vouchers.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
(function () {
  const data = @json($chartData);
  const cardColor = config.colors.cardColor;
  const axisColor = config.colors.axisColor;
  const headingColor = config.colors.headingColor;
  const borderColor = config.colors.borderColor;

  const salesPurchaseEl = document.querySelector('#salesPurchaseChart');
  if (salesPurchaseEl) {
    new ApexCharts(salesPurchaseEl, {
      series: [
        { name: 'Sales (৳)', data: data.sales },
        { name: 'Purchase (৳)', data: data.purchase }
      ],
      chart: { height: 310, type: 'bar', toolbar: { show: false } },
      plotOptions: {
        bar: { horizontal: false, columnWidth: '45%', borderRadius: 6 }
      },
      colors: [config.colors.primary, config.colors.info],
      dataLabels: { enabled: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'left',
        labels: { colors: axisColor }
      },
      grid: { borderColor: borderColor },
      xaxis: {
        categories: data.months,
        labels: { style: { colors: axisColor, fontSize: '12px' } }
      },
      yaxis: {
        labels: {
          style: { colors: axisColor, fontSize: '12px' },
          formatter: function (val) {
            return val >= 1000 ? (val / 1000).toFixed(1) + 'k' : val;
          }
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return Number(val).toLocaleString() + ' ৳';
          }
        }
      }
    }).render();
  }

  const collectionEl = document.querySelector('#collectionRateChart');
  if (collectionEl) {
    new ApexCharts(collectionEl, {
      series: [data.collectionRate],
      labels: ['Collected'],
      chart: { height: 195, type: 'radialBar' },
      plotOptions: {
        radialBar: {
          startAngle: -135,
          endAngle: 135,
          hollow: { size: '60%', background: 'transparent' },
          track: { background: borderColor, strokeWidth: '100%' },
          dataLabels: {
            name: { offsetY: 20, color: axisColor, fontSize: '13px' },
            value: { offsetY: -12, color: headingColor, fontSize: '20px', fontWeight: 600 }
          }
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: { shade: 'dark', shadeIntensity: 0.4, opacityFrom: 1, opacityTo: 0.7 }
      },
      stroke: { dashArray: 4 }
    }).render();
  }

  const cashflowEl = document.querySelector('#cashflowChart');
  if (cashflowEl) {
    new ApexCharts(cashflowEl, {
      series: [
        { name: 'Receipts (৳)', data: data.receipts },
        { name: 'Payments (৳)', data: data.payments }
      ],
      chart: { height: 225, type: 'area', toolbar: { show: false } },
      colors: [config.colors.success, config.colors.danger],
      dataLabels: { enabled: false },
      stroke: { width: 2, curve: 'smooth' },
      fill: {
        type: 'gradient',
        gradient: { opacityFrom: 0.35, opacityTo: 0.05 }
      },
      grid: { borderColor: borderColor },
      xaxis: {
        categories: data.months,
        labels: { style: { colors: axisColor, fontSize: '11px' } }
      },
      yaxis: {
        labels: {
          style: { colors: axisColor, fontSize: '11px' },
          formatter: function (val) {
            return val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val;
          }
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return Number(val).toLocaleString() + ' ৳';
          }
        }
      }
    }).render();
  }

  const categoryDonutEl = document.querySelector('#categoryDonutChart');
  if (categoryDonutEl && data.categoryLabels.length > 0) {
    new ApexCharts(categoryDonutEl, {
      series: data.categoryCounts,
      labels: data.categoryLabels,
      chart: { height: 190, type: 'donut' },
      colors: [config.colors.primary, config.colors.success, config.colors.info, config.colors.warning, config.colors.secondary],
      legend: { show: false },
      dataLabels: { enabled: false },
      plotOptions: {
        pie: {
          donut: {
            size: '68%',
            labels: {
              show: true,
              value: { fontSize: '16px', fontWeight: 600, color: headingColor },
              name: { fontSize: '12px', color: axisColor },
              total: {
                show: true,
                label: 'Items',
                color: axisColor,
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                }
              }
            }
          }
        }
      }
    }).render();
  }
})();
</script>
@endsection
