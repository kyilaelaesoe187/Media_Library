<?php

namespace App\Controller;

use App\Service\CatalogService;

class CatalogController extends BaseController
{
    public function __construct(
        private CatalogService $service
    ) {}

    public function home(): void
    {
        $this->requireLogin();

        $this->render(
            'home',
            $this->service->getHomePageData()
        );
    }

    public function index(): void
    {
        $this->requireLogin();

        $this->render(
            'catalog',
            $this->service->getCatalogPage($_GET)
        );
    }
}