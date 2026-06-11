
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|CashFlow')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="6" class="text-center">
                    Ledger Head: {{$data['ledger']->name}}<br>
                    <span style="font-size:11px;">{{$data['info']['s_date'] .' to '. $data['info']['e_date']}}</span>
                </td>
            </tr>
            <tr>
                <th>Date</th>
                <th>Vch No</th>
                <th>Particulars</th>
                <th>Receive (Tk)</th>
                <th>Payment (Tk)</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        	<tr>
                <td colspan="3"  style="text-align:right">Opening Balance :</td>
                <td style="text-align:right">
                	{{$data['data']['openingBalance']>0?number_format(abs($data['data']['openingBalance']),2):''}}
                </td>
                <td style="text-align:right">
                	{{$data['data']['openingBalance']<0?number_format(abs($data['data']['openingBalance']),2):''}}
                </td>
                <td></td>
            </tr>
            @php
                $total_debit = 0;
                $total_credit = 0;
            @endphp
            @foreach($data['data']['masterData'] as $key => $item)
                <?php
                $total_debit += $item['debit'];
                $total_credit += $item['credit'];
                ?>
                <tr>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ $item['voucher_no'] }}</td>
                    <td>{{ $item['particulars'] }}</td>
                    <td style="text-align:right">
                        {{$item['debit']>0?number_format($item['debit'],2):''}}
                    </td>
                    <td style="text-align:right">
                        {{$item['credit']>0?number_format($item['credit'],2):''}}
                    </td>
                    <td>{{ $item['note'] }}</td>
                </tr>
            @endforeach
           
            @php
                $closing = $data['data']['openingBalance']+$total_debit-$total_credit;
            @endphp
                    
            <tr>
                <td colspan="3"  style="text-align:right">Total of: Cash In Hand</td>
                <td style="text-align:right">{{number_format($total_debit,2)}}</td>
                <td style="text-align:right">{{number_format($total_credit,2)}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"  style="text-align:right">Closing Balance :</td>
                <td style="text-align:right">
                	{{$closing>0?number_format(abs($closing),2):''}}
                </td>
                <td style="text-align:right">
                	{{$closing<0?number_format(abs($closing),2):''}}
                </td>
                <td style="text-align:right"></td>
            </tr>           
        </tbody>
    </table>
@endsection