<?php
session_start();
include 'db_config.php'; // Siguroha nga PDO ang sulod niini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // 1. Usba ang table name gikan sa 'users' ngadto sa 'customers'
        // Kay base sa imong screenshot, 'customers' ang ngalan sa table
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // 2. Check password
            // Kung naggamit ka og password_hash() inig register:
            if (password_verify($password, $row['password'])) {
                
                // 3. I-set ang session variables (Gamita ang 'customer_id')
                $_SESSION['customer_id'] = $row['id']; 
                $_SESSION['customer_name'] = $row['name'] ?? $row['first_name'];

                header("Location: customer_dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>