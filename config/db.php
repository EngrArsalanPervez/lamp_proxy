<?php

class Database
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = 'N3tw0rk@ieup';
    private string $dbname = 'proxy';
    private $conn;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("DB Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function prepare($sql)
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("DB Prepare failed: " . $this->conn->error);
        }
        return $stmt;
    }

    public function close(): void
    {
        $this->conn->close();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function user_exists($email): array
    {
        $stmt = $this->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $check = false;
                $msg = "Email already registered";
            } else {
                $check = true;
                $msg = "Email can be used";
            }
        } else {
            $check = false;
            $msg = $stmt->error;
        }

        $stmt->close();
        return array(
            'check' => $check,
            'msg' => $msg,
        );
    }

    public function register_user($full_name, $email, $password): array
    {
        $return = $this->user_exists($email);
        if ($return['check'] === true) {
            $stmt = $this->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $full_name, $email, $password);
            if ($stmt->execute()) {
                $check = true;
                $msg = "Registration Successful";
            } else {
                $check = false;
                $msg = $stmt->error;
            }
            $stmt->close();
        } else {
            $check = false;
            $msg = $return['msg'];
        }

        return array(
            'check' => $check,
            'msg' => $msg,
        );
    }

    public function login_user($email, $password): array
    {
        $full_name = '';
        $id = '';

        $stmt = $this->prepare("SELECT id,full_name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $storedHash = '';

                $stmt->bind_result($id, $full_name, $storedHash);
                $stmt->fetch();
                if (password_verify($password, $storedHash)) {
                    $check = true;
                    $msg = "Login Successful";
                } else {
                    $check = false;
                    $msg = "Invalid Email and Password";
                }
            } else {
                $check = false;
                $msg = "Invalid Email and Password";
            }
        } else {
            $check = false;
            $msg = $stmt->error;
        }

        $stmt->close();
        return array(
            'check' => $check,
            'msg' => $msg,
            'id' => $id,
            'full_name' => $full_name,
        );
    }

    public function login_with_token(): array
    {
        $token = $_COOKIE['remember_me'];
        $tokenHash = hash('sha256', $token);

        $stmt = $this->prepare('SELECT user_id FROM user_tokens WHERE token_hash = ? AND expires_at > NOW()');
        $stmt->bind_param('s', $tokenHash);
        $stmt->execute();
        $result = $stmt->get_result();
        $userToken = $result->fetch_assoc();

        $id = '';
        $full_name = '';
        $email = '';

        if ($userToken) {
            $stmt = $this->prepare("SELECT id, full_name, email FROM users WHERE id = ?");
            $stmt->bind_param('s', $userToken['user_id']);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $full_name, $email);
                    $stmt->fetch();
                }
            }
            $stmt->close();
        } else {
            setcookie('remember_me', '', time() - 3600, "/", "", true, true); // Expire the cookie
        }

        $session = array(
            'id' => $id,
            'full_name' => $full_name,
            'email' => $email,
            'logged_in' => true,
        );
        save_sessions_var($session);
        return $session;
    }

    function remember_me($session): void
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days

        $stmt = $this->prepare('INSERT INTO user_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $session['id'], $tokenHash, $expiresAt);
        $stmt->execute();

        setcookie('remember_me', $token, time() + (86400 * 30), "/", "", true, true); // 30 days, HttpOnly, Secure
    }

    function forget_me($session): void
    {
        // Get the token from the cookie
        $token = $_COOKIE['remember_me'];
        $tokenHash = hash('sha256', $token);

        // Delete the token from the database
        $stmt = $this->prepare('DELETE FROM user_tokens WHERE user_id = ? AND token_hash = ?');
        $stmt->bind_param('is', $session['id'], $tokenHash);
        $stmt->execute();

        // Clear the remember me cookie
        setcookie('remember_me', '', time() - 3600, "/", "", true, true); // Expire the cookie
    }
}

$db = new Database();
