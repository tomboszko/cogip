<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\UserModel;
use PDO;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function getAllUsers()
    {
        $this->userModel->getAllUsers();
    }

    public function getUser($id)
    {
        $this->userModel->getUser($id);
    }

    public function getUsersLastFive()
    {
        $this->userModel->getUsersLastFive();
    }

    public function createUser($data)
    {
        $this->userModel->createUser($data);
    }

    public function updateUser($data)
    {
        $this->userModel->updateUser($data);
    }

    public function deleteUser($id)
    {
        $this->userModel->deleteUser($id);
    }
}