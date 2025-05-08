<?php
include "functions.php";

session_start(); // Ensure session is started.

$db = db_conn();
$n = 10;

$action = isset($_POST['submit']) ? $_POST['submit'] : null;

switch ($action) {
    case "login":
        $username = validate($_POST['uname']);
        $password = md5($_POST['password']);
        $status = "Active";

        if (empty($username)) {
            header("Location: ../index.php?error=User Name is required");
            exit();
        } else if (empty($password)) {
            header("Location: ../index.php?error=Password is required");
            exit();
        }

        // Check if employees table is empty and create default admin if necessary.
        $install = "SELECT * FROM employees";
        $ret = $db->query($install);
        if (empty($ret->fetchArray(SQLITE3_ASSOC))) {
            $defaultUser = "INSERT INTO employees (idno, dob, gender, contact, email, username, password, position, name, status) 
                            VALUES ('1', '2000-01-01', 'male', '01', 'admin@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin', 'Active')";
            $db->exec($defaultUser);
        }

        // Check user credentials
        $stmt = $db->prepare("SELECT * FROM employees WHERE username = :username AND password = :password AND status = :status");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':status', $status);
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);
        if (!$row) {
            header("Location: ../index.php?error=Invalid username or password");
            exit();
        }

        // Log the user and redirect based on their position
        $_SESSION['id'] = $username;
        $logfile = $db->prepare("INSERT INTO logfiles (username, password, level) VALUES (:username, :password, :level)");
        $logfile->bindValue(':username', $username);
        $logfile->bindValue(':password', $password);
        $logfile->bindValue(':level', $row['position']);
        $logfile->execute();

        switch ($row['position']) {
            case 'Administrator':
                header("Location: ../admin/index.php?dashboard");
                exit();
            case 'Receptionist':
                header("Location: ../reception/index.php?dashboard");
                exit();
            case 'Teacher':
                header("Location: ../teachers/index.php?dashboard");
                exit();
            default:
                header("Location: ../index.php?error=Unauthorized access");
                exit();
        }
        break;

    case "register":
        // Registration logic
        $affino = $_POST['affino'];
        $cname = str_replace("'", "\'", $_POST['cname']);
        $phone = $_POST['phone'];
        $email = str_replace("'", "\'", $_POST['email']);
        $username = str_replace("'", "\'", $_POST['username']);
        $password = md5($_POST['password']);
        $status = "Inactive";
        $user_type = "Customer";

        $stmt = $db->prepare("SELECT * FROM customer WHERE email = :email OR phone = :phone");
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $result = $stmt->execute();

        if ($result->fetchArray(SQLITE3_ASSOC)) {
            echo "<script>alert('You already have an existing account')</script>
                  <script>window.location = '../index.php'</script>";
        } else {
            $stmt = $db->prepare("INSERT INTO customer (full_name, phone, email, username, password, user_type, status, affno) 
                                  VALUES (:cname, :phone, :email, :username, :password, :user_type, :status, :affino)");
            $stmt->bindValue(':cname', $cname);
            $stmt->bindValue(':phone', $phone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':user_type', $user_type);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':affino', $affino);
            $stmt->execute();

            echo "<script>window.location = '../index.php'</script>";
        }
        break;

    case "Change":
        // Password change logic
        $id = $_POST['userid'];
        $new_password = md5($_POST['new_password']);

        $stmt = $db->prepare("UPDATE employees SET password = :password WHERE username = :username");
        $stmt->bindValue(':password', $new_password);
        $stmt->bindValue(':username', $id);
        $stmt->execute();

        echo "<script>alert('Your password has been changed successfully!')</script>
              <script>window.location = '../index.php'</script>";
        session_destroy();
        break;

    case "reset":
        // Password reset logic
        $email = $_POST['myemail'] ?? null;
        $username = $_POST['fusername'] ?? null;

        if ($email && $username) {
            $stmt = $db->prepare("SELECT * FROM customer WHERE email = :email AND username = :username AND status = 'Active'");
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':username', $username);
            $result = $stmt->execute();

            if ($result->fetchArray(SQLITE3_ASSOC)) {
                $new_password = md5(substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8));
                $stmt = $db->prepare("UPDATE customer SET password = :password WHERE email = :email");
                $stmt->bindValue(':password', $new_password);
                $stmt->bindValue(':email', $email);
                $stmt->execute();

                echo "<script>alert('Password reset. Check your email for the new password.')</script>
                      <script>window.location = '../index.php'</script>";
            } else {
                echo "<script>alert('Invalid account. Please contact support.')</script>
                      <script>window.location = '../index.php'</script>";
            }
        }
        break;

    default:
        echo "Invalid operation";
        break;
}
