<?php
namespace App\Services;

use App\Collections\UserCollection;
use App\Models\Profile;
use App\Repositories\ProfileRepository;

class ProfileService

{
    private ProfileRepository $repository;


    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }
    public function registerProfile(Profile $profile, string $password): Profile
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->repository->register($profile, $password);
        $id = $this->repository->getProfileByName($profile->getName())['ID'];
        $_SESSION['AuthId'] = $id;
        return $profile;
    }

    public function loginProfile(string $name, string $password): array
    {
        $profileRow = $this->repository->getProfileByName($name);
        if (password_verify($password, $profileRow['Password'])) {
            $_SESSION['AuthId'] = $profileRow['ID'];
            $profile = $this->setupProfile($_SESSION['AuthId']);
            return [true, $profile];
        } else {
            return [false, NULL];
        }
    }

    public function getProfile(string $ID): Profile
    {
        $profileRow = $this->repository->getProfileById($ID);
        $profile = new Profile($profileRow['Name'], $profileRow['Gender'], intval($profileRow['Age']));
        $profile->setID($ID);

        return $profile;
    }

    public function setupProfile(string $userId): Profile
    {
        $profile = $this->getProfile($userId);
        $userId = $profile->getID();
        $this->getImages($profile);
        $allLikeIds = $this->repository->getProfileLikesIDs($userId);

        $collection = $this->getLikes($allLikeIds);
        $profile->addLikes($collection);
        return $profile;
    }

    public function getLikes(array $likeIds)
    {
        $collection = new UserCollection;
        for ($i=0; $i<count($likeIds); $i++) 
        {
            $likedProfiles = $this->getLikedProfiles($likeIds[$i]);
            $collection->add($likedProfiles);
        }
        return $collection;
    }

    public function getLikedProfiles(string $userId): Profile
    {
        $likedProfile = $this->getProfile($userId);
        $likedUserId = $likedProfile->getID();
        $this->getImages($likedProfile);
        $allUserLikeIds = $this->repository->getProfileLikesIDs($likedUserId);
        $likedUsersCollection = new UserCollection;
        
        if (count($allUserLikeIds) > 0) {

            foreach ($allUserLikeIds as $likedID) {
                $secondarylikedProfile = $this->getProfile($likedID);
                $likedUsersCollection->add($secondarylikedProfile);
            }
            $likedProfile->addLikes($likedUsersCollection);
        } else {
            return $likedProfile;
        }
        return $likedProfile;
    }

    public function getImages(Profile $profile) 
    {
        $userId = $profile->getID();
        $imageAdresses = $this->repository->getProfileImages($userId);
        $profile->addImages($imageAdresses);
    }

    public function getAllPotentialProfiles(Profile $profile): UserCollection
    {
        $likesCollection = $profile->getLikes();
        $likesArray = $likesCollection->getUserList();
        $idArray = [];
        foreach ($likesArray as $likedProfile)
        {
            $idArray[] = $likedProfile->getID();
        }

        $likesArray = $likesCollection->getUserList();
        $rowNumber = $this->repository->getClientTableLength();

        $collection = new UserCollection; 
        for ($i=1; $i<=$rowNumber; $i++) {
            if (!in_array($i, $idArray) && $profile->getID() != $i) {
                $unlikedUserProfile = $this->setupProfile($i);
                $collection->add($unlikedUserProfile);
            }
        }
        return $collection; // RETURNS COLLECTION OF ALL POTENTIAL PROFILES TO LIKE / DISLIKE
    }

    public function showProfiles(UserCollection $collection)
    {
        $firstProfile = $collection->getUserList()[0];
        return $firstProfile;
    }
    public function displayMatch(Profile $activeProfile, Profile $likedProfile)
    {
        return [$activeProfile, $likedProfile];
    }

    public function rate(array $data)
    {

        // GO THROUGH CREATION OF BOTH PROFILES AGAIN ( THE setupProfile METHOD, BUT SO IT ACTUALLY WORKS)
        $activeProfileID = $_SESSION['AuthId'];
        $activeProfile = $this->setupProfile($activeProfileID);
        $suggestedLikesCollection = $this->getAllPotentialProfiles($activeProfile);
        $activeProfile->setSuggestedLikes($suggestedLikesCollection);
        $collection = $activeProfile->getLikes();

        $rating = $data[0];
        $likedProfileID = $data[1];

        if ($rating == 'like') {
            $likedProfile = $this->getLikedProfiles($likedProfileID);
            $collection->add($likedProfile);
            $activeProfile->addLikes($collection);
            $this->repository->addLikeToProfile($activeProfile, $likedProfile);

            $allLikeIds = $this->repository->getProfileLikesIDs($likedProfile->getID());

            $likeCollection = $this->getLikes($allLikeIds);

            if (count($likeCollection->getUserList()) < 1) {
                foreach ($likeCollection->getUserList() as $object)
                {
                    if ($object->getID() == $activeProfileID)
                    {
                        return ['match', $activeProfile, $likedProfile];
                    }
                }
            } else {
                return ['', $activeProfile, $likedProfile];
            }

        } else {
            $_SESSION['UnlikedIds'][] = $data[1];
            
            // DELETE FROM COLLECTION

            /*
            
            $_SESSION['UnlikedIds'][] = $unlikedId;
            
            if (!in_array[$_SESSION['unlikedIds']])
            {
                $showLikes
            }

            */
        }
    }
}

?>