
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|Stock')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="5" class="text-center">
                    Stock Position<br>
                    <span style="font-size:11px;">As on {{date('d F Y')}}</span>
                </td>
            </tr>
            <tr>
                <th>Item Name</th>
                <th>M.Unit</th>
                <th>Pur.Qty</th>
                <th>Sales.Qty</th>
                <th>Balance.Qty</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data['data'] as $key => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>{{ $item['total_purchase_qty'] }}</td>
                    <td>{{ $item['total_sales_qty'] }}</td>
                    <td style="text-align:right">
                        {{number_format(($item['total_purchase_qty'] - $item['total_sales_qty']),2)}}
                    </td>
                </tr>
            @endforeach          
        </tbody>
    </table>
@endsection