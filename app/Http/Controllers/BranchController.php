<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of active branches.
     */
    public function index()
    {
        $branches = Branch::where('is_active', true)
            ->with(['officials' => function($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('theme::pages.branches', compact('branches'));
    }
}
