<?php

//<======== create user account =======>
function createAccount(string $name, string $email, string $password, string $image) : bool
{
    global $connection;
    $role = "teacher";
    $statement = $connection->prepare("insert into users (name,email, password, role, image) values (:name, :email, :password, :role, :image)");
    $statement->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role,
        ':image' => $image
    ]);
    return $statement->rowCount() > 0;
}

//<======== check exist account =======>
function accountExists(string $email): array
{
    global $connection;
    $statement = $connection->prepare("select * from users where email = :email");
    $statement->execute([':email' => $email]);
    if ($statement->rowCount() > 0) {
        return $statement->fetch();
    } else {
        return [];
    }
}

// =========== get users by email ============
function getUserEmail(string $email)
{
    global $connection;
    $statement = $connection->prepare("select * from users where email = :email");
    $statement->execute([':email' => $email]);
    if ($statement->rowCount() > 0) {
        return $statement->fetch();
    } else {
        return [];
    }
}