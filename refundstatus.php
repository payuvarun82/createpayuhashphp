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
 * Function to check refund status
 *
 * @param array config The PayU configuration array (key, salt, and environment)
 * @param string payuid The PayU transaction ID for the refund
 * @return mixed The response from the PayU API
 * @throws Exception If any required parameter is missing or invalid
 */
function checkRefundStatus($config, $payuid) {
    // Determine the API URL based on the environment
    if ($config["environment"] === "sandbox") {
        $apiURLV1 = "https://test.payu.in/merchant/postservice.php?form=2";
    } elseif ($config["environment"] === "prod") {
        $apiURLV1 = "https://info.payu.in/merchant/postservice.php?form=2";
    } else {
        throw new Exception("Invalid Environment. Expected values 'sandbox' or 'prod', but received " . $config["environment"]);
    }

    // Validate that the PayU transaction ID is provided
    if (empty($payuid)) {
        throw new Exception("PayU transaction ID (payuid) is missing.");
    }

    // Prepare the command and hash
    $command = "check_action_status";
    $hashString = $config["key"] . "|" . $command . "|" . $payuid . "|" . $config["salt"];
    $hash = hash("sha512", $hashString);

    // Prepare the request data
    $data = [
        "key" => $config["key"],
        "command" => $command,
        "var1" => $payuid,
        "var2" => "payuid",
        "hash" => $hash,
    ];

    // Convert data into URL-encoded string
    $formBody = http_build_query($data);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiURLV1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formBody);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded"
    ]);

    // Execute the request and parse the response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

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
    // Replace payuid with the actual PayU transaction ID
    $payuid = "403993715534690468";

    // Call the checkRefundStatus function
    $payuResponse = checkRefundStatus($payuConfig, $payuid);

    // Print the response
    echo "PayU Response:\n";
    print_r($payuResponse);

} catch (Exception $e) {
    // Handle and display exceptions
    echo "Error: " . $e->getMessage() . "\n";
}
