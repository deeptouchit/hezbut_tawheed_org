<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Leader;

class PageDataService
{
    /**
     * Get specific data needed for rendering a page based on its slug.
     *
     * @param string $slug
     * @param \App\Models\Page $page
     * @return array
     */
    public function getPageData($slug, $page)
    {
        return match($slug) {
            'about-us' => $this->getAboutUsData($page),
            'publications' => $this->getPublicationsData($page),
            'social' => $this->getSocialData($page),
            'training' => $this->getTrainingData($page),
            'research' => $this->getResearchData($page),
            default => ['page' => $page]
        };
    }
    
    /**
     * Get data required for About Us page template.
     *
     * @param \App\Models\Page $page
     * @return array
     */
    protected function getAboutUsData($page)
    {
        return [
            'page' => $page,
        ];
    }
    
    /**
     * Get data required for Publications page.
     *
     * @param \App\Models\Page $page
     * @return array
     */
    protected function getPublicationsData($page)
    {
        $categories = \App\Models\BookCategory::where('is_active', true)->get();
        $categorySlug = request()->get('category');
        $selectedCategory = null;

        if ($categorySlug) {
            $selectedCategory = \App\Models\BookCategory::where('slug', $categorySlug)->first();
        }

        $popularQuery = Book::where('is_active', true)->where('is_popular', true);
        $bestsellerQuery = Book::where('is_active', true)->where('is_bestseller', true);
        $booksQuery = Book::where('is_active', true);

        if ($selectedCategory) {
            $popularQuery->where('category_id', $selectedCategory->id);
            $bestsellerQuery->where('category_id', $selectedCategory->id);
            $booksQuery->where('category_id', $selectedCategory->id);
        }

        $popularBooks = $popularQuery->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $bestSellingBooks = $bestsellerQuery->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $excludedIds = array_unique(array_merge(
            $popularBooks->pluck('id')->toArray(),
            $bestSellingBooks->pluck('id')->toArray()
        ));

        $books = $booksQuery->whereNotIn('id', $excludedIds)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return [
            'page' => $page,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'popularBooks' => $popularBooks,
            'bestSellingBooks' => $bestSellingBooks,
            'books' => $books
        ];
    }

    /**
     * Get data required for Social page.
     *
     * @param \App\Models\Page $page
     * @return array
     */
    protected function getSocialData($page)
    {
        return [
            'page' => $page,
            'activitiesPosts' => \App\Models\Blog::published()
                ->where('category_id', 2)
                ->orderBy('published_at', 'desc')
                ->paginate(9)
                ->withQueryString(),
        ];
    }

    /**
     * Get data required for Training page.
     *
     * @param \App\Models\Page $page
     * @return array
     */
    protected function getTrainingData($page)
    {
        $keywords = ['ক্রীড়া', 'খেলাধুলা', 'ফুটবল', 'টুর্নামেন্ট', 'ক্রিকেট', 'ব্যাডমিন্টন', 'মার্шал আর্ট', 'ক্যারাতে', 'শারীরিক প্যারেড'];
        
        $query = \App\Models\Blog::published();
        $query->where(function($q) use ($keywords) {
            foreach ($keywords as $kw) {
                $q->orWhere('title', 'like', '%' . $kw . '%');
            }
        });
        
        $query->where('title', 'not like', '%সালাহ%')
              ->where('title', 'not like', '%নামাজ%')
              ->where('title', 'not like', '%নামায%')
              ->where('title', 'not like', '%দাজ্জাল%');
              
        $posts = $query->orderBy('published_at', 'desc')->get();
        
        return [
            'page' => $page,
            'trainingPosts' => $posts
        ];
    }

    /**
     * Get data required for Research page.
     *
     * @param \App\Models\Page $page
     * @return array
     */
    protected function getResearchData($page)
    {
        $researchPosts = \App\Models\Blog::published()
            ->where('category_id', 1) // আদর্শিক ও ধর্মীয় দৃষ্টিভঙ্গি
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        $researchBooks = \App\Models\Book::where('is_active', true)
            ->orderBy('title', 'asc')
            ->take(4)
            ->get();

        return [
            'page' => $page,
            'researchPosts' => $researchPosts,
            'researchBooks' => $researchBooks
        ];
    }
}


