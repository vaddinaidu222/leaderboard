{{-- partials/leaderboard_rows.blade.php --}}
@foreach ($updatedUsers as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->activities_count * 20 }}</td>
        <td>{{ optional($user->rank)->daily_rank ?? 'N/A' }}</td>

    </tr>
@endforeach
