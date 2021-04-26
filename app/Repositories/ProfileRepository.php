<?php
namespace App\Repositories;
use App\Models\Profile;
use App\Repositories\Database;
use Twig\Profiler\Profile as ProfilerProfile;

class ProfileRepository
{
    private Database $database; 

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getLikes(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function register(Profile $profile, $password): void
    {

        // vALIDATE THAT USERNAM DOESNT EXIST YET
        $name = $profile->getName();
        $gender = $profile->getGender();
        $age = $profile->getAge();
        $sql = "INSERT INTO clients (Name, Password, Age, Gender) VALUES (" 
                                . "'" . $name . "', " . '"' . $password . '", '
                                . "'" . $age . "', " . "'" . $gender . "');";
        $this->database->query($sql);

    }

    public function getProfileByName(string $name): array
    {
        $sql = "SELECT * FROM clients WHERE Name = " . '"' . $name . '";';
        $query = $this->database->query($sql);
        $row = mysqli_fetch_assoc($query);
        return $row;
    }

    public function getProfileById(string $ID): array
    {
        $sql = "SELECT * FROM clients WHERE ID = " . '"' . $ID . '";';
        $query = $this->database->query($sql);
        $row = mysqli_fetch_assoc($query);
        return $row;
    }

    public function getProfileLikesIDs(string $authId): array
    {
        $allLikeIDs = [];
        $sql = "SELECT ID_liked_user FROM likes WHERE ID_user = " . "'" . $authId . "'";
        $query = $this->database->query($sql);

        while ($row = mysqli_fetch_assoc($query))
        {
            $allLikeIDs[] = $row['ID_liked_user'];
        }
        return $allLikeIDs;
    }

    public function getProfileImages(string $authId): array
    {
        $photoAddresses = [];
        $sql = "SELECT * FROM photos WHERE ID_user = " . "'" . $authId . "'";
        $query = $this->database->query($sql);
        
        while ($row = mysqli_fetch_assoc($query))
        {
            $photoAddresses[] = $row['photo_address'];
        }
        
        return $photoAddresses;
    }

    public function getClientTableLength(): int
    {
        $sql = "SELECT COUNT(*) FROM Clients";
        $query = $this->database->query($sql);
        $row = mysqli_fetch_assoc($query);
        return $row['COUNT(*)'];
    }

    public function addLikeToProfile(Profile $activeProfile, Profile $likedProfile): void
    {
        $activeUserId = $activeProfile->getID();
        $likedProfileId = $likedProfile->getID();
        $sql = "INSERT INTO likes (ID_user, ID_liked_user) VALUES (" . "'" . $activeUserId . "', " . "'" . $likedProfileId . "');";
        $this->database->query($sql);
    }
}





?>