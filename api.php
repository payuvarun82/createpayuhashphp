<?php
        /* PayU Merchant Key and Salt
        * These values are used for authentication and should be kept secret
        * Change them to your actual Merchant Key and Salt
        * You can find these values in your PayU Merchant Dashboard
        * Production key and salt can be downloaded from here https://docs.payu.in/docs/generate-merchant-key-and-salt-on-payu-dashboard
        * Sandbox key and salt can be downloaded from here https://docs.payu.in/docs/generate-test-merchant-key-and-salt
        * Make sure to use the correct key and salt for the environment you are working in
        * For example, if you are working in the sandbox environment, use the sandbox key and salt
        */
 // Helper function to calculate HMAC SHA-512 hash
 function calculateHmac($data, $date, $salt) {
     $hashString = json_encode($data) . "|" . $date . "|" . $salt;
     return hash('sha512', $hashString);
 }
 
 // PayU Configurations
 $payuConfig = [
     "key" => "PRiQvJ", // Your Merchant KEY
     "salt" => "mGHSxpD2iBVywParGQrGBlaXjnwkGJMQ", // Your Merchant SALT
     "environment" => "sandbox", // Set "sandbox" for sandbox and "prod" for production
 ];
 
 // Helper function (do not modify)
 function initiatePayment($config, $data) {
     try {
         // Determine the payment URL based on the environment
         if ($config["environment"] === "sandbox") {
             $paymentUrl = "https://apitest.payu.in/v2/payments";
         } elseif ($config["environment"] === "prod") {
             $paymentUrl = "https://api.payu.in/v2/payments";
         } else {
             throw new Exception("Invalid environment. Expected 'sandbox' or 'prod', got: " . $config["environment"]);
         }
 
         // Generate current UTC date in the required format
         $date = gmdate("D, d M Y H:i:s T");
 
         // Add accountId if not present in the request data
         if (!array_key_exists("accountId", $data)) {
             $data["accountId"] = $config["key"];
         }
 
         // Calculate the HMAC hash
         $hash = calculateHmac($data, $date, $config["salt"]);
 
         // Prepare request headers
         $headers = [
             "Authorization: hmac username=\"" . $config["key"] . "\", algorithm=\"sha512\", headers=\"date\", signature=\"$hash\"",
             "Date: " . $date,
             "Content-Type: application/json"
         ];
 
         // Make API request
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $paymentUrl);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $response = curl_exec($ch);
         curl_close($ch);
 
         // Decode and return the response
         return json_decode($response, true);
     } catch (Exception $e) {
         echo "Error initiating payment: " . $e->getMessage();
         throw $e;
     }
 }
 
 // Main payment processing
 try {
     // Replace the below dummy values with your actual payment values
     $paymentData = [
         "currency" => "INR",
         "accountId" => $payuConfig["key"],
         "txnId" => "test_" . rand(1000, 9999), // Generate unique transaction ID
         "order" => [
             "productInfo" => "productInfo", // Mandatory
             "paymentChargeSpecification" => [
                 "price" => 10000 // Mandatory
             ]
         ],
         "callBackActions" => [
             "successAction" => "https://test.payu.in/admin/test_response", // Replace with redirection URL (POST)
             "failureAction" => "https://test.payu.in/admin/test_response" // Replace with redirection URL (POST)
         ],
         "billingDetails" => [
             "firstName" => "test", // Mandatory
             "phone" => "9999999999", // Mandatory
             "lastName" => "",
             "address1" => "",
             "address2" => "",
             "city" => "",
             "state" => "",
             "country" => "",
             "zipCode" => ""
         ],
         "additionalInfo" => [
             "txnFlow" => "nonseamless",
             "userToken"=>"User_2",
          //   "enforcePaymethod" => "upi|creditcard"
         ]
     ];
 
     $payuResponse = initiatePayment($payuConfig, $paymentData);
     $checkoutUrl = $payuResponse["result"]["checkoutUrl"] ?? null;
 
     if (!$checkoutUrl) {
         throw new Exception("Checkout URL is missing in the response.");
     }
 
     echo "Response: " . json_encode($payuResponse, JSON_PRETTY_PRINT) . "
 ";
     echo "Checkout URL: " . $checkoutUrl . "
 ";
 
 } catch (Exception $e) {
     echo "Error: " . $e->getMessage() . "
 ";
 }
?>
 