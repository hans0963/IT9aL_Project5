<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" required>
            @error('price')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity') }}" required>
            @error('stock_quantity')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="supplier_id">Supplier:</label>
            <select name="supplier_id" id="supplier_id" required>
                <option value="" disabled selected>Select a supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Create Product</button>
        <a href="{{ route('products.index') }}" style="padding: 10px 15px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px;">Cancel</a>
    </form>
</body>
</html>