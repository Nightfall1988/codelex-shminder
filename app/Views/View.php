<?php
namespace App\Views;
use App\Collections\UserCollection;

class View
{
    public function frontPage(): string
    {
        $page = 'loginPage.php';
        return $page;
    }

    public function profilePage(): string
    {
        $page = 'profilePage.php';
        return $page;
    }

    public function matchPage()
    {
        return 'matchPage.php';
    }
}

?>