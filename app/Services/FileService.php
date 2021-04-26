<?php
namespace App\Services;
use App\Repositories\FileRepository;
use App\Models\Profile;

class FileService 
{
  private FileRepository $fileRepository;

  public function __construct(FileRepository $fileRepository)
  {
    $this->fileRepository = $fileRepository;
  }

  public function setupImg()
    {
        $this->targetDir = "storage/";
        $this->targetFile = $this->targetDir . basename($_FILES["fileToUpload"]["name"]);
        $this->uploadSafe = true;
        $imageFileType = strtolower(pathinfo($this->targetFile,PATHINFO_EXTENSION));

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $this->uploadSafe = true;
        }
    }

    public function checkIfImg(): bool
    {
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
              $this->uploadSafe = true;
            } else {
              $this->uploadSafe = false;
            }
          }
          return $this->uploadSafe;
    }

    public function checkImgSize()
    {
      if ($_FILES["fileToUpload"]["size"] > 500000) {
        $this->uploadSafe = false;
      }
    }
    public function save(string $userId)
    {      
        if ($this->uploadSafe == true) {
          $this->fileRepository->save($this->targetFile, $userId);
        }
    }

    public function getImagesByProfile(Profile $profile) 
    {
      $picName = $profile->getImages()[0];
      $file = file_get_contents($picName);

      return $file;
      //$imgAddresses = $this->fileRepository->getImages($profile);

    }
}


?>