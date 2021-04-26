<?php
namespace App\Controllers;
use App\Services\FileService;
use App\Models\Profile;

class FileController
{
    private FileService $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }
    
    public function save()
    {
        $userId = $_SESSION['AuthId'];
        $this->service->setupImg();
        $this->service->checkIfImg();
        $this->service->save($userId);
    }

    public function getImg(Profile $profile)
    {
        //$this->service->getImagesByProfile($profile);
        return  $this->service->getImagesByProfile($profile);
    }
}

?>