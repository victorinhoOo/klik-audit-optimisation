<?php

use Model\Exceptions\BadCaptchaResponse;
use Model\Exceptions\BadLoginOrPasswordException;

require_once("Model/Managers/UserManager.php");
require_once("Views/View.php");
require_once("Model/Logic/User.php");
require_once("Model/Logic/Captcha.php");
require_once("Model/Exceptions/BadLoginOrPasswordException.php");
require_once("Model/Exceptions/BadCaptchaResponse.php");

class UserController
{
    private UserManager $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    
    }

    public function displayConnexion(Exception $e = null)
    {
        $view = new View("Login");
        $params = ["title" => "Authentification", "captcha" => new Captcha()];
        if (isset($e)) {
            $params["exception"] = $e->getMessage();
        }
        $view->generate($params);
    }

    public function verifyConnexionAttempt(string $username, string $password, string $captchaRep): bool
    {
        //Récupération du captcha
        $captcha = new Captcha();
        if ($captcha->validate($captchaRep)) {
            $response = $this->userManager->verifyUserCredentials($username, $password);
            if (!$response) {
                throw new BadLoginOrPasswordException();
            }
        } else {
            throw new BadCaptchaResponse();
        }
        return $response;
    }

    public function getUserByUsername(string $username): User
    {
        $data = $this->userManager->getUserByUsername($username);
        $data = $data[0];
        $formattedData = [
            "id" => $data["idUsers"],
            "username" => $data["uidUsers"],
            "email" => $data["emailUsers"],
            "password" => $data["pwdUsers"],
            "firstName" => $data["f_name"],
            "lastName" => $data["l_name"],
            "level" => $data["userLevel"],
            "headline" => $data["headline"],
            "profilePicture" => $data["userImg"]
        ];
        $user = new User($formattedData);
        return $user;
    }

    public function disconnect(): void
    {
        session_unset();
        session_destroy();
        header("location: index.php");
    }
}
?>
