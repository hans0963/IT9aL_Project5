<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Purchase Orders</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">Create New Purchase Order</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrders as $purchaseOrder)
                <tr>
                    <td>{{ $purchaseOrder->id }}</td>
                    <td>{{ $purchaseOrder->supplier->name }}</td>
                    <td>{{ $purchaseOrder->date }}</td>
                    <td>{{ $purchaseOrder->total_amount }}</td>
                    <td>
                        <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>