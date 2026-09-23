
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|Sales')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="7" class="text-center">
                    Sub Group Summary Report<br>
                    Sub Group: {{$data['sub_group']?->alias.'-'.$data['sub_group']?->name}}<br>
                    <span style="font-size:11px;">Period: {{$data['info']['s_date'].' to '.$data['info']['e_date']}}</span>
                </td>
            </tr>
            <tr>
                <th>Particulars</th>
                <th>Mobile No</th>
                <th>Opening Balance</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit Balance</th>
                <th>Credit Balance</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @php
                $total_sales_quantity = 0;
                $total_net_amount = 0;
            @endphp
            @foreach($data['data'] as $key => $item)
                <?php
                    $total_sales_quantity += $item['opening_balance'];
                    $total_net_amount += $item['closing_balance'];
                ?>
                <tr>
                    <td>{{ $item['alias'].'-'.$item['name'] }}</td>
                    <td>{{ $item['mobile'] }}</td>
                    <td>{{ $item['opening_balance'] }}</td>
                    <td>{{ $item['debit'] }}</td>
                    <td>{{ $item['credit'] }}</td>
                    <td style="text-align:right">
                        {{number_format($item['closing_balance'],2)}}
                    </td>
                    <td style="text-align:right">
                        {{number_format($item['closing_balance'],2)}}
                    </td>
                </tr>
            @endforeach  
            <tr>
                <td colspan="2" style="text-align:center;">Total</td>
                <td style="text-align:right">{{number_format($total_sales_quantity,2)}}</td>
                
                <td></td>
                <td></td>
                <td style="text-align:right">{{number_format($total_net_amount,2)}}</td>
                <td style="text-align:right">{{number_format($total_net_amount,2)}}</td>
            </tr>        
        </tbody>
    </table>
@endsection