<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "absence_db";

require_once 'functions.php';

// ایجاد اتصال 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تنظیم charset به utf8
$conn->set_charset("utf8");

if (isset($_GET['show_all'])) {
    $tables = ["class701_absences", "class702_absences", "class801_absences", "class802_absences", "class901_absences", "class902_absences"];
    $output = "";
    foreach ($tables as $table) {
        $result = $conn->query("SELECT name, class, time, date FROM $table");
        $output .= "<h3>" . str_replace("class", "کلاس ", $table) . "</h3>";
        $output .= "<ul>";
        while ($row = $result->fetch_assoc()) {
            list($gy, $gm, $gd) = explode('-', $row['date']);
            $shamsi_date = gregorian_to_jalali($gy, $gm, $gd, '/');
            $class_name = str_replace("class", "کلاس ", $row['class']);
            $output .= "<li>" . $row['name'] . " - " . $class_name . " - " . $row['time'] . " - " . $shamsi_date . "</li>";
        }
        $output .= "</ul>";
    }
    echo $output;
} else if (isset($_GET['name']) && isset($_GET['class'])) {
    $name = $_GET['name'];
    $class = $_GET['class'];
    $table = $class . "_absences";
    $sql = "INSERT INTO $table (name, class, time, date) VALUES ('$name', '$class', CURTIME(), CURDATE())";

    if ($conn->query($sql) === TRUE) {
        // بازیابی لیست غایبین
        $result = $conn->query("SELECT name, class, time, date FROM $table");
        $output = "<ul>";
        while ($row = $result->fetch_assoc()) {
            list($gy, $gm, $gd) = explode('-', $row['date']);
            $shamsi_date = gregorian_to_jalali($gy, $gm, $gd, '/');
            $class_name = str_replace("class", "کلاس ", $row['class']);
            $output .= "<li>" . $row['name'] . " - " . $class_name . " - " . $row['time'] . " - " . $shamsi_date . "</li>";
        }
        $output .= "</ul>";
        echo $output;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>