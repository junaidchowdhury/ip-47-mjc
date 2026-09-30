<?php








include 'connect.php';
session_start();

if (isset($_POST['signUp'])) {
    $Name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

   
    $full_name = "11";
    $address = "11";
    $phone = "11";
    $age = 11;
    $favorite_place = "11";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid Email Format!");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = $conn->prepare("
            INSERT INTO users (Name, email, password, full_name, address, phone, age, favorite_place) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insertQuery->bind_param("ssssssis", $Name, $email, $hashedPassword, $full_name, $address, $phone, $age, $favorite_place);

        if ($insertQuery->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}




if (isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid Email Format!");
    }


    $defaultEmail = "mdazim@gmail.com";
    $defaultPassword = "1234";

    if ($email === $defaultEmail && $password === $defaultPassword) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    }

    $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        

        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role']; 

            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "Incorrect Email or Password!";
        }
    } else {
        echo "User Not Found!";
    }
}















?>
