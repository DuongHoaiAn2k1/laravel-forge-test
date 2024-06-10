<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\LogTestJob;

class TestQueueController extends Controller
{
    public function testQueue()
    {
        // Dispatch the LogTestJob
        LogTestJob::dispatch();

        return response()->json(['message' => 'Job dispatched successfully!']);
    }
}
