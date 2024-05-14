<?php 

include 'includes/Authenticate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' )
{
    // Database connection settings
    $host = 'localhost'; // Assuming your database is hosted locally
    $dbname = 'conference_management';
    $username = 'root'; // Change this to your database username
    $password = ' '; // Change this to your database password

    // Attempt database connection
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form inputs
        $useremail = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $type = htmlspecialchars($_POST['userType']);

        // Prepare SQL statement based on user type
        $sql = '';
        if ($type == "student") {
            $sql = "SELECT * FROM user WHERE email = :email AND password = :password AND userType = 'student'";
        } elseif ($type == "institution") {
            $sql = "SELECT * FROM user WHERE email = :email AND password = :password AND userType = 'institution'";
        }

        // Execute SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $useremail);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        // Check if user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // User exists, redirect to appropriate page
            Authenticate::redirect();
        } else {
            // Invalid login credentials
            $status = 'Invalid Login Credentials!';
        }
    } catch(PDOException $e) {
        // Error occurred during database connection
        echo "Connection failed: " . $e->getMessage();
    }
}

?>
