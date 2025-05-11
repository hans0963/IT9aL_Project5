<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
</head>
<body>
    <h1>Edit Supplier</h1>
    <a href="{{ route('suppliers.index') }}">Back to Suppliers</a>

    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required>
            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact_info">Contact Info:</label>
            <input type="text" name="contact_info" id="contact_info" value="{{ old('contact_info', $supplier->contact_info) }}" required>
            @error('contact_info')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="address">Address:</label>
            <textarea name="address" id="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
            @error('address')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Update Supplier</button>
        <a href="{{ route('suppliers.index') }}" style="padding: 10px 15px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px;">Cancel</a>
    </form>
</body>
</html>