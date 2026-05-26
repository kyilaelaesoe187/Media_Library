<?php

namespace App\Controller;

use App\Service\CatalogService;

class DetailsController extends BaseController
{
    public function __construct(
        private CatalogService $service
    ) {}

    public function show(): void
    {
        $this->requireLogin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirect('?page=catalog');
        }

        $item = $this->service->getById($id);

        if (!$item) {
            $this->redirect('?page=catalog');
        }

        $this->render('details', [
            'item' => $item,
            'pageTitle' => $item['title'] ?? 'Details',
            'category' => $item['category'] ?? null
        ]);
    }
}
