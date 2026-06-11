
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|Sales')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="4" class="text-center">
                    Sales Statement<br>
                    <span style="font-size:11px;">As on {{date('d F Y')}}</span>
                </td>
            </tr>
            <tr>
                <th>Item Name</th>
                <th>M.Unit</th>
                <th>Sales.Qty</th>
                <th>Sales.Value</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($data['data'] as $key => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>{{ $item['sales_qty'] }}</td>
                    <td style="text-align:right">
                        {{number_format($item['sales_value'],2)}}
                    </td>
                </tr>
            @endforeach          
        </tbody>
    </table>
@endsection