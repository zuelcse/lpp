@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher')

@section('content')
<style>
        @page {
            size: 8.25in 5.65in;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #eee;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .receipt {
            width: 8.25in;
            height: 5.65in;
            margin: auto;
            background: #fff;
            border: 1px solid #999;
            position: relative;
            overflow: hidden;
        }

        /* =========================
           Header
        ========================== */

        .receipt-header {
            height: 1.15in;
            position: relative;
                margin: 0 0.25in;
        }

        .logo-area {
            position: absolute;
            left: 0.30in;
            top: 0.20in;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            border: 8px solid #222;
            position: relative;
        }

        .logo-icon::after {
            content: "";
            position: absolute;
            width: 25px;
            height: 25px;
            border: 3px solid #555;
            border-radius: 50%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: #eee;
        }

        .logo-name {
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
            line-height: 1;
        }

        .logo-subtitle {
            font-size: 15px;
            color: #666;
            letter-spacing: 4px;
        }

        .company-info {
            position: absolute;
            right: 0;
            top: 0;
            width: 3.25in;
            height: 1.15in;
            background: #ddd;
            border-bottom-left-radius: 30px;
            padding: 14px 18px;
        }

        .company-info h1 {
            margin: 0 0 8px;
            font-size: 21px;
            font-style: italic;
            font-weight: 700;
            text-align: center;
        }

        .company-info p {
            margin: 3px 0;
            font-size: 11px;
            line-height: 1.3;
        }

        /* =========================
           Main Form
        ========================== */

        .receipt-form {
            margin: 0 0.25in;
        }

        .form-row {
            margin-bottom: 6px;
        }

        .field {
            height: 30px;
            border: 1px solid #aaa;
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 14px;
        }

        .field-label {
            font-size: 14px;
            margin-right: 5px;
        }

        .no-date-row {
            height: 34px;
            margin-bottom: 5px;
        }

        .date-field {
            width: 2.05in;
            height: 32px;
        }

        /* =========================
           Payment
        ========================== */

        .payment-row {
            min-height: 34px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 2px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            font-size: 13px;
            white-space: nowrap;
        }

        .payment-option input {
            width: 14px;
            height: 14px;
            margin-right: 6px;
        }

        .mobile-field {
            height: 30px;
            flex: 1;
            border: 1px solid #aaa;
            display: flex;
            align-items: center;
            padding: 0 8px;
            font-size: 13px;
        }

        /* =========================
           Footer
        ========================== */

        .receipt-footer {
            position: absolute;
            left: 0.50in;
            right: 0.50in;
            bottom: 0.25in;
        }

        .signature {
            width: 1.45in;
            text-align: center;
            border-top: 1px solid #555;
            padding-top: 3px;
            font-size: 12px;
        }

        .authorized {
            width: 1.85in;
            text-align: center;
            border-top: 1px solid #555;
            padding-top: 3px;
            font-size: 12px;
        }

        .authorized strong {
            font-size: 13px;
        }

        /* =========================
           Print
        ========================== */

        @media print {

            body {
                padding: 0;
                margin: 0;
                background: #fff;
            }

            .receipt {
                margin: 0;
                border: 1px solid #999;
            }

        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Voucher Information') }}</span>
                </div>

                <div class="card-body">
                    <div class="card">
                        <div class="receipt">

                            <!-- ======================================
                                 HEADER
                            ======================================= -->

                            <div class="receipt-header">

                                <!-- Logo -->
                                <div class="logo-area d-flex align-items-center">

                                    <div class="logo-icon me-3"></div>

                                    <div>
                                        <div class="logo-name">
                                            LOCKNATH
                                        </div>

                                        <div class="logo-subtitle">
                                            Printing Press
                                        </div>
                                    </div>

                                </div>


                                <!-- Company Information -->
                                <div class="company-info">

                                    <h1>
                                        Receipt of payment
                                    </h1>

                                    <p style="text-fit: shrink; font-size:14px; white-space: nowrap;">
                                        <strong>📍</strong>
                                        322, Cement Pottry (Old Post Office Road),
                                        Narsingdi-1600.
                                    </p>

                                    <p style="text-fit: shrink; font-size:14px; white-space: nowrap;">
                                        <strong>☎</strong>
                                        01712-423272, 01689-914620,
                                        02-224453656
                                    </p>

                                </div>

                            </div>


                            <!-- ======================================
                                 FORM
                            ======================================= -->

                            <div class="receipt-form">

                                <!-- No + Date -->
                                <div class="row no-date-row align-items-center">

                                    <div class="col-8">
                                        <span class="ms-2">
                                            No. {{ $data->voucher_no }}
                                        </span>
                                    </div>

                                    <div class="col-4 d-flex justify-content-end">

                                        <div class="field date-field">

                                            <span class="field-label">
                                                Date : {{ $data->date }}
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <!-- Name -->
                                <div class="row form-row">

                                    <div class="col-12">

                                        <div class="field">

                                            <span class="field-label">

                                                Name: {{ $data->CreditLedger->name }}
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <!-- Address + Cell -->
                                <div class="row form-row">

                                    <div class="col-8 pe-1">

                                        <div class="field">

                                            <span class="field-label">
                                                Address: {{ $data->CreditLedger->address }}
                                            </span>

                                        </div>

                                    </div>

                                    <div class="col-4 ps-1">

                                        <div class="field">

                                            <span class="field-label">
                                                Cell: {{ $data->CreditLedger->mobile }}
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <!-- TK + In Words -->
                                <div class="row form-row">

                                    <div class="col-4 pe-1">

                                        <div class="field">

                                            <span class="field-label">
                                                TK.: {{ $data->amount }}
                                            </span>

                                        </div>

                                    </div>

                                    <div class="col-8 ps-1">

                                        <div class="field">

                                            <span class="field-label">
                                                In Words: {{ numberToWords($data->amount) }} Only
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <!-- ==================================
                                     PAYMENT METHODS
                                =================================== -->

                                <div class="payment-row">

                                    <label class="payment-option">
                                        <input type="checkbox" {{$data->voucher_type==10?'checked':''}}>
                                        Cash
                                    </label>

                                    <label class="payment-option">
                                        <input type="checkbox" {{$data->voucher_type==12?'checked':''}}>
                                        Cheque
                                    </label>

                                    <label class="payment-option">
                                        <input type="checkbox">
                                        B-kash
                                    </label>

                                    <label class="payment-option">
                                        <input type="checkbox">
                                        Nagad
                                    </label>

                                    <div class="mobile-field">
                                        B-kash/Nagad No :
                                    </div>

                                </div>


                                <!-- ==================================
                                     BANK DETAILS
                                =================================== -->

                                <div class="row mt-1">

                                    <div class="col-5 pe-1">

                                        <div class="field">
                                            Bank Name: {{$data->voucher_type==12?$data->DebitLedger->name:''}}
                                        </div>

                                    </div>

                                    <div class="col-3 ps-1 pe-1">

                                        <div class="field">
                                            Cheque No: {{$data->Voucher->cheque_no}}
                                        </div>

                                    </div>

                                    <div class="col-4 ps-1">

                                        <div class="field">
                                            Cheque Date : {{$data->Voucher->cheque_date}}
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- ======================================
                                 FOOTER
                            ======================================= -->

                            <div class="receipt-footer">

                                <div class="d-flex justify-content-between align-items-end">

                                    <div class="signature">
                                        Signature of buyer
                                    </div>

                                    <div class="authorized">
                                        For- <strong>Locknath Printing Press</strong>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
