<?php
namespace App\Controllers;

use App\Collections\UserCollection;
use App\Models\Profile;
use App\Services\ProfileService;
use App\Controllers\FileController;
use App\Views\View;

class ProfileController
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function home(): array
    {
        $view = new View;
        $profile = $this->service->setupProfile($_SESSION['AuthId']);
        $profileColl = $this->service->getAllPotentialProfiles($profile); // COLLECTION OF ALL NON-LIKED PROFILES
        $profile->setSuggestedLikes($profileColl);
        $page = $view->profilePage();
        return [$page, $profile];
    }

    public function login(): array
    {
        $password = $_POST['password'];
        $name = $_POST['username'];
        $result = $this->service->loginProfile($name, $password);

        return $this->preparePage($result);
    }
    
    public function register(): array
    {
        $view = new View;
        $page = $view->profilePage();
        $name = $_POST['username'];
        $password = $_POST['password'];
        $age = intval($_POST['age']);
        $gender = $_POST['sex'];
        $profile = new Profile($name, $gender, $age);
        $profile = $this->service->registerProfile($profile, $password);
        return [$page, $profile];
    }

    public function rateProfile(): array
    {
        $data = explode('/', $_POST['rate']);
        $raitingResult = $this->service->rate($data);
        
        if ($raitingResult[0] == 'match')
        {
            $activeProfile = $raitingResult[1]; 
            $likedProfile = $raitingResult[2];

            $view = new View;
            $page = $view->matchPage();
            return [$page, [$activeProfile, $likedProfile]];
        } else {
            $view = new View;
            $page = $view->profilePage();

            return $this->home();
        }
    }

    public function preparePage(array $result)
    {
        $view = new View;

        if ($result[0] == false) {
            return $result;
        } else {
            $profile = $result[1];
            $profile = $this->service->setupProfile($profile->getID());

            $profileColl = $this->service->getAllPotentialProfiles($profile); // COLLECTION OF ALL NON-LIKED PROFILES
            $profile->setSuggestedLikes($profileColl);
            $page = $view->profilePage();
            return [$page, $profile];
        }
    }
}
?>
