<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController {
    public function login() {
        $data = $this->getJsonData();

        if (!isset($data->username) || !isset($data->password) || empty(trim($data->username)) || empty(trim($data->password))) {
            $this->sendResponse(['error' => 'Please provide username and password'], 400);
            return;
        }

        $username = htmlspecialchars(strip_tags($data->username));
        $password = $data->password;

        $user = new User();
        $user_data = $user->getByUsername($username);

        if ($user_data && password_verify($password, $user_data['password_hash'])) {
            session_start();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['role'] = $user_data['role'];

            $this->sendResponse(['message' => 'Login successful', 'user' => $user_data]);
        } else {
            $this->sendResponse(['error' => 'Invalid credentials'], 401);
        }
    }

    public function register() {
        $data = $this->getJsonData();

        if (!isset($data->username) || !isset($data->password) || !isset($data->role) || empty(trim($data->username)) || empty(trim($data->password))) {
            $this->sendResponse(['error' => 'Please provide username, password, and role'], 400);
            return;
        }

        $username = htmlspecialchars(strip_tags($data->username));
        $password = $data->password;
        $role = htmlspecialchars(strip_tags($data->role));

        $user = new User();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($user->create($username, $password_hash, $role)) {
            $this->sendResponse(['message' => 'User created successfully'], 201);
        } else {
            $this->sendResponse(['error' => 'Failed to create user'], 500);
        }
    }
}
