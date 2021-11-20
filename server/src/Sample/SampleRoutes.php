<?php

namespace Sample;

use Ninja\Authentication;
use Sample\Controller\SampleController;

class SampleRoutes implements \Ninja\NJInterface\IRoutes
{
    public function __construct()
    {
    }

    public function getRoutes(): array
    {
        $sampleController = new SampleController();

        return [
            '/' => [
                'GET' => [
                    'controller' => $sampleController,
                    'action' => 'show_home_page'
                ]
            ],
        ];
    }

    public function getAuthentication(): ?Authentication
    {
        return null;
    }

    public function checkPermission($permission): ?bool
    {
        return null;
    }
}
