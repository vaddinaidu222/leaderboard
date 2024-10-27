@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Leaderboard</h1>

       
        <form method="POST" action="{{ route('leaderboard.search') }}">
            @csrf
            <input type="text" name="user_id" placeholder="Search by User ID" required>
            <button type="submit">Search</button>
        </form>

      
        <form method="GET" action="{{ route('leaderboard.index') }}">
            <select name="filter" onchange="this.form.submit()">
                <option value="">Select Filter</option>
                <option value="day">Today</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Total Points</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->activities_count * 20 }}</td>
                        <td>{{  $user->finalRank }}</td> 
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form id="recalculate-form" method="POST" action="{{ route('leaderboard.recalculate') }}">
            @csrf
            <button type="button" id="recalculate-button">Recalculate Leaderboard</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
          
            $('#recalculate-button').on('click', function() {
                $.ajax({
                    url: '{{ route("leaderboard.recalculate") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                       
                        $('tbody').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
@endsection
