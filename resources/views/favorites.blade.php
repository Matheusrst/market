<!-- resources/views/favorites.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Favorites</h1>

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

        <div class="card">
            <div class="card-body">
                @if ($favorites->isEmpty())
                    <p>No favorite products yet.</p>
                @else
                    <ul class="list-group">
                        @foreach ($favorites as $favorite)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>{{ $favorite->product->name }}</h5>
                                    <p>{{ $favorite->product->description }}</p>
                                    <p>Price: ${{ $favorite->product->price }}</p>
                                </div>
                                <form action="{{ route('favorites.remove', $favorite->product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Back to Products</a>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
