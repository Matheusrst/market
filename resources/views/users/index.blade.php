<!-- resources/views/users/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>User List</h1>

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

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Wallet</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>${{ $user->wallet }}</td>
                        <td>{{ $user->created_at }}</td>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
