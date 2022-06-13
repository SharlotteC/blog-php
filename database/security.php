<?php

class AuthDAO {

    const SECRET_KEY = 'formation dwwm au top grace a rachid';

    private PDOStatement $statementRegistration;
    private PDOStatement $statementReadSession;
    private PDOStatement $statementReadUser;
    private PDOStatement $statementReadUserFromEmail;
    private PDOStatement $statementCreateSession;
    private PDOStatement $statementDeleteSession;



    public function __construct(private PDO $pdo)
    {
        $this->statementRegistration = $this->pdo->prepare(
            'INSERT INTO user (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)'     
        );
        $this->statementReadSession = $this->pdo->prepare('SELECT * FROM session WHERE id=:id');
        $this->statementReadUser = $this->pdo->prepare('SELECT * FROM user WHERE id=:id');
        $this->statementReadUserFromEmail = $this->pdo->prepare('SELECT * FROM user WHERE email=:email');
        $this->statementCreateSession = $this->pdo->prepare('INSERT INTO session VALUES (:sessionId, :userid)');
        $this->statementDeleteSession = $this->pdo->prepare('DELETE FROM session  WHERE id=:id');
    }

    function getUserFromEmail(string $email): array {
        $this->statementReadUserFromEmail->bindValue(':email', $email);
        $this->statementReadUserFromEmail->execute();
        return $this->statementReadUserFromEmail->fetch() ?? false;
    }

    function login(int $userId): void {
        $sessionId = bin2hex(random_bytes(32));

        $this->statementCreateSession->bindValue(':sessionId', $sessionId);
        $this->statementCreateSession->bindValue(':userid', $userId);
        $this->statementCreateSession->execute();

        $signature = hash_hmac('sha256', $sessionId, AuthDAO::SECRET_KEY);

        // créer notre cookie
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14 , '', '', false, true);
        setcookie('signature', $signature, time() + 60 * 60 * 24 * 14 , '', '', false, true);

    }

    function register(array $user) : void {

        $hashPassword = password_hash($user['password'], PASSWORD_ARGON2I);
        $this->statementRegistration->bindValue(':firstname', $user['firstname']);
        $this->statementRegistration->bindValue(':lastname', $user['lastname']);
        $this->statementRegistration->bindValue(':email', $user['email']);
        $this->statementRegistration->bindValue(':password', $hashPassword);
        $this->statementRegistration->execute();
    }

    function isLoggedIn(): array | false {
        $sessionId = $_COOKIE['session'] ?? '';
        $signature = $_COOKIE['signature'] ?? '';


        if($sessionId && $signature) {

            $hash =  hash_hmac('sha256', $sessionId, AuthDAO::SECRET_KEY);

            if(hash_equals($hash, $signature)){
                $this->statementReadSession->bindValue(':id', $sessionId);
                $this->statementReadSession->execute();
    
                $session = $this->statementReadSession->fetch();
    
            if($session) {
                $this->statementReadUser->bindValue(':id', $session['userid']);
                $this->statementReadUser->execute();
                $user = $this->statementReadUser->fetch();
                }
            }
        }
        return $user ?? false;
    }

    function logout(int $sessionId): void {   

        $this->statementDeleteSession->bindValue(':id', $sessionId);
        $this->statementDeleteSession->execute();

        setcookie('session', '', time() -1);
        setcookie('siganture', '', time() -1);

    }
}

return new AuthDAO($pdo);



// function isLoggedIn(){
//     /**
//      * @var PDO
//      */

//     global $pdo;
//     $sessionId = $_COOKIE['session'] ?? '';

//     if($sessionId) {
//         $statementSession = $pdo->prepare('SELECT * FROM session WHERE id=:id');
//         $statementSession->bindValue(':id', $sessionId);
//         $statementSession->execute();

//         $session = $statementSession->fetch();

//         if($session) {
//             $statementUser = $pdo->prepare('SELECT * FROM user WHERE id=:id');
//             $statementUser->bindValue(':id', $session['userid']);
//             $statementUser->execute();
//             $user = $statementUser->fetch();
//         }
//     }
//     return $user ?? false;
// }