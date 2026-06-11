<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM s_student WHERE idname = $id");
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = (int)$_POST['year'];
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    
    if (empty($name) || empty($course)) {
        $message = '<p style="color:red;">Name and course are required.</p>';
    } else {
        $sql = "UPDATE s_student SET 
                Sname='$name', Scourse='$course', Syear=$year,
                email='$email', phone='$phone', address='$address'
                WHERE idname=$id";
        
        if ($conn->query($sql)) {
            header('Location: index4.php');
            exit;
        } else {
            $message = '<p style="color:red;">Error: ' . $conn->error . '</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea { resize: vertical; }
        button {
            margin-top: 15px;
            padding: 12px 30px;
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover { background: #1976D2; }
        .cancel { margin-left: 10px; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student #<?= $student['idname'] ?></h1>
        
        <?= $message ?>
        
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['Sname']) ?>" required>
            
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
            
            <label>Course</label>
            <select name="course" required>
                <option value="BSIT" <?= $student['Scourse'] == 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                <option value="BSCS" <?= $student['Scourse'] == 'BSCS' ? 'selected' : '' ?>>BSCS</option>
            </select>
            
            <label>Year Level</label>
            <input type="number" name="year" value="<?= $student['Syear'] ?>" min="1" max="5" required>
            
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
            
            <label>Address</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
            
            <button type="submit">Update Student</button>
            <a href="index4.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>
</html>