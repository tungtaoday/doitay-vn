<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ratings</title>
</head>
<body>
    <h1>Danh sách đánh giá</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Company</th>
                <th>Category</th>
                <th>Feature</th>
                <th>Score</th>
                <th>Suggest</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ratings as $rating)
                <tr>
                    <td>{{ $rating->id }}</td>
                    <td>{{ $rating->user->name ?? 'N/A' }}</td>
                    <td>{{ $rating->company->name ?? 'N/A' }}</td>
                    <td>{{ $rating->category->name ?? 'N/A' }}</td>
                    <td>{{ $rating->feature->name ?? 'N/A' }}</td>
                    <td>{{ $rating->score }}</td>
                    <td>{{ $rating->suggest }}</td>
                    <td>{{ $rating->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Không có đánh giá nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
