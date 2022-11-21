<?php

namespace Utils;

use Exceptions\DataNotFoundException;
use Repositories\UserRepository;

abstract class AuthUtils
{
    public static function checkAuth(): bool
    {
        if (isset($_SESSION["user"])) {
            $user = $_SESSION["user"];
            //Check if the user is still on the database and reload the user object, if not, log out the user
            try {
                $user = UserRepository::getUserById($user->getId());
                $_SESSION["user"] = $user;
                return true;
            } catch (DataNotFoundException) {
                unset($_SESSION["user"]);
                return false;
            }
        }
        return false;
    }

}