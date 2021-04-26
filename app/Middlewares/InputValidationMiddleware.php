<?php
namespace App\Middlewares;

class InputValidationMiddleware implements MiddlewareInterface

{
    public function handle()
    {
        if (count($_POST) == 4) {
            $this->validateRegistration($_POST);
        } else {
            $this->validateLogin($_POST);
        }
    }

    public function validateRegistration(array $post)
    {
        if (strlen($post['password']) < 1) {
            header('Location: /enter');
            die;
        }

    }

    public function validateLogin(array $post)
    {

    }
}
?>