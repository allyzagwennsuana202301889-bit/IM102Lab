<?php
require_once 'config.php';

$sql = "SELECT * FROM s_student ORDER BY idname ASC ";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link rel ="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student List</h1>
            <p>
 <a href="add.php" style="
 display: inline-block;
 padding: 10px 20px;
 background: #4CAF50;
 color: white;
 text-decoration: none;
 border-radius: 4px;
 ">+ Add Student</a>
</p>
        
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year</th>
                <th>Date Added</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
                
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                
                <td><?= $row['idname'] ?></td>
                <td><?= htmlspecialchars($row['Sname']) ?></td>
                <td><?= htmlspecialchars($row['Scourse']) ?></td>
                <td><?= $row['Syear'] ?></td>
                <td><?= $row['Screated'] ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td>
    <a href="edit.php?id=<?= $row['idname'] ?>" 
       style="color: #2196F3; text-decoration: none; margin-right: 10px;">Edit</a>
    <a href="delete.php?id=<?= $row['idname'] ?>" 
       style="color: #f44336; text-decoration: none;"
       onclick="return confirm('Delete this student?')">Delete</a>
</td>
            </tr>
            <?php endwhile; ?>
            
        </table>
        
        <p class="count">Total: <?= $result->num_rows ?> student(s)</p>
    </div>
    


</body>
</html>