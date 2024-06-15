<!-- resources/views/products/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Product List</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
            <th>||</th>
            <a href="{{ route('users.showLoginForm') }}" class="btn btn-primary">Login</a>
            <th>||</th>
            <a href="{{ route('users.showRegistrationForm') }}" class="btn btn-secondary">Register</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>||</th>
                    <th>Name</th>
                    <th>||</th>
                    <th>Description</th>
                    <th>||</th>
                    <th>Price</th>
                    <th>||</th>
                    <th>Stock</th>
                    <th>||</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <th>||</th>
                        <td>{{ $product->name }}</td>
                        <th>||</th>
                        <td>{{ $product->description }}</td>
                        <th>||</th>
                        <td>{{ $product->price }}</td>
                        <th>||</th>
                        <td>{{ $product->stock }}</td>
                        <th>||</th>
                        <td>
                            @if($product->stock > 0)
                                <form action="{{ route('products.purchase', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Buy</button>
                                </form>
                            @else
                                <button class="btn btn-secondary" disabled>Out of Stock</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
