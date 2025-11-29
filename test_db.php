<?php
include 'config.php';

// Check if database exists
$check_db = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'eventwale'";
$db_result = $conn->query($check_db);

echo "Database Check:\n";
if ($db_result->num_rows > 0) {
    echo "✓ Database 'eventwale' exists\n";
} else {
    echo "✗ Database 'eventwale' does not exist\n";
}

// Check if users table exists and its structure
$check_table = "SHOW TABLES LIKE 'users'";
$table_result = $conn->query($check_table);

echo "\nTable Check:\n";
if ($table_result->num_rows > 0) {
    echo "✓ Table 'users' exists\n\nTable Structure:\n";
    
    $structure = $conn->query("DESCRIBE users");
    while($row = $structure->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']}\n";
    }
    
    // Check if there are any records
    $count = $conn->query("SELECT COUNT(*) as total FROM users");
    $total = $count->fetch_assoc()['total'];
    echo "\nTotal Records: $total\n";
} else {
    echo "✗ Table 'users' does not exist\n";
    
    // Create the users table if it doesn't exist
    $create_table = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(15) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) === TRUE) {
        echo "✓ Created 'users' table successfully\n";
    } else {
        echo "✗ Error creating table: " . $conn->error . "\n";
    }
}

$conn->close();
?>
