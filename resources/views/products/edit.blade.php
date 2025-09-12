<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <a href="{{ route('products.index') }}">Back</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li style="color:red">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')
        <p>
            Name: <input type="text" name="name" value="{{ old('name', $product->name) }}">
        </p>
        {{-- <p>
            SKU: <input type="text" name="sku" value="{{ old('sku', $product->sku) }}">
        </p> --}}
        <button type="submit">Update</button>
    </form>
</body>
</html>
