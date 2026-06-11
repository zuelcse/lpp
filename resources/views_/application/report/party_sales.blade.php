
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|Sales')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="6" class="text-center">
                    Party-Wise Sales Report<br>
                    Ledger Head: {{$data['ledger']?->name}}<br>
                    <span style="font-size:11px;">{{$data['info']['s_date'].' to '.$data['info']['e_date']}}</span>
                </td>
            </tr>
            <tr>
                <th>V.Date</th>
                <th>V.No.</th>
                <th>Particulars</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Debit (Tk)</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @php
                $total_sales_quantity = 0;
                $total_net_amount = 0;
            @endphp
            @foreach($data['data'] as $key => $item)
                <?php
                    $total_sales_quantity += $item['sales_quantity'];
                    $total_net_amount += $item['net_amount'];
                ?>
                <tr>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ $item['voucher_no'] }}</td>
                    <td>{{ $item['item_name'] }}</td>
                    <td>{{ $item['sales_quantity'] }}</td>
                    <td>{{ $item['net_amount']/$item['sales_quantity'] }}</td>
                    <td style="text-align:right">
                        {{number_format($item['net_amount'],2)}}
                    </td>
                </tr>
            @endforeach  
            <tr>
                <td colspan="3">Total</td>
                <td style="text-align:right">{{number_format($total_sales_quantity,2)}}</td>
                
                <td></td>
                <td style="text-align:right">{{number_format($total_net_amount,2)}}</td>
            </tr>        
        </tbody>
    </table>
@endsection