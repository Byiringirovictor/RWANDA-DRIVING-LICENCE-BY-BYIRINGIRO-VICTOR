<?php
include 'config.php';

$message = []; 
$num_rows = 10; 
$sql = mysqli_query($conn, "SELECT CandidateNationalId FROM candidate");
$grade_options = '';
while ($fetch = mysqli_fetch_assoc($sql)) {
    $grade_options .= '<option value="' . $fetch['CandidateNationalId'] . '">' . $fetch['CandidateNationalId'] . '</option>';
}

if (isset($_POST['add'])) {
    // Retrieve form data
    $CandidateNationalId = $_POST['CandidateNationalId'];
    $LicenseExamCategory = $_POST['LicenseExamCategory'];
    $ObtainedMarks = $_POST['ObtainedMarks'];

    if ($ObtainedMarks > 20) {
        $message[] = "Marks can't be over 20 marks";
    } else {
        $Decision = $ObtainedMarks > 12 ? " Pass! " : " Fail! ";
     
        if (empty($CandidateNationalId) || empty($LicenseExamCategory) || empty($ObtainedMarks) || empty($Decision)) {
            $message[] = 'Please fill out all fields.';
        } else {
            
            $insert = mysqli_query($conn, "INSERT INTO grade (CandidateNationalId, LicenseExamCategory, ObtainedMarks, Decision) VALUES ('$CandidateNationalId', '$LicenseExamCategory', '$ObtainedMarks', '$Decision')");
            
            if ($insert) {
                $message[] = 'New grade added successfully.';
            } else {
                $message[] = 'Couldn\'t add grade.';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    
    $ID = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM grade WHERE ID = $ID");
    header('location:grade1.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Page</title>
    <link rel="stylesheet" href="styler3.css">
</head>
<body>
    
<div class="links">
    <h1>RWANDA DRIVING LICENCE</h1>
    <center> 
        <div class="header">
            <a href="./candidate.php" style="color: white; padding: 10px; font-size: 15px; font-weight: bold;">HOME</a>
            <a href="./grade1.php" style="color: white; padding: 10px; font-size: 15px; font-weight: bold;">GRADE</a>
            <a href="./aboutus.php" style="color: white; padding: 10px; font-size: 15px; font-weight: bold;">ABOUT US</a>
            <!-- <a href="./logout.php" class="btn1">LOG OUT</a> -->
        </div>
    </center>
</div>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">
    <h2>ADD NEW ADMIN GRADE</h2>
    <div class="grade_information_form_container centered">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <select name="CandidateNationalId" id="CandidateNationalId" required class="box">
                <?php echo $grade_options; ?>
            </select>
            <input type="text" placeholder="LicenseExamCategory" name="LicenseExamCategory" required class="box">
            <input type="number" placeholder="ObtainedMarks" name="ObtainedMarks" required class="box">
            <button type="submit" name="add" class="btn">TAP TO GRADE</button> 
        </form>
    </div>

    <div class="grade_display">
        <table class="grade_display_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>CandidateNationalId</th>
                    <th>LicenseExamCategory</th>
                    <th>ObtainedMarks</th>
                    <th>Decision</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>

            <?php
           
            $select = mysqli_query($conn, "SELECT * FROM grade LIMIT $num_rows");
            $row_number = 1;

            while ($row = mysqli_fetch_assoc($select)) {
            ?>
            <tr>
                <td><?php echo $row_number; ?></td>
                <td><?php echo $row['CandidateNationalId']; ?></td>
                <td><?php echo $row['LicenseExamCategory']; ?></td>
                <td><?php echo $row['ObtainedMarks']; ?></td>
                <td><?php echo $row['Decision']; ?></td>
                <td>
                    <a href="?delete=<?php echo $row['ID']; ?>" class="btn">Delete</a>
                </td>
            </tr>
            <?php
                $row_number++;
            };
            ?>
        </table>
    </div>
</div>
<center><h5 style="font-size: 15px;">Report Candidate Grade Information</h5><button onclick="print()" class="btn" style="width: 90px; ">Print</button></center>

</body>
</html>
