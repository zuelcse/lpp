@extends('layouts/commonMaster' )

@section('layoutContent')
<style>
        table {
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            padding: 10px;
            border: 1px solid bloack;
            text-align: center;
            font-weight: bold;
        }

        td {
            padding: 7px;
            border: 1px solid bloack;
        }
        div {
        	margin: 0px auto;
        	display: block; 
        	width: 100%;
        	float:left;
        		
        }
        
    </style>
<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
