<?php
/**
 * Contact Form Submission Handler
 * Processes contact form data and stores in MySQL database
 */

// Enable error logging to file for debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Include database connection
require_once 'db.php';

// Start session for flash messages (optional)
session_start();

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html?status=error');
    exit;
}

// Get and sanitize form inputs
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

error_log("Form received - Name: $full_name, Email: $email");

// Validate required fields
$errors = [];

if (empty($full_name)) {
    $errors[] = 'Full name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If validation fails, redirect with error
if (!empty($errors)) {
    error_log("Validation errors: " . implode(', ', $errors));
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: contact.html?status=validation_error');
    exit;
}

// Get database connection
$db = getDbConnection();

if ($db === null) {
    error_log("Database connection failed");
    header('Location: contact.html?status=db_error');
    exit;
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO contact_messages (full_name, email, subject, message, created_at, status) 
            VALUES (:full_name, :email, :subject, :message, NOW(), 'new')";
    
    error_log("Executing SQL: $sql");
    
    $stmt = $db->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    
    // Execute query
    $result = $stmt->execute();
    error_log("Query executed successfully. Rows affected: " . $stmt->rowCount());
    
    // Clear form data from session
    unset($_SESSION['form_data']);
    
    // Redirect with success message
    header('Location: contact.html?status=success');
    exit;
    
} catch (PDOException $e) {
    // Log error with full details
    error_log("Database Error: " . $e->getMessage());
    error_log("Error Code: " . $e->getCode());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Redirect with error
    header('Location: contact.html?status=error');
    exit;
}
?>
