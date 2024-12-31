<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "absence_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تنظیم charset به utf8
$conn->set_charset("utf8");

if (isset($_GET['password'])) {
    $password = $_GET['password'];
    $correct_password = "1051602408";

    if ($password === $correct_password) {
        $tables = ["class701_absences", "class702_absences", "class801_absences", "class802_absences", "class901_absences", "class902_absences"];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table";
            $conn->query($sql);
        }
        echo "غیبت‌ها با موفقیت پاک شدند.";
    } else {
        echo "رمز عبور اشتباه است.";
    }
}

$conn->close();
?>
