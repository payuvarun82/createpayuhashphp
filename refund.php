<?php

/**
 * This implementation uses PayU v2 Refund Initiation API
 * Documentation: https://docs.payu.in/v2/reference/v2-refund-transaction-api
 *
 * PayU Configurations
 * Replace the values for Merchant_KEY, Merchant_SALT, and Environment(sandbox or prod)
 */
$payuConfig = [
     "key" => "PRiQvJ", // Your Merchant KEY
     "salt" => "mGHSxpD2iBVywParGQrGBlaXjnwkGJMQ", // Your Merchant SALT
     "environment" => "sandbox", // Set "sandbox" for sandbox and "prod" for production
];

/**
 * Function to initiate a refund using PayU v2 API
 *
 * @param array config The PayU configuration array (key, salt, and environment)
 * @param string payuId The PayU transaction ID for the refund
 * @param string refundToken The refund token
 * @param float amount The refund amount
 * @param array refundDetails Additional refund details (optional)
 * @param array refundSplitRequest Split refund request (optional)
 * @return mixed The response from the PayU API
 * @throws Exception If any required parameter is missing or invalid
 */
function initiateRefund($config, $payuId, $refundToken, $amount, $refundDetails = [], $refundSplitRequest = []) {
    // Determine the API URL based on the environment
    if ($config["environment"] === "sandbox") {
        $refundURL = "https://apitest.payu.in/v2/refund/";
    } elseif ($config["environment"] === "prod") {
        $refundURL = "https://api.payu.in/v2/refund/";
    } else {
        throw new Exception("Invalid Environment. Expected values 'sandbox' or 'prod', but received " . $config["environment"]);
    }

    // Validate required parameters
    if (empty($payuId)) {
        throw new Exception("payuId is missing");
    }
    if (empty($refundToken)) {
        throw new Exception("refundToken is missing");
    }
    if (empty($amount)) {
        throw new Exception("amount is missing");
    }

    // Generate current date in required format
    $date = gmdate("D, d M Y H:i:s T");

    // Prepare request body
    $requestBody = [
        "payuId" => $payuId,
        "refundToken" => $refundToken,
        "amount" => $amount,
        "refundDetails" => empty($refundDetails) ? (object) [] : $refundDetails,
        "refundSplitRequest" => empty($refundSplitRequest) ? (object) [] : $refundSplitRequest
    ];

    // Generate hash string for authorization
    $bodyData = json_encode($requestBody);
    $hashString = $bodyData . '|' . $date . '|' . $config["salt"];
    $hash = hash('sha512', $hashString);

    // Prepare headers
    $headers = [
        "Content-Type: application/json",
        "date: " . $date,
        "Authorization: hmac username=\"" . $config["key"] . "\", algorithm=\"sha512\", headers=\"date\", signature=\"" . $hash . "\""
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $refundURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Execute the request and parse the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Handle cURL errors
    if ($curlError) {
        throw new Exception("cURL Error: " . $curlError);
    }

    // Handle HTTP errors
    if ($httpCode !== 200) {
        throw new Exception("HTTP Error: Received status code $httpCode. Response: $response");
    }

    // Decode and return the response as an array
    return json_decode($response, true);
}

/**
 * Main execution point
 */
try {
    // Replace the below dummy values with actual refund values
    $payuId = "403993715534690468"; // PayU transaction ID
    $refundToken = "refund40399371553469046"; // Refund token
    $amount = "1000"; // Refund amount

    // Call the initiateRefund function
    $payuResponse = initiateRefund($payuConfig, $payuId, $refundToken, $amount);

    // Print the response
    echo "PayU Response:\n";
    print_r($payuResponse);

} catch (Exception $e) {
    // Handle and display exceptions
    echo "Error: " . $e->getMessage() . "\n";
}
