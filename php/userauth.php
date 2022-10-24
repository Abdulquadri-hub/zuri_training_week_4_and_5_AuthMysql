<?php

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();
    $query = "select email from students where email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s',$email);
    if($stmt->execute()){
        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $row = $row[0];
        if ($row['email'] === $email) {
                    echo "<script> alert('Email Aleady Exists') </script>";
                    echo "<script> window.open('../forms/register.html','_self') </script>";
        }else{
            $query = "insert into students set full_names = ?, email = ?, password = ?, gender = ?, country = ?";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('sssss', $fullnames, $email, $password, $gender, $country);
                $check = $stmt->execute();
                if ($check) {
                    echo "<script> alert('User is successfully registered') </script>";
                    echo "<script> window.open('../forms/login.html') </script>";
                }
            }
        }
    }
    
    
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if email exist in the database
    $query = "select * from students where email = ? and password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss',$email,$password);
    if($stmt->execute()){
        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $row = $row[0];
        if ($row['email'] === $email && $row['password'] === $password) {
    //if it does, check if the password is the same with what is given
                session_start();
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_names'] = $row['full_names'];
    //if true then set user session for the user and redirect to the dasbboard
                echo "<script> alert('You Login successfully') </script>";
                echo "<script> window.open('../dashboard.php','_self') </script>";
        }else {
            echo "<script> window.open('../forms/login.html','_self') </script>";
            echo "<script> alert('Wrong login details') </script>";
        }
    }

}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    $query = "select * from students where email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s',$email);
    if($stmt->execute()){
        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $row = $row[0];
        if ($row['email'] === $email) {
    //if it does, replace the password with $password given
                $query = "update students set password = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s',$password);
                if($stmt->execute()){
                    echo "<script> alert('Password have been reset. You can now login') </script>";
                    echo "<script> window.open('../forms/login.html','_self') </script>";
                }
        }else {
            echo "<script> alert('User does not exist) </script>";
            
        }
    }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id){
    $conn = db();
     //delete user with the given id from the database
    $query = "delete from students where id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    echo "<script> alert('User was successfully deleted') </script>";
    echo "<script> window.open('action.php?all=','_self') </script>";
}
