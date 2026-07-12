<?php

namespace App\Http\Controllers;

use App\Models\Leader;
use Illuminate\Http\Request;

class LeadershipController extends Controller
{
    /**
     * Display the leadership directory dashboard.
     */
    public function index()
    {
        // 1. Load active founders (Spotlight leaders)
        $founders = Leader::where('is_founder', true)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 2. Load other active leaders grouped by category
        $central = Leader::where('is_founder', false)
            ->where('category', 'central')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $advisory = Leader::where('is_founder', false)
            ->where('category', 'advisory')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $executive = Leader::where('is_founder', false)
            ->where('category', 'executive')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $regional = Leader::where('is_founder', false)
            ->where('category', 'regional')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // 3. Load hierarchical tree structure for Organization Diagram
        $treeLeaders = Leader::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }, 'children.children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }, 'children.children.children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('theme::pages.leadership', compact('founders', 'central', 'advisory', 'executive', 'regional', 'treeLeaders'));
    }

    /**
     * Display a specific leader's detailed profile page.
     */
    public function show($slug)
    {
        $leader = Leader::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $books = $leader->books;

        return view('theme::pages.leader_profile', compact('leader', 'books'));
    }

    /**
     * Get leader details as JSON for the slide-in drawer.
     */
    public function getDetailsJson($id)
    {
        try {
            $leader = Leader::findOrFail($id);
            
            // Map books for dynamic rendering inside the drawer
            $books = $leader->books->map(function($book) {
                return [
                    'title' => $book->title,
                    'image' => $book->image_url,
                    'read_url' => route('library.read', $book->slug)
                ];
            });

            return response()->json([
                'success' => true,
                'leader' => $leader,
                'image_url' => $leader->image_url,
                'signature_url' => $leader->signature_url,
                'books' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'লিডারের প্রোফাইল লোড করা যায়নি!'
            ], 404);
        }
    }

    /**
     * Display the organizational structure tree diagram.
     */
    public function structure()
    {
        $treeLeaders = Leader::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }, 'children.children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }, 'children.children.children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('theme::pages.structure', compact('treeLeaders'));
    }
}
