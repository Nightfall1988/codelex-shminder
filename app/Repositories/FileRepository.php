<?php
namespace App\Repositories;
use App\Repositories\Database;
use App\Models\Profile;

class FileRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function save(string $file, string $userId)
    {
        $sql = "INSERT INTO photos (ID_user, photo_address) VALUES (" . "'" . $userId . "', " . "'" . $file . "');";
        $this->database->query($sql);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file);
    }

    public function getImages(Profile $profile): array
    {
        $photoAddresses = [];
        $sql = "SELECT photo_address FROM photos WHERE ID_user = " . "'" . $profile->getID() . "';";
        $query = $this->database->query($sql);
        while ($row = mysqli_fetch_assoc($query))
        {
            $photoAddresses[] = $row['photo_address'];
        }
        return $photoAddresses;
    }
} 


?>