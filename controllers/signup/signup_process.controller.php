<?php
session_start();
require "../../database/database.php";
require "../../models/signup.medel.php";
function secureData($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
};
$error = [

    "password" => "",
    "name" => "",
    "email" => ""

];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $image = $_FILES['image'];
    $password = $_POST["password"];
    $passwordEncript = password_hash($password, PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $email = $_POST['email'];

    // // Image upload
    if (!empty($name) && (!empty($email)) && (!empty($password)) && !empty($image)) {
        $directory = "../../assets/images/profiles/";
        $target_file = $directory . '.' . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $checkImageSize = getimagesize($_FILES["image"]["tmp_name"]);
        if ($checkImageSize) {
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $_SESSION['error'] = "Wrong Image extension!";
                header('Location: /user-signup');
            } else {

                $imageExtension = explode('.', $target_file)[6];
                $newFileName = uniqid();
                $nameInDirectory = $directory . $newFileName . '.' . $imageExtension;
                $nameInDB = $newFileName . '.' . $imageExtension;
                move_uploaded_file($_FILES["image"]["tmp_name"], $nameInDirectory);
                $user = accountExists($email);
                if (count($user) == 0) {
                    createAccount($name, $email, $passwordEncript, $nameInDB);
                    $_SESSION['user'] = ['name'=>$name, 'email'=>$email, 'image'=>$nameInDB];
                    header('Location: /user-signin');
                    $_SESSION['success'] = "Account successfully created";
                } else {
                    $_SESSION['exist'] = "Account already exists";
                    header('Location: /user-signup');
                }
            }
        }
    }
    if (!empty($name) && (!empty($email)) && (!empty($password))) {
        $_SESSION['error'] = $error;
        createAccount($name, $email, $passwordEncript, $image);
        header("Location:/home");
    } else {
        header("Location:/user-signup");
        $error['name'] = "It's require!";
        $_SESSION['error'] = $error;
        exit;
    }
    if (!preg_match('/^(?=.*[!@#$])[a-zA-Z0-9!@#$]{8,}+$/', secureData($password))) {
        $error['password'] = "Password is not secure!";
        $_SESSION['error'] = $error;
        header("Location:/user-signup");
        exit;
    }
    if (empty($error['password'])) {
        createAccount($name, $email, $passwordEncript, $image);
        header("Location:/home");
        $_SESSION['error'] = $error;
        exit;
    } else {
        header("Location:/user-signup");
        $_SESSION['error'] = $error;
        exit;
    };
} else {
    // Redirect to the login page if the form is not submitted
    header("Location: /user-signup");
    exit;
}
