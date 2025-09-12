<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>
</head>
<body>
    <h1>Create Product</h1>
    <a href="{{ route('products.index') }}">Back</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li style="color:red">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <p>
            Name: <input type="text" name="name" value="{{ old('name') }}">
        </p>
        {{-- <p>
            SKU: <input type="text" name="sku" value="{{ old('sku') }}">
        </p> --}}

        <p>
            Generate Barcode Count:  <input type="text" name="barcode" value="">
        </p>
        <button type="submit">Create</button>
    </form>
</body>
</html>
