<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $agent_name = $_POST['agent_name'];
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postalCode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $beneficiary_name = $_POST['beneficiaryName'];
    $relationship = $_POST['relationship'];
    $beneficiary_phone = $_POST['beneficiaryPhone'];
    $beneficiary_email = $_POST['beneficiaryEmail'];
    $cover_amount = $_POST['coverAmount'];
    $payment_frequency = $_POST['paymentFrequency'];
    $start_date = $_POST['startDate'];

    // Handle the file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $file_name;

        // Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = $target_file;
        } else {
            echo "There was an error uploading the file.";
            exit;
        }
    } else {
        echo "No file was uploaded or there was an error.";
        exit;
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO applications (agent_name, first_name, last_name, dob, gender, address, city, state, postal_code, phone, email, photo, beneficiary_name, relationship, beneficiary_phone, beneficiary_email, cover_amount, payment_frequency, start_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssssssssssss", $agent_name, $first_name, $last_name, $dob, $gender, $address, $city, $state, $postal_code, $phone, $email, $photo_path, $beneficiary_name, $relationship, $beneficiary_phone, $beneficiary_email, $cover_amount, $payment_frequency, $start_date);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Application submitted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
