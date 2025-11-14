<?php
/**
 * PayU Configurations
 * Replace the values for Merchant_KEY, Merchant_SALT, and Environment(sandbox or prod)
 */
$payuConfig = [
     "key" => "PRiQvJ", // Your Merchant KEY
     "salt" => "mGHSxpD2iBVywParGQrGBlaXjnwkGJMQ", // Your Merchant SALT
     "environment" => "sandbox", // Set "sandbox" for sandbox and "prod" for production
];

/**
 * Function to verify payment using new v3 API
 *
 * @param array config The PayU configuration array (key, salt, and environment)
 * @param string txnid The transaction ID to verify
 * @return mixed The response from the PayU API
 * @throws Exception If any required parameter is missing or invalid
 */
function verify_payment($config, $txnid) {
    // Determine the API URL based on the environment
    if ($config["environment"] === "sandbox") {
        $apiUrl = "https://test.payu.in/v3/transaction";
    } elseif ($config["environment"] === "prod") {
        $apiUrl = "https://info.payu.in/v3/transaction";
    } else {
        throw new Exception("Invalid Environment. Expected values 'sandbox' or 'prod', but received " . $config['environment']);
    }

    // Validate that the transaction ID is provided
    if (empty($txnid)) {
        throw new Exception("Transaction ID (txnid) is missing.");
    }

    // Prepare the request data for new API
    $data = [
        "txnId" => [$txnid]
    ];

    // Generate date in UTC format
    $date = gmdate("D, d M Y H:i:s T");

    // Create hash string for new API
    $hashString = json_encode($data) . "|" . $date . "|" . $config['salt'];
    $hashValue = hash('sha512', $hashString);

    // Set request headers for new API
    $headers = [
        "Authorization: hmac username=\"" . $config['key'] . "\", algorithm=\"sha512\", headers=\"date\", signature=\"" . $hashValue . "\"",
        "Date: " . $date,
        "Info-Command: verify_payment",
        "Content-Type: application/json"
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request and parse the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Handle different types of errors
    if ($curlError) {
        // cURL error occurred
        echo "cURL Error: " . $curlError . "\n";
        return ["error" => "cURL Error: " . $curlError];
    } elseif ($httpCode !== 200) {
        // HTTP error occurred
        echo "Error Status: " . $httpCode . "\n";
        echo "Error Response Body: " . $response . "\n";
        return ["error" => "HTTP Error: " . $httpCode, "response" => $response];
    }

    // Decode and return the response as an associative array
    return json_decode($response, true);
}

/**
 * Main execution point
 */
try {
    // Replace the transaction ID with an actual ID to verify
    $txnid = "test_8968";

    // Call the verify_payment function
    $payuResponse = verify_payment($payuConfig, $txnid);

    // Print the response
    echo "PayU Response:\n";
    print_r($payuResponse);

} catch (Exception $e) {
    // Handle and display exceptions
    echo "Error: " . $e->getMessage() . "\n";
}

?>