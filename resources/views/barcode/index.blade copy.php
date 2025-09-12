@extends('layouts.master')

@section('title', 'Barcode')

@section('content')
<style>
        table { border-collapse: collapse; width: 100%; margin-top:20px;}
        table, th, td { border:1px solid #ccc; padding:8px; text-align:center;}
        img { max-height: 80px; }
    </style>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Fill Up Product Details</h5>
            <div class="card">
            <div class="card-header text-bg-primary">
                <h4 class="mb-0 text-white">BarCode Generate Form</h4>
            </div>
            <form id="barcodeForm">
                @csrf
                <div>
                <div class="card-body">
                    <h4 class="card-title">Product Info</h4>
                    <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Order No</label>
                        <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Ex:458989-5789">
                        
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="mb-3 has-danger">
                        <label class="form-label">Product No</label>
                        <input type="text" name="product_no" id="product_no" class="form-control" placeholder="Ex:131572">
                        
                        </div>
                    </div>
                    <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                    {{-- <div class="col-md-6">
                        <div class="mb-3 has-success">
                        <label class="form-label">Gender</label>
                        <select class="form-select">
                            <option value="">Male</option>
                            <option value="">Female</option>
                        </select>
                        <small class="form-control-feedback">
                            Select your gender
                        </small>
                        </div>
                    </div> --}}

                    <div class="col-md-6">
                        <div class="mb-3 has-success">
                        <label class="form-label">Season</label>
                        
                         <input type="text" name="season" id="season" class="form-control" placeholder="Ex:3-2026">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="10">
                        </div>
                    </div>
                    <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 has-success">
                        <label class="form-label">Color Code</label>
                        
                         <input type="text" name="color_code" id="color_code" class="form-control" placeholder="Ex:002">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Size Code</label>
                        <input type="number" class="form-control" name="size_code" id="size_code" placeholder="10">
                        </div>
                    </div>
                    <!--/span-->
                    </div>
                </div>
                <hr>
                {{-- <div class="card-body">
                    <!--/row-->
                    <h4 class="card-title mb-4">Address</h4>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                        <label class="form-label">Street</label>
                        <input type="text" class="form-control">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control">
                        </div>
                    </div>
                    <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Post Code</label>
                        <input type="text" class="form-control">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select class="form-select">
                            <option>--Select your Country--</option>
                            <option>India</option>
                            <option>Sri Lanka</option>
                            <option>USA</option>
                        </select>
                        </div>
                    </div>
                    <!--/span-->
                    </div>
                </div> --}}
                <div class="form-actions">
                    <div class="card-body border-top">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                    <button type="button" class="btn bg-danger-subtle text-danger ms-6">
                        Cancel
                    </button>
                    </div>
                </div>
                </div>
            </form>
            </div>
            
        </div>
    </div>   
 <div id="result"></div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(function(){
        $('#barcodeForm').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('barcode.batch') }}",
                method: "POST",
                data: $(this).serialize(),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    if(res.ok){
                        let html = `<table>
                            <tr>
                            <th>ID</th>
                            <th>GTIN-14</th>
                            <th>GTIN-16</th>
                            <th>QR Code</th>
                            </tr>`;
                        res.items.forEach(item=>{
                            html += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.gtin14}</td>
                                    <td>${item.gtin16}</td>
                                    <td><img src="${item.qr}"></td>
                                </tr>`;
                        });
                        html += `</table>`;
                        $('#result').html(html);
                    } else {
                        alert(res.message);
                    }
                },
                error: function(err){
                    alert("Error: "+err.responseJSON.message);
                }
            });
        });
    });
    </script>

@endsection