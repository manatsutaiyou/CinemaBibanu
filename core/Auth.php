<?php
class Auth {

    public static function login($user) {
    $_SESSION['user'] = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'phone' => $user['phone'],
        'role'  => $user['role'] ?? 'user'
    ];
}

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function isAdmin() {
        return self::check() && $_SESSION['user']['role'] === 'admin';
    }

    public static function logout() {
        unset($_SESSION['user']);
    }
}
