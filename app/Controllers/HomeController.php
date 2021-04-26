<?php
namespace App\Controllers;
use App\Services\HomeService;
use App\Views\View;

class HomeController
{
    private HomeService $service;
    
    public function __construct(HomeService $service)
    {
        $this->service = $service;
    }

    public function home()
    {
        return [$this->service->showForms()];
    }

    public function shortPasswordNotification()
    {
        $view = new View;
        $this->shortPasswordNotification();
    }
}
?>