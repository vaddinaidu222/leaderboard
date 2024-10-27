<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRank;
use App\Models\UserActivity; 
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\Telescope;


class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'day');

        Log::info('Filter applied: ' . print_r($filter, true));
        $users = $this->user_helper($filter);

        return view('leaderboard.index', compact('users'));
    }



    public function search(Request $request)
    {
       
        $userId = $request->input('user_id');
        $user = User::withCount('activities')->find($userId);
      
        $user->finalRank = $user->rank->daily_rank ?? 'N/A';
       

        return view('leaderboard.index', ['users' => $user ? [$user] : []]);
    }


    public function recalculate()
    {
           
            $users = User::withCount('activities')->get();
            foreach ($users as $user) {
         
                $dailyActivitiesCount = $user->activities()->whereDate('activity_date', now())->count();
                $monthlyActivitiesCount = $user->activities()->whereMonth('activity_date', now()->month)
                                                          ->whereYear('activity_date', now()->year)
                                                          ->count();
                $yearlyActivitiesCount = $user->activities()->whereYear('activity_date', now()->year)->count();
            
               
                $dailyRankingData = User::withCount(['activities' => function ($query) {
                    $query->whereDate('activity_date', now());
                }])
                ->orderBy('activities_count', 'desc') 
                ->get();
            
                $dailyRanking = 1; 
                $previousCount = null; 
                $currentRank = 1;
            
                foreach ($dailyRankingData as $rankedUser) {
                    if ($previousCount === null || $rankedUser->activities_count != $previousCount) {
                        $dailyRanking = $currentRank; 
                    }
            
                    if ($rankedUser->id === $user->id) {
                        break; 
                    }
            
                    $previousCount = $rankedUser->activities_count; 
                    $currentRank++; 
                }
            
              
                $monthlyRankingData = User::withCount(['activities' => function ($query) {
                    $query->whereMonth('activity_date', now()->month)
                          ->whereYear('activity_date', now()->year);
                }])
                ->orderBy('activities_count', 'desc')
                ->get();
            
                $monthlyRanking = 1;
                $previousMonthlyCount = null;
                $currentMonthlyRank = 1;
            
                foreach ($monthlyRankingData as $rankedUser) {
                    if ($previousMonthlyCount === null || $rankedUser->activities_count != $previousMonthlyCount) {
                        $monthlyRanking = $currentMonthlyRank; 
                    }
            
                    if ($rankedUser->id === $user->id) {
                        break; 
                    }
            
                    $previousMonthlyCount = $rankedUser->activities_count; 
                    $currentMonthlyRank++; 
                }
            
               
                $yearlyRankingData = User::withCount(['activities' => function ($query) {
                    $query->whereYear('activity_date', now()->year);
                }])
                ->orderBy('activities_count', 'desc')
                ->get();
            
                $yearlyRanking = 1;
                $previousYearlyCount = null;
                $currentYearlyRank = 1;
            
                foreach ($yearlyRankingData as $rankedUser) {
                    if ($previousYearlyCount === null || $rankedUser->activities_count != $previousYearlyCount) {
                        $yearlyRanking = $currentYearlyRank;
                    }
            
                    if ($rankedUser->id === $user->id) {
                        break;
                    }
            
                    $previousYearlyCount = $rankedUser->activities_count;
                    $currentYearlyRank++;
                }
            
               
                UserRank::updateOrCreate(
                    ['user_id' => $user->id], 
                    [
                        'daily_rank' => $dailyRanking,
                        'monthly_rank' => $monthlyRanking,
                        'yearly_rank' => $yearlyRanking,
                    ]
                );
            }
            
            
            
            
        
            $updatedUsers = $this->user_helper('day');

            
           return view('partials.leaderboard_rows', compact('updatedUsers'));
    }



    public function user_helper($filter)
    {
            
            $users = User::withCount(['activities' => function($query) use ($filter) {
                if ($filter === 'day') {
                    $query->whereDate('activity_date', now());
                } elseif ($filter === 'month') {
                    $query->whereMonth('activity_date', now()->month)
                        ->whereYear('activity_date', now()->year);
                } elseif ($filter === 'year') {
                    $query->whereYear('activity_date', now()->year);
                }
            }])->with('rank')->get(); 

        
            foreach ($users as $user) {
                if ($filter === 'day') {
                    $user->finalRank = $user->rank->daily_rank ?? 'N/A';
                } elseif ($filter === 'month') {
                    $user->finalRank = $user->rank->monthly_rank ?? 'N/A';
                } elseif ($filter === 'year') {
                    $user->finalRank = $user->rank->yearly_rank ?? 'N/A';
                } else {
                    $user->finalRank = 'N/A';
                }
            }

            $users = $users->sortBy(function ($user) {
                return $user->finalRank === 'N/A' ? PHP_INT_MAX : $user->finalRank;
            });

        return $users;
    }

}


