<?php

namespace App\Http\Controllers\API\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        $branches = Branch::with('company')->get();
        return response()->json(['status' => true, 'data' => $branches]);
    }
}
