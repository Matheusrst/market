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
        <th>||</th>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
            <th>||</th>
            <a href="{{ route('users.showLoginForm') }}" class="btn btn-primary">Login</a>
            <th>||</th>
            <a href="{{ route('users.showRegistrationForm') }}" class="btn btn-secondary">Register</a>
            <th>||</th>
            <a href="{{ route('users.index') }}" class="btn btn-info">User Menu</a>
            <th>||</th>
        </div>

        @if (Auth::check())
            <div class="alert alert-info mt-3">
                Logged in as: {{ Auth::user()->name }} ({{ Auth::user()->email }}) <br>
                Wallet Balance: ${{ Auth::user()->wallet }}
            </div>
        @endif
        <th>|</th>
        <table class="table">
            <thead>
                <tr>
                <th>||</th>
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
                    <th>||</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                    <th>||</th>
                        <td>{{ $product->id }}</td>
                        <th>||</th>
                        <td>{{ $product->name }}</td>
                        <th>||</th>
                        <td>{{ $product->description }}</td>
                        <th>||</th>
                        <td>${{ $product->price }}</td>
                        <th>||</th>
                        <td>{{ $product->stock }}</td>
                        <th>||</th>
                        <td>
                            @if($product->stock > 0)
                                <form action="{{ route('products.purchase', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Buy</button>
                                </form>
                            @else
                                <button class="btn btn-secondary" disabled>Out of Stock</button>
                            @endif
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $product->id }})">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('delete-form-' + productId).submit();
            }
        }
    </script>
</body>
</html>
