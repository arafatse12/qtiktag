@extends('layouts.master')

@section('title', 'Barcode')

@push('styles')
<style>
  table { border-collapse: collapse; width: 100%; margin-top: 20px; }
  table, th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
  img { max-height: 80px; }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Fill Up Product Details</h5>

    <div class="card">
      <div class="card-header text-bg-primary">
        <h4 class="mb-0 text-white">BarCode Generate Form</h4>
      </div>

      <form id="barcodeForm" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <h4 class="card-title">Product Info</h4>

          <div class="mb-3">
            <label class="form-label">Upload Image/PDF</label>
            <input
              type="file"
              name="file"
              id="file"
              class="form-control"
              accept=".jpg,.jpeg,.png,.pdf"
            />
          </div>

          <div class="row pt-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Order No</label>
                <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Ex:458989-5789" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Product No</label>
                <input type="text" name="product_no" id="product_no" class="form-control" placeholder="Ex:131572" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Season</label>
                <input type="text" name="season" id="season" class="form-control" placeholder="Ex:3-2026" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" placeholder="10" min="1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Color Code</label>
                <input type="text" name="color_code" id="color_code" class="form-control" placeholder="Ex:002" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Size Code (S count)</label>
                <input type="number" name="size_code" id="size_code" class="form-control" placeholder="1" />
              </div>
            </div>
          </div>
        </div>

        <hr />

        <div class="form-actions">
          <div class="card-body border-top d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn bg-danger-subtle text-danger">Cancel</button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>

<div id="result" class="mt-3"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
  'use strict';

  const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': csrfToken }
  });

  function showAlert(message) {
    alert(message);
  }

  // OCR: file change -> POST to extract
  $('#file').on('change', function () {
    const fileInput = this;
    if (!fileInput.files || !fileInput.files[0]) return;

    const file = fileInput.files[0];
    const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!allowed.includes(file.type)) {
      showAlert('Please upload a JPG, PNG, or PDF.');
      fileInput.value = '';
      return;
    }

    const formData = new FormData();
    formData.append('file', file);

    $.ajax({
      url: "{{ route('barcode.extract') }}",
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false
    })
    .done(function (res) {
      // If backend returns {ok:false, data:{...}}, still prefill fields
      const data = (res && res.data) ? res.data : res;
      if (!data) return;

      $('#order_no').val(data.order_no || '');
      $('#product_no').val(data.product_no || '');
      $('#season').val(data.season || '');
      $('#quantity').val(data.quantity || '');
      $('#color_code').val(data.color_code || '');
      $('#size_code').val(data.size_code || '');

      if (res && res.ok === false && res.message) {
        showAlert(res.message);
      }
    })
    .fail(function (xhr) {
      const msg = xhr?.responseJSON?.message || xhr?.responseJSON?.error || 'OCR failed. Please try another file.';
      showAlert('OCR Error: ' + msg);
    });
  });

  // Generate batch
  $('#barcodeForm').on('submit', function (e) {
    e.preventDefault();

    const payload = $(this).serialize();

    $.ajax({
      url: "{{ route('barcode.batch') }}",
      method: 'POST',
      data: payload
    })
    .done(function (res) {
      if (res?.ok) {
        let html = `
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>GTIN-14</th>
                <th>GTIN-16</th>
                <th>QR Code</th>
              </tr>
            </thead>
            <tbody>
        `;
        (res.items || []).forEach(function (item) {
          html += `
            <tr>
              <td>${item.index ?? ''}</td>
              <td>${item.gtin14 ?? ''}</td>
              <td>${item.gtin16 ?? ''}</td>
              <td>${item.qr ? `<img src="${item.qr}" alt="QR" />` : ''}</td>
            </tr>
          `;
        });
        html += `</tbody></table>`;
        $('#result').html(html);
      } else {
        showAlert(res?.message || 'Generation failed');
      }
    })
    .fail(function (xhr) {
      const msg = xhr?.responseJSON?.message || xhr?.responseJSON?.error || 'Request failed';
      showAlert('Error: ' + msg);
    });
  });
});
</script>
@endsection
