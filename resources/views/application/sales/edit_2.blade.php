
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit POS Invoice</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6f9;
        }
        .card{
            border:none;
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
        }
        .table th{
            background:#0d6efd;
            color:#fff;
            text-align:center;
        }
        .table td{
            vertical-align:middle;
        }
        .summary-box{
            background:#f8f9fa;
            padding:20px;
            border-radius:10px;
        }
    </style>

</head>
<body>

<div class="container-fluid mt-4">

    <div class="card">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between">

                <h4>Edit POS Invoice</h4>

                <h5>
                    Invoice :
                    <span class="badge bg-warning text-dark">
                        INV-240701
                    </span>
                </h5>

            </div>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-3">
                    <label>Invoice Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-5">
                    <label>Customer</label>
                    <select class="form-select">
                        <option>Select Customer</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Sales Person</label>
                    <select class="form-select">
                        <option>Select User</option>
                    </select>
                </div>

            </div>

            <hr>

            <div class="row mb-3">

                <div class="col-md-5">
                    <input type="text"
                           class="form-control"
                           placeholder="Barcode / Product Name">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-success w-100">
                        Add Item
                    </button>
                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead>

                    <tr>

                        <th width="5%">#</th>

                        <th>Product</th>

                        <th width="10%">Stock</th>

                        <th width="10%">Qty</th>

                        <th width="12%">Price</th>

                        <th width="10%">Disc.</th>

                        <th width="12%">Total</th>

                        <th width="8%">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    <tr>

                        <td>1</td>

                        <td>Coca Cola 250ml</td>

                        <td class="text-center">
                            120
                        </td>

                        <td>
                            <input type="number"
                                   class="form-control text-center"
                                   value="2">
                        </td>

                        <td>
                            <input type="number"
                                   class="form-control text-end"
                                   value="50">
                        </td>

                        <td>
                            <input type="number"
                                   class="form-control text-end"
                                   value="0">
                        </td>

                        <td class="text-end">
                            100.00
                        </td>

                        <td class="text-center">
                            <button class="btn btn-danger btn-sm">
                                Remove
                            </button>
                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

            <div class="row mt-4">

                <div class="col-md-7">

                    <label>Remarks</label>

                    <textarea class="form-control"
                              rows="7"></textarea>

                </div>

                <div class="col-md-5">

                    <div class="summary-box">

                        <table class="table">

                            <tr>
                                <th>Subtotal</th>
                                <td>
                                    <input class="form-control text-end"
                                           value="1000">
                                </td>
                            </tr>

                            <tr>
                                <th>Discount</th>
                                <td>
                                    <input class="form-control text-end"
                                           value="50">
                                </td>
                            </tr>

                            <tr>
                                <th>VAT</th>
                                <td>
                                    <input class="form-control text-end"
                                           value="15">
                                </td>
                            </tr>

                            <tr class="table-warning">

                                <th>Grand Total</th>

                                <td>

                                    <input class="form-control fw-bold text-end"
                                           value="965">

                                </td>

                            </tr>

                            <tr>

                                <th>Paid</th>

                                <td>

                                    <input class="form-control text-end"
                                           value="965">

                                </td>

                            </tr>

                            <tr>

                                <th>Due</th>

                                <td>

                                    <input class="form-control text-end text-danger fw-bold"
                                           value="0">

                                </td>

                            </tr>

                        </table>

                        <div class="d-grid gap-2">

                            <button class="btn btn-primary btn-lg">
                                Update Invoice
                            </button>

                            <button class="btn btn-secondary">
                                Cancel
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>