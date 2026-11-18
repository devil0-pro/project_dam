<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payroll_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 1. FETCH DATA FOR HTML TABLES (GET REQUESTS)
if (isset($_GET['fetch'])) {
    header('Content-Type: application/json');
    $table = $_GET['fetch'];
    $allowed_tables = ['employees', 'departments', 'salaries', 'payroll'];

    if (in_array($table, $allowed_tables)) {
        $result = mysqli_query($conn, "SELECT * FROM $table");
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode([]);
    }
    exit();
}

// 2. SAVE FORM DATA INTO DATABASE (POST REQUESTS)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // --- ADD EMPLOYEE ---
    if ($action == 'add_employee') {
        $id = mysqli_real_escape_string($conn, $_POST['empid']);
        $name = mysqli_real_escape_string($conn, $_POST['empname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $dept = mysqli_real_escape_string($conn, $_POST['department']);
        $joining = mysqli_real_escape_string($conn, $_POST['joiningdate']);

        $sql = "INSERT INTO employees (id, name, email, phone, department, joining_date) 
                VALUES ('$id', '$name', '$email', '$phone', '$dept', '$joining')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Employee Saved to Database!'); window.location.href='employees.html';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // --- ADD DEPARTMENT ---
    elseif ($action == 'add_department') {
        $dept_id = mysqli_real_escape_string($conn, $_POST['dept_id']);
        $dept_name = mysqli_real_escape_string($conn, $_POST['dept_name']);
        $manager = mysqli_real_escape_string($conn, $_POST['manager']);

        $sql = "INSERT INTO departments (id, name, manager) 
                VALUES ('$dept_id', '$dept_name', '$manager')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Department Saved to Database!'); window.location.href='departments.html';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // --- ADD SALARY ---
    elseif ($action == 'add_salary') {
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $basic = mysqli_real_escape_string($conn, $_POST['basic']);
        $allowance = mysqli_real_escape_string($conn, $_POST['allowance']);
        $bonus = mysqli_real_escape_string($conn, $_POST['bonus']);
        $deduction = mysqli_real_escape_string($conn, $_POST['deduction']);
        $net = ($basic + $allowance + $bonus) - $deduction;

        $sql = "INSERT INTO salaries (emp_id, basic, allowance, bonus, deduction, net_salary) 
                VALUES ('$emp_id', '$basic', '$allowance', '$bonus', '$deduction', '$net')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Salary Record Saved to Database!'); window.location.href='salaries.html';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // --- ADD PAYROLL ---
    elseif ($action == 'add_payroll') {
        $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
        $month = mysqli_real_escape_string($conn, $_POST['month']);
        $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $sql = "INSERT INTO payroll (emp_id, month, payment_date, status) 
                VALUES ('$emp_id', '$month', '$payment_date', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Payroll Record Saved to Database!'); window.location.href='payroll.html';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    exit();
}

// Default message if opened directly
echo "Backend Master File is Active & Connected to Database!";
mysqli_close($conn);
?>