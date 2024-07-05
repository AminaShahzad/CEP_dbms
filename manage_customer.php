<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

include("dbconnection.php");
$con = dbconnection();

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Get POST data as JSON

    if (isset($data["action"])) {
        $action = $data["action"];

        switch ($action) {
            case "add_customer":
                if (isset($data["name"]) && isset($data["email"]) && isset($data["address"]) && isset($data["phoneno"])) {
                    $name = $data["name"];
                    $email = $data["email"];
                    $address = $data["address"];
                    $phoneno = $data["phoneno"];

                    $stmt = $con->prepare("INSERT INTO `customer` (`CUST_NAME`, `EMAIL`, `PHONE_NUM`, `ADDRESS`) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $email, $phoneno, $address);

                    if ($stmt->execute()) {
                        $response["success"] = true;
                        $response["message"] = "Customer added successfully";
                    } else {
                        $response["success"] = false;
                        $response["message"] = "Failed to add customer: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response["success"] = false;
                    $response["message"] = "Missing required fields";
                }
                break;

            case "fetch_data":
                $query = "SELECT * FROM `customer`";
                $result = mysqli_query($con, $query);

                if ($result) {
                    $customers = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $customers[] = $row;
                    }
                    $response["success"] = true;
                    $response["data"] = $customers;
                } else {
                    $response["success"] = false;
                    $response["message"] = "Failed to fetch customers: " . mysqli_error($con);
                }
                break;

            case "update_customer":
                if (isset($data["cust_id"]) && isset($data["phoneno"])) {
                    $cust_id = $data["cust_id"];
                    $phoneno = $data["phoneno"];
                    $name = isset($data["name"]) ? $data["name"] : '';
                    $email = isset($data["email"]) ? $data["email"] : '';
                    $address = isset($data["address"]) ? $data["address"] : '';

                    $stmt = $con->prepare("UPDATE `customer` SET `CUST_NAME` = ?, `EMAIL` = ?, `ADDRESS` = ?, `PHONE_NUM` = ? WHERE `CUST_ID` = ? AND `PHONE_NUM` = ?");
                    $stmt->bind_param("ssssss", $name, $email, $address, $phoneno, $cust_id, $phoneno);

                    if ($stmt->execute()) {
                        $response["success"] = true;
                        $response["message"] = "Customer updated successfully";
                    } else {
                        $response["success"] = false;
                        $response["message"] = "Failed to update customer: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response["success"] = false;
                    $response["message"] = "Missing cust_id or phoneno";
                }
                break;

            case "delete":
                if (isset($data["custid"]) && isset($data["phoneno"])) {
                    $custid = $data["custid"];
                    $phoneno = $data["phoneno"];

                    $stmt = $con->prepare("DELETE FROM `customer` WHERE `CUST_ID` = ? AND `PHONE_NUM` = ?");
                    $stmt->bind_param("ss", $custid, $phoneno);

                    if ($stmt->execute()) {
                        $response["success"] = true;
                        $response["message"] = "Customer deleted successfully";
                    } else {
                        $response["success"] = false;
                        $response["message"] = "Failed to delete customer: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response["success"] = false;
                    $response["message"] = "Missing cust_id or phoneno";
                }
                break;

            default:
                $response["success"] = false;
                $response["message"] = "Invalid action specified";
                break;
        }
    } else {
        $response["success"] = false;
        $response["message"] = "No action specified";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Invalid request method";
}

echo json_encode($response);
?>
