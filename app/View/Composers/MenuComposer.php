<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Helpers\MenuHelper;

class MenuComposer
{
    protected $menuData;
    protected $menuHelper;

    public function __construct()
    {
        $this->menuHelper = MenuHelper::class;
        $this->menuData   = MenuHelper::getMenuData();
    }

    public function compose(View $view)
    {
        $view->with([

            'topbarLeft'            => $this->menuData['topbar_left'] ?? [],
            'topbarRight'           => $this->menuData['topbar_right'] ?? [],
            'desktopNav'            => $this->menuData['desktop_nav'] ?? [],
            'mobileNav'             => $this->menuData['mobile_nav'] ?? [],
            'footerQuickLinks'      => $this->menuData['footer_quick_links'] ?? [],
            'footerCustomerService' => $this->menuData['footer_customer_service'] ?? [],
            'footerAbout'           => $this->menuData['footer_about'] ?? [],
            'footerContact'         => $this->menuData['footer_contact'] ?? [],
            'menuHelper'            => $this->menuHelper
        ]);
    }
}
