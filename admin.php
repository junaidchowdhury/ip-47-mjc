<!-- filepath: /c:/xampp/htdocs/TRAVEL_WEB/admin.php -->
<?php
$conn = new mysqli('localhost', 'root', '', 'dblogin');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle confirm and delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm'])) {
        $id = $_POST['id'];
        // You can add any additional logic for confirming the booking here
        echo "<script>alert('Booking confirmed for ID: $id');</script>";
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        // Delete the booking
        $sql = "DELETE FROM booking_info WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 20px;
        }
        .container {
            width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #ccc;
        }
        h2 {
            color: #FF5400;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #FF5400;
            color: white;
        }
        .delete-btn, .edit-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .edit-btn {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Users' Hotel Booking History</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Hotel Name</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Actions</th>
            </tr>
  
            <?php
            // Fetch data from database
            $sql = "SELECT id, full_name, email, hotel_name, check_in, check_out FROM booking_info";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['hotel_name']}</td>
                            <td>{$row['check_in']}</td>
                            <td>{$row['check_out']}</td>
                            <td>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='confirm' class='edit-btn'>Confirm</button>
                                </form>
                                <form method='POST' action='' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='delete' class='delete-btn'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>