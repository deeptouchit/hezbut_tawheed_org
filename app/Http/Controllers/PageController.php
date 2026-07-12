<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\PageViewResolver;
use App\Services\PageDataService;

class PageController extends Controller
{
    use PageViewResolver;

    /**
     * @var PageDataService
     */
    protected $pageDataService;

    /**
     * PageController constructor.
     *
     * @param PageDataService $pageDataService
     */
    public function __construct(PageDataService $pageDataService)
    {
        $this->pageDataService = $pageDataService;
    }

    /**
     * Display the specified page.
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug)
    {
        $pageQuery = Page::where('slug', $slug);

        if (!auth()->check()) {
            $pageQuery->where('is_active', true);
        }

        $page = $pageQuery->firstOrFail();

        // Get additional page-specific data
        $data = $this->pageDataService->getPageData($slug, $page);

        // Resolve and render view (checks if theme::pages.$slug exists, otherwise falls back)
        return $this->resolvePageView($slug, $page, $data);
    }
}
