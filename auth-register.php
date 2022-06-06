<?php 
    $articleDAO = require_once'./database/database.php';

    const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
    const ERROR_TOO_SHORT = 'Ce champ est trop court';
    const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe est trop court';
    const ERROR_EMAIL_INAVALID = 'Email invalide';
    const ERROR_PASSWORD_MISMATCH = 'Les mots de passe ne correspondent pas';

    $errors = [
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = filter_input_array(INPUT_POST,[

            'firstname'=> FILTER_SANITIZE_SPECIAL_CHARS,
            'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
            'password'=> '',
            'confirm_password'=> '' 
        ]);

        $firstname = $input['firstname'] ?? '';
        $lastname = $input['lastname'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $confirm_password = $input['confirm_password'] ?? '';

        if(!$firstname) {
            $errors['firstname'] = ERROR_REQUIRED;
        }else if(mb_strlen($firstname) < 2) {
            $errors['firstname'] = ERROR_TOO_SHORT;
        }

        if(!$lastname) {
            $errors['lastname'] = ERROR_REQUIRED;
        }else if(mb_strlen($lastname) < 2) {
            $errors['lastname'] = ERROR_TOO_SHORT;
        }

        if(!$email){
            $errors['email'] = ERROR_REQUIRED;
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ERROR_EMAIL_INAVALID;
        }

        if(!$password) {
            $errors['password'] = ERROR_REQUIRED;
        }else if(mb_strlen($password) < 6) {
            $errors['password'] = ERROR_PASSWORD_TOO_SHORT;
        }

        if(!$confirm_password) {
            $errors['confirm_password'] = ERROR_REQUIRED;
        } else if($confirm_password !== $password) {
            $errors['confirm_password'] = ERROR_PASSWORD_MISMATCH;
        }

        
        if(empty(array_filter($errors, fn ($e) => $e !== ''))) {

            $statement = $pdo->prepare(
                'INSERT INTO user VALUES (DEFAULT, :firstname, :lastname, :email, :password)'
            );
            $hashPassword = password_hash($password, PASSWORD_ARGON2I);

            $statement->bindValue(':firstname', $firstname);
            $statement->bindValue(':lastname', $lastname);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $hashPassword);
            $statement->execute();

            header ('Location: /');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'?>
    <link rel="stylesheet" href="public/css/auth-register.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
                <div class="block p-20 form-container">
                    <h1>Inscription</h1>
                        <form action="/auth-register.php" method="POST">
                            <div class="form-control">
                                    <label for="firstname">Prénom</label>
                                    <input type="text" name="firstname" id="firstname" value="<?= $firstname?? '' ?>">
                                    <?php if($errors['firstname']) : ?>
                                    <p class="text-danger"><?= $errors['firstname'] ?></p> 
                                    <?php endif ?>
                                </div>
                                <div class="form-control">
                                    <label for="lastname">Nom</label>
                                    <input type="text" name="lastname" id="lastname" value="<?= $lastname?? '' ?>">
                                    <?php if($errors['lastname']) : ?>
                                    <p class="text-danger"><?= $errors['lastname'] ?></p> 
                                    <?php endif ?>
                                </div>
                                <div class="form-control">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" value="<?= $email?? '' ?>">
                                    <?php if($errors['email']) : ?>
                                    <p class="text-danger"><?= $errors['email'] ?></p> 
                                    <?php endif ?>
                                </div>
                                <div class="form-control">
                                    <label for="password">Mot de passe</label>
                                    <input type="text" name="password" id="password" value="<?= $password?? '' ?>">
                                    <?php if($errors['password']) : ?>
                                    <p class="text-danger"><?= $errors['password'] ?></p> 
                                    <?php endif ?>
                                </div>
                                <div class="form-control">
                                    <label for="confirm_password">Confirmation de mot de passe</label>
                                    <input type="text" name="confirm_password" id="confirm_password" value="<?= $confirm_password?? '' ?>">
                                    <?php if($errors['confirm_password']) : ?>
                                    <p class="text-danger"><?= $errors['confirm_password'] ?></p> 
                                    <?php endif ?>
                                </div>
                            <div class="form-action">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit">Créer</button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once'includes/footer.php'?>
    </div>    
</body>
</html>