<?php
require_once 'config.php';

$sql = "SELECT * FROM s_student";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head><title>Student List</title></head>
<body>
    <h1>Student List</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <p>
                <strong><?= $row['Sname'] ?></strong> — 
                <?= $row['Scourse'] ?> Year <?= $row['Syear'] ?>
            </p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>
    
    <p>Total: <?= $result->num_rows ?> students</p>
</body>
</html>