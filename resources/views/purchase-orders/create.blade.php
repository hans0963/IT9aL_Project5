<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchase Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Create Purchase Order</h1>
    <form action="{{ route('purchase-orders.store') }}" method="POST">
        @csrf
       <div class="mb-3">
            <label for="barcode" class="form-label">Enter Barcode</label>
            <input type="text" id="barcode" class="form-control" placeholder="Scan or Enter Barcode">
            <button type="button" id="add-product" class="btn btn-primary mt-2">Add Product</button>
            <!-- QR Scanner Button -->
            <a href="http://127.0.0.1:8000/qr-scanner" class="btn btn-outline-secondary mt-2 ms-2">Open QR Scanner</a>
        </div>

        <h2>Order Details</h2>
        <table class="table table-bordered" id="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Order Quantity</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamically added rows will go here -->
            </tbody>
        </table>

        <div class="mt-3">
            <h4>Overall Price: <span id="overall-price">0.00</span></h4>
        </div>

        <input type="hidden" name="orderDetails" id="orderDetails">
        <button type="submit" class="btn btn-success mt-3">Check-Out</button>
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>

<script>
    let overallPrice = 0;

    // Add product to the table
    $('#add-product').on('click', function () {
        const barcode = $('#barcode').val();

        if (!barcode) {
            alert('Please enter a barcode!');
            return;
        }

        // AJAX request to fetch product details
        $.ajax({
            url: '/get-product-by-barcode',
            method: 'GET',
            data: { barcode: barcode },
            success: function (product) {
                if (!product) {
                    alert('Product not found!');
                    return;
                }

                const row = `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.price}</td>
                        <td>
                            <input type="number" class="form-control quantity" value="1" min="1">
                        </td>
                        <td class="total-price">${product.price}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
                        </td>
                    </tr>
                `;
                $('#order-table tbody').append(row);
                updateOverallPrice();
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    alert('Product not found!');
                } else {
                    alert('Error fetching product details!');
                }
            }
        });
    });

    $(document).on('input', '.quantity', function () {
        const quantity = $(this).val();
        const price = $(this).closest('tr').find('td:nth-child(3)').text();
        const totalPrice = quantity * price;

        $(this).closest('tr').find('.total-price').text(totalPrice.toFixed(2));
        updateOverallPrice();
    });

    $(document).on('click', '.remove-product', function () {
        $(this).closest('tr').remove();
        updateOverallPrice();
    });

    function updateOverallPrice() {
        overallPrice = 0;

        $('#order-table tbody tr').each(function () {
            const totalPrice = parseFloat($(this).find('.total-price').text());
            overallPrice += totalPrice;
        });

        $('#overall-price').text(overallPrice.toFixed(2));
    }
</script>
</body>
</html>