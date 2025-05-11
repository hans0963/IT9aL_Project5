<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Receipt</h1>
    <div class="mb-3">
        <h4>Purchase Order ID: {{ $purchaseOrder->id }}</h4>
        <h4>Supplier: {{ $purchaseOrder->supplier->name }}</h4>
        <h4>Date: {{ $purchaseOrder->date }}</h4>
    </div>

    <h2>Order Details</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Order Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        <h4>Overall Price: {{ number_format($purchaseOrder->total_amount, 2) }}</h4>
    </div>

    <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary mt-3">Back to Purchase Orders</a>
</div>
</body>
</html>