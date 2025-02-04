<?php

namespace App\Http\Controllers\FindGym;

use App\Http\Controllers\Controller;
use App\Services\SerperApiService;
use Illuminate\Http\Request;

class FindGymController extends Controller
{
    //
    protected $serperService;

    public function __construct(SerperApiService $serperApiService) 
    {
        $this->serperService = $serperApiService;
    }

    public function index() 
    {
        return view('find_gym');
    }

    public function searchGym(Request $request) 
    {
        $location = $request->input('location');

        $results = $this->serperService->searchNearbyGyms($location);
        return response()->json($results);
    }
}
