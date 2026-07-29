<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use PDO;

final class LoginController extends Controller
{
    private PDO $pdo;
    private Session $session;

    public function __construct(Database $database)
    {
        $this->pdo = $database->pdo();
        $this->session = new Session();
    }

    public function index(Request $request, Response $response): void
    {
        $this->view('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function login(Request $request, Response $response): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {

            $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Username and password are required.'
            ]);

            return;
        }

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE username = ?
              AND active = 1
            LIMIT 1
        ");

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid username or password.'
            ]);

            return;
        }

        if (!password_verify($password, $user['password'])) {

            $this->view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid username or password.'
            ]);

            return;
        }

        $this->session->regenerate();

        $this->session->set('user', [
            'id'       => $user['id'],
            'role_id'  => $user['role_id'],
            'username' => $user['username'],
            'name'     => trim(
                ($user['first_name'] ?? '') . ' ' .
                ($user['last_name'] ?? '')
            )
        ]);

        header('Location: /');

        exit;
    }

    public function logout(Request $request, Response $response): void
    {
        $this->session->destroy();

        header('Location: /login');

        exit;
    }
}

