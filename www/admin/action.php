<?php
include "../include/functions.php";
$conn = db_conn();
$action = $_POST['submit'];

switch($action) {
    case 'addemp':
        $fname = $_POST['fname'];
        $idno = $_POST['idno'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $mail = $_POST['mail'];
        $dob = $_POST['dob'];
        $username = $_POST['username'];
        $job = $_POST['job'];
        $ac_no = $_POST['ac_no'];
        $kra = $_POST['kra'];
        $nhif = $_POST['nhif'];
        $nssf = $_POST['nssf'];
        $county = $_POST['county'];
        $bunge = $_POST['bunge'];
        $ward = $_POST['ward'];
        $areacode = $_POST['areacode'];
        $kin = $_POST['kin'];
        $kphone = $_POST['kinphone'];
        $kinrship = $_POST['kinrship'];
        $photo = $_FILES['photo']['temp_name'];
        $pd = 12345;
        $type = $_POST['position'];

        $sql = "INSERT INTO employees (photo, name, gender, idno, dob, contact, email, username, password, position, next_of_kin, kin_contact, kinrship, krapin, nssf, nhif, account_no, status)
              VALUES ('$idno', '$fname', '$gender', '$idno', '$dob', '$phone', '$mail', '$username', '$pd', '$type', '$kin', '$kphone', '$kinrship', '$kra', '$nssf', '$nhif', '$ac_no', 'Inactive')";

        $ret = $conn->exec($sql);
        if(!$ret){
            echo $conn->lastErrorMsg();
        } 
        else{
            $conn = null;
            echo "
            <script>alert('Teacher added successfully!')</script>
            <script>window.location = 'index.php?emp'</script>
            ";
        }
    break;
    
    case 'addsubject':
        $name = $_POST['subjectname'];
        $class = $_POST['class'];
        $teacher = $_POST['teacher'];
        $category = $_POST['category'];

        $sql = "INSERT INTO subject (name, classid, teacherid, category)
                VALUES ('$name', '$class', '$teacher', '$category')";

        $ret = $conn->exec($sql);
        if(!$ret){
            echo $conn->lastErrorMsg();
        } 
        else{
            $conn = null;
            echo "
            <script>alert('Subject added successfully!')</script>
            <script>window.location = 'index.php?subject'</script>
            ";
        }
    break;
    
    case 'addclass':
        $classname = $_POST['classname'];
        $classteacher = $_POST['classteacher'];
        $room = $_POST['room'];
        $date_added = $_POST['date_added'];
        $fees = $_POST['fees'];
        $status = $_POST['status'];

        $sql = "INSERT INTO class (classname, classteacher, fees, date_added, room_no, status)
              VALUES ('$classname', '$classteacher','$fees', '$date_added','$room', '$status')";

        $ret = $conn->exec($sql);
        if(!$ret){
            echo $conn->lastErrorMsg();
        } 
        else{
            $conn = null;
            echo "
            <script>alert('Class added successfully!')</script>
            <script>window.location = 'index.php?class'</script>
            ";
        }
    break;

    case 'updateclass':
        // Retrieve updated class details from the form
        $classid = $_POST['classid'];
        $classname = $_POST['Uclassname'];
        $classteacher = $_POST['Uclassteacher'];
        $room = $_POST['Uroom'];
        $date_added = $_POST['Udate_added'];
        $fees = $_POST['Ufees'];
        $status = $_POST['Ustatus'];

        // Check if class ID exists to prevent invalid updates
        if ($classid > 0) {
            // SQL query to update class details in the database using a prepared statement
            $sql = "UPDATE class SET 
                        classname = :classname, 
                        classteacher = :classteacher, 
                        room_no = :room_no, 
                        date_added = :date_added, 
                        fees = :fees, 
                        status = :status 
                    WHERE classid = :classid";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':classname', $classname, SQLITE3_TEXT);
            $stmt->bindValue(':classteacher', $classteacher, SQLITE3_TEXT);
            $stmt->bindValue(':room_no', $room, SQLITE3_TEXT);
            $stmt->bindValue(':date_added', $date_added, SQLITE3_TEXT);
            $stmt->bindValue(':fees', $fees, SQLITE3_FLOAT);
            $stmt->bindValue(':status', $status, SQLITE3_TEXT);
            $stmt->bindValue(':classid', $classid, SQLITE3_INTEGER);

            // Execute the query
            $ret = $stmt->execute();

            if (!$ret) {
                echo $conn->lastErrorMsg(); // If an error occurs, display the error message
            } else {
                // If successful, redirect and display a success message
                echo "
                <script>
                    alert('Class updated successfully!');
                    window.location = 'index.php?class';
                </script>
                ";
            }
        } else {
            echo "<script>alert('Invalid class ID.');</script>";
        }
    break;

    case 'addexam':
        $DateAdded = $_POST['DateAdded'];
        $examname = $_POST['examname'];
        $status = $_POST['status'];
        $year = $_POST['year'];

        $sql = "INSERT INTO exam (examname, year, date_added, status)
              VALUES ('$examname', '$year','$date_added', '$status')";

        $ret = $conn->exec($sql);
        if(!$ret){
            echo $conn->lastErrorMsg();
        }
        else{
            $conn = null;
            echo "
            <script>alert('Exam created successfully!')</script>
            <script>window.location = 'index.php?exam'</script>
            ";
        }
    break;
    
    case 'Change':
        $email =$_POST['email'];
        $new_password = md5($_POST['new_password']);
        $sql = $conn->query("UPDATE tutors SET password = '$new_password' WHERE email = '$email' ");
        $conn = null;
        echo "
        <script>alert('Your password has been changed successfully!')</script>
        <script>window.location = 'index.php'</script>
        ";
    break;

    case "edit_user":
        $uid = str_replace("'", "\'", $_POST['userid']);
        $status = $_POST['ustatus'];
        $sql = $conn->query("UPDATE employees SET status = '$status' WHERE username = '$uid'");
        $conn = null;
        echo "
        <script>alert('Your status has been changed successfully!')</script>
        <script>window.location = 'index.php?emp'</script>
        ";
    break;

} // End switch
?>
