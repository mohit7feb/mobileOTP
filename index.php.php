
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Mobile OTP</title>
</head>
<body>

    <h2>Login with Mobile OTP</h2>

    <form method="post">
        <label for="mobile">Mobile Number:</label>
        <input type="text" name="mobile" required>
        <br>
        <input type="text" name="otp" id="otp" hidden>
        <button type="submit">Request OTP</button>
    </form>

</body>
</html>
<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'];

    // Validate and sanitize the mobile number (you may want to add more validation)
    $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);

    // Generate OTP
    $otp = rand(100000, 999999);

    // Authorisation details.
    $username = "bdev37380@gmail.com";
    $hash = "c503d60a56a94c83eae876843a35bed205e3a29e820ddb2e380bd05ceaa2c2bc";

    // Config variables. Consult http://api.textlocal.in/docs for more info.
    $test = "0";

    // Data for text message. This is the text message data.
    $sender = "TXTLCL"; // This is who the message appears to be from.
    $numbers = array($mobile); // A single number or a comma-separated list of numbers
    $message = "This is a test message from the PHP API script. $otp";
    // 612 chars or less
    // A single number or a comma-separated list of numbers
    $message = urlencode($message);
    $numbers = implode(',', $numbers); // Convert the array to a comma-separated string
    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;


    $ch = curl_init('http://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch); // This is the result from the API
    curl_close($ch);
    // echo $result;

    // echo "API Result: " . $result; // Debugging statement

    $response_data = json_decode($result, true); // Use $result instead of $response

    if (isset($response_data['errors'])) {
        // Handle errors
        foreach ($response_data['errors'] as $error) {
            echo "Error code: {$error['code']}, Message: {$error['message']}";
        }
    } else {
        // Store OTP in the session for verification
        $_SESSION['otp'] = $otp;

        // Provide a success message to the user
        echo "OTP sent successfully. Check your mobile for the OTP.";
    }
}
?>






