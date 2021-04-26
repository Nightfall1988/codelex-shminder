<?php
namespace App\Services;
use App\Views\View;

class HomeService
{
    public function __construct()
    {
        
    }
    public function showForms()
    {
        $view = new View;
        return $view->frontPage();
    }
}
?>