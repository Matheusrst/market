<!-- resources/views/users/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Menu</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>User Menu</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                <td>||</td>
                    <th>ID</th>
                    <td>||</td>
                    <th>Name</th>
                    <td>||</td>
                    <th>Email</th>
                    <td>||</td>
                    <th>Wallet</th>
                    <td>||</td>
                    <th>Actions</th>
                    <td>||</td>
                    <th>Created At</th>
                    <td>||</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>||</td>
                    <td>{{ $user->id }}</td>
                    <td>||</td>
                    <td>{{ $user->name }}</td>
                    <td>||</td>
                    <td>{{ $user->email }}</td>
                    <td>||</td>
                    <td>${{ $user->wallet }}</td>
                    <td>||</td>
                    <td>
                            <form action="{{ route('users.addFunds', $user->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount" required>
                                <button type="submit" class="btn btn-success">Add Funds</button>
                            </form>
                            <form action="{{ route('users.withdrawFunds', $user->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="number" name="amount" step="0.01" min="0.01" max="{{ $user->wallet }}" placeholder="Amount" required>
                                <button type="submit" class="btn btn-warning">Withdraw Funds</button>
                            </form>
                        </td>
                        <td>||</td>
                    <td>{{ $user->created_at }}</td>
                    <td>||</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit Profile</a>
                        <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('delete-user-form').submit();">Delete User</a>
                        <form id="delete-user-form" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('GET')
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
        </form>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Return to Products</a>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
