
@extends('layouts/contentNavbarLayout')

@section('title', 'Report|Sales')

@section('content')
    <table class="table table-bordered" class="font-size: 0.7em">
        <thead>
            <tr>
                <td colspan="7" class="text-center">
                    Main Group Summary Report<br>
                    Main Group: {{$data['data'][0]['alias'].'-'.$data['data'][0]['name']}}<br>
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
                $o_total = 0;
                $b_total = 0;
            @endphp
            @foreach($data['data'][0]['subgroups'] as $ky => $itm)
                <?php
                    $so_total = 0;
                    $sb_total = 0;
                ?>
                <tr>
                    <td colspan="7">{{ $itm['alias'].'-'.$itm['name'] }}</td>
                </tr>
                @foreach($itm['ledger'] as $key => $item)
                    <?php
                        $o_total += $item['opening_balance'];
                        $b_total += $item['closing_balance'];
                        $so_total += $item['opening_balance'];
                        $sb_total += $item['closing_balance'];
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
                    <td colspan="2" style="text-align:center;">Sub Total ({{ $itm['alias'].'-'.$itm['name'] }})</td>
                    <td style="text-align:right">{{number_format($so_total,2)}}</td>
                    
                    <td></td>
                    <td></td>
                    <td style="text-align:right">{{number_format($sb_total,2)}}</td>
                    <td style="text-align:right">{{number_format($sb_total,2)}}</td>
                </tr> 
            @endforeach  
            <tr>
                <td colspan="2" style="text-align:center;">Total</td>
                <td style="text-align:right">{{number_format($o_total,2)}}</td>
                
                <td></td>
                <td></td>
                <td style="text-align:right">{{number_format($b_total,2)}}</td>
                <td style="text-align:right">{{number_format($b_total,2)}}</td>
            </tr>        
        </tbody>
    </table>
@endsection