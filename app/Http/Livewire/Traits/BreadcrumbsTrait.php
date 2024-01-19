<?php

namespace App\Http\Livewire\Traits;

trait BreadcrumbsTrait
{
    public $breadcrumbs = [
    ];

    /**
     * Mount the component
     */
    public function bootBreadcrumbsTrait()
    {
        $this->addBreadcrumb(__('Home'), route('tenant.home'));
    }

    /**
     * Add a breadcrumb to the list
     * @param int|string $text
     * @param string $route
     * @param array $params
     * @return void
     */
    public function addBreadcrumb($text, $route = null, $params = [])
    {
        $this->breadcrumbs[] = [
            'text' => $text,
            'href' => $route,
            'params' => $params
        ];
    }
}
