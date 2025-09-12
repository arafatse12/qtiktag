<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <h1>Products List</h1>
    <a href="{{ route('products.create') }}">Create Product</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            {{-- <th>SKU</th> --}}
            <th>QR Code</th>
            <th>Actions</th>
        </tr>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            {{-- <td>{{ $product->sku }}</td> --}}
            <td>{!! QrCode::size(100)->generate(route('products.show', $product->id)) !!}</td>
            <td>
                <a href="{{ route('products.show', $product->id) }}">View</a> |
                <a href="{{ route('products.edit', $product->id) }}">Edit</a> |
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
