<?php
namespace App\Middlewares;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle()
    {
        if (!isset($_SESSION['AuthId'])) {
            header('Location: /enter');
        } else {
            
        }
    }
}
?>
