<?php
namespace App\Models;
use App\Collections\UserCollection;

class Profile
{
    private string $name;

    private string $ID;

    private string $gender;

    private int $age;

    private array $images = [];

    private UserCollection $likes;

    private UserCollection $suggestedLikes;

    public function __construct(string $name, string $gender, int $age)
    {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
    }

    public function setID(string $ID): void
    {
        $this->ID = $ID;
    }


    public function getID(): string
    {
        return $this->ID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getLikes(): UserCollection
    {
        return $this->likes;
    }

    public function addLikes(UserCollection $collection)
    {
        $this->likes = $collection;
    }

    public function addImages(array $imageAddresses)
    {
        foreach ($imageAddresses as $imageAddress)
        $this->images[] = $imageAddress;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setSuggestedLikes(UserCollection $userCollection)
    {
        $this->suggestedLikes = $userCollection;
    }

    public function getSuggestedLikes()
    {
        return $this->suggestedLikes;
    }
}
?>