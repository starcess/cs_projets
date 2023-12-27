<?php

$path = "$_SERVER[DOCUMENT_ROOT]/RichRicasso";

require_once "$path/models/db_manager.php";
require_once "$path/controllers/sessions.php";

function printt($element) {
    if (is_array($element)) {
        foreach ($element as $subelem => $value) {
            print("<br><b>$subelem:</b> $value");
        }
    } else {
        print("<br>$element");
    }
    print("<br>");
}

function printSection($title, $value) {
    if (!empty($title)) {
        printt("<h4>$title</h4>");
    }

    print("<b>RESULTS:</b>");
    printt($value);
    print("<hr>");
}

function testCreate($controller) {
    $user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

    $users = $controller->getUsersController();
    $users->createUser($user);

    $fetchedUser = $users->getUserByEmail($user['email']);

    printSection("TEST CREATE", $fetchedUser);
}

function testDelete($controller) {
    $user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

    $users = $controller->getUsersController();
    $users->createUser($user);

    $fetchedUser = $users->getUserByEmail($user['email']);
    $id = $fetchedUser['id'];
    $users->deleteUser($fetchedUser['id']);

    printSection("TEST DELETE", $users->getUserById($id));
}

function testLogin($controller) {
    $user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

    $users = $controller->getUsersController();
    $users->createUser($user);

    $fetchedUser = $users->getUserByEmail($user['email']);
    $users->login($user['email'], $user['password']);

    printSection("TEST LOGIN (if auth is true)", $users->isAuthenticated());
}

function testLogout($controller) {
    $user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

    $users = $controller->getUsersController();
    $users->createUser($user);

    $fetchedUser = $users->getUserByEmail($user['email']);
    $users->login($user['email'], $user['password']);

    $users->logout();

    printSection("TEST LOGOUT (if auth is false/null)", $users->isAuthenticated());
}


function testUpdate($controller) {
    $user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

    $users = $controller->getUsersController();
    $users->createUser($user);
    $users->login($user['email'], $user['password']);

    $fetchedUser = $users->getUserByEmail($user['email']);
    $id = $fetchedUser['id'];

    $updateData = ['name' => 'test2'];
    $users->updateUser($id, $updateData);

    printSection("TEST UPATE", $users->getUserById($id, ['lastname', 'name']));
}

$controller = SessionController::getInstance();
$users = $controller->getUsersController();
$newsletters = $controller->getNewslettersController();

testCreate($controller);
testDelete($controller);

testLogin($controller);
testUpdate($controller);
testLogout($controller);

$user = ["email" => "test@email", "name" => "testname", "lastname" => "testlastname", "password" => "123"];

$newsletters->createSubscriber($user);
$sub = $newsletters->getSubscriberByEmail($user['email']);
print(!empty($sub));

// $tables = ["user" => 1, "newsletter" => "caca","product" => "123"];
// $tables2 = ["user", "newsletter","product"];
// $field = 'user';

// $filteredColumns = array_filter($tables2, function($column) {
//      return $column != 'user';
// });

// // print($columnNames);
// print_r($filteredColumns);

// $id = ["id" => 123];

// print_r(array_keys($id));

// $id2 = ["id2"];

// print_r(array_values($id2));

// print_r($users->getColumns());

// $pdo = DBManager::getInstance('zi', '123')->getPDO();
// $sql = "SELECT * FROM user WHERE email = ?";

// $stmt = $pdo->prepare($sql);
// $stmt->execute(['a@a']);

// $result = $stmt->fetch(PDO::FETCH_ASSOC);

// print_r($result);

// print_r($users->getAll());