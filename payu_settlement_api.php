<?php
// Set content type for browser display
header('Content-Type: text/html; charset=UTF-8');

// Initialize variables with default values
//$merchant_key = "wdBcgU";  // Replace with your merchant key $merchant_secret = "wPOBweVHKM8KEnhpcVq6AXI6z9aWBBpB";  // Replace with your merchant salt $mid = "8441593";  // Replace with your merchant I

$merchant_key = '';
$merchant_secret = '';
$mid = '';
$date_from = '';
$errors = [];
$response = null;
$http_code = null;
$execution_time = null;
$curl_info = null;
$error = null;

// Check if form is submitted
if ($_POST) {
    // Get form values
    $merchant_key = trim($_POST['merchant_key'] ?? '');
    $merchant_secret = trim($_POST['merchant_secret'] ?? '');
    $mid = trim($_POST['mid'] ?? '');
    $date_from = trim($_POST['date_from'] ?? '');
    
    // Validation
    if (empty($merchant_key)) {
        $errors[] = 'Merchant Key is required';
    }
    
    if (empty($merchant_secret)) {
        $errors[] = 'Merchant Secret (Salt) is required';
    }
    
    if (empty($mid)) {
        $errors[] = 'Merchant ID (MID) is required';
    }
    
    if (empty($date_from)) {
        $errors[] = 'Date From is required';
    } elseif (!DateTime::createFromFormat('Y-m-d', $date_from)) {
        $errors[] = 'Date From must be in YYYY-MM-DD format';
    }
    
    // Only proceed with API call if no validation errors
    if (empty($errors)) {
        // API endpoint and parameters
        $url = "https://info.payu.in/settlement/range/";
      //  $url = "https://apitest.payu.in/settlement/range/";
        $params = [
            'pageSize' => 100,
            'page' => 1,
            'dateFrom' => $date_from
        ];

        // Build query string
        $query_string = http_build_query($params);
        $full_url = $url . '?' . $query_string;

        // Generate current GMT date
        $date = gmdate('D, d M Y H:i:s T');

        // Generate authorization header
        function getAuthHeader($date, $merchant_key, $merchant_secret, $data = '') {
            $auth_type = 'sha512';
            
            // Create hash string: data|date|merchant_secret
            $hash_string = $data . '|' . $date . '|' . $merchant_secret;
            
            // Generate SHA512 hash
            $hash = hash('sha512', $hash_string);
            
            // Create authorization header
            $auth_header = 'hmac username="' . $merchant_key . '", algorithm="' . $auth_type . '", headers="date", signature="' . $hash . '"';
            
            return $auth_header;
        }

        // Generate authorization header (empty data for GET request)
        $authorization = getAuthHeader($date, $merchant_key, $merchant_secret, '');

        // Set up headers
        $headers = [
            'Authorization: ' . $authorization,
            'date: ' . $date,
            'mid: ' . $mid,
        ];

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $full_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        // Execute request
        $start_time = microtime(true);
        $response = curl_exec($ch);
        $end_time = microtime(true);
        $execution_time = round(($end_time - $start_time) * 1000, 2);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_info = curl_getinfo($ch);
        $error = curl_error($ch);

        // Close cURL
        curl_close($ch);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayU Settlement API Response</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        
        .content {
            padding: 30px;
        }
        
        /* Form Styles */
        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .form-section h2 {
            margin-top: 0;
            color: #495057;
            font-size: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #495057;
        }
        
        .form-group input {
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input.error {
            border-color: #dc3545;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 16px;
        }
        
        .info-card p {
            margin: 0;
            font-weight: bold;
            color: #212529;
        }
        
        .response-container {
            background: #1e1e1e;
            color: #f8f8f2;
            padding: 25px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 20px 0;
            position: relative;
        }
        
        .response-header {
            background: #667eea;
            color: white;
            padding: 15px 25px;
            margin: -25px -25px 25px -25px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .copy-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .copy-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .debug-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .debug-section h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .debug-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #6c757d;
        }
        
        .debug-item strong {
            color: #495057;
        }
        
        .highlight {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏦 PayU Settlement API Response</h1>
            <p>Enter your merchant credentials to fetch settlement data</p>
        </div>
        
        <div class="content">
            <!-- Configuration Form -->
            <div class="form-section">
                <h2>🔧 API Configuration</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <strong>❌ Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="merchant_key">🔑 Merchant Key *</label>
                            <input type="text" 
                                   id="merchant_key" 
                                   name="merchant_key" 
                                   value="<?php echo htmlspecialchars($merchant_key); ?>"
                                   placeholder="Enter your merchant key"
                                   required
                                   <?php echo in_array('Merchant Key is required', $errors) ? 'class="error"' : ''; ?>>
                            <div class="help-text">Your PayU merchant key (e.g., wdBcgU)</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="merchant_secret">🧂 Merchant Secret (Salt) *</label>
                            <input type="password" 
                                   id="merchant_secret" 
                                   name="merchant_secret" 
                                   value="<?php echo htmlspecialchars($merchant_secret); ?>"
                                   placeholder="Enter your merchant secret"
                                   required
                                   <?php echo in_array('Merchant Secret (Salt) is required', $errors) ? 'class="error"' : ''; ?>>
                            <div class="help-text">Your PayU merchant salt/secret key</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="mid">🏪 Merchant ID (MID) *</label>
                            <input type="text" 
                                   id="mid" 
                                   name="mid" 
                                   value="<?php echo htmlspecialchars($mid); ?>"
                                   placeholder="Enter your merchant ID"
                                   required
                                   <?php echo in_array('Merchant ID (MID) is required', $errors) ? 'class="error"' : ''; ?>>
                            <div class="help-text">Your PayU merchant ID (e.g., 11197)</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_from">📅 Date From *</label>
                            <input type="date" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="<?php echo htmlspecialchars($date_from); ?>"
                                   required
                                   <?php echo (in_array('Date From is required', $errors) || in_array('Date From must be in YYYY-MM-DD format', $errors)) ? 'class="error"' : ''; ?>>
                            <div class="help-text">Start date for settlement data (YYYY-MM-DD)</div>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        🚀 Fetch Settlement Data
                    </button>
                </form>
            </div>
            
            <?php if ($_POST && empty($errors)): ?>
                <!-- API Response Section -->
                <?php if ($error): ?>
                    <div class="status-badge status-error">
                        ❌ Request Failed
                    </div>
                    <div class="response-container">
                        <div class="response-header">
                            <span>cURL Error</span>
                        </div>
                        <pre><?php echo htmlspecialchars($error); ?></pre>
                    </div>
                <?php else: ?>
                    <div class="status-badge <?php echo ($http_code >= 200 && $http_code < 300) ? 'status-success' : 'status-error'; ?>">
                        <?php echo ($http_code >= 200 && $http_code < 300) ? '✅ Success' : '❌ Error'; ?>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-card">
                            <h3>📊 HTTP Status</h3>
                            <p><?php echo $http_code; ?></p>
                        </div>
                        <div class="info-card">
                            <h3>⏱️ Execution Time</h3>
                            <p><?php echo $execution_time; ?> ms</p>
                        </div>
                        <div class="info-card">
                            <h3>📦 Response Size</h3>
                            <p><?php echo number_format(strlen($response)); ?> bytes</p>
                        </div>
                        <div class="info-card">
                            <h3>🌐 Content Type</h3>
                            <p><?php echo $curl_info['content_type'] ?? 'Unknown'; ?></p>
                        </div>
                    </div>
                    
                    <div class="response-container">
                        <div class="response-header">
                            <span>📋 API Response</span>
                            <button class="copy-btn" onclick="copyResponse()">Copy Response</button>
                        </div>
                        <pre id="response-content"><?php 
                            // Try to format as JSON if possible
                            $json_response = json_decode($response, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                echo htmlspecialchars(json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                            } else {
                                echo htmlspecialchars($response);
                            }
                        ?></pre>
                    </div>
                    
                    <?php if ($json_response && is_array($json_response)): ?>
                        <div class="info-grid">
                            <div class="info-card">
                                <h3>🔍 Response Analysis</h3>
                                <p><?php echo count($json_response); ?> top-level elements</p>
                            </div>
                            <div class="info-card">
                                <h3>🗝️ Available Keys</h3>
                                <p><?php echo implode(', ', array_keys($json_response)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="debug-section">
                        <h3>🔧 Debug Information</h3>
                        <div class="debug-item">
                            <strong>📅 Request Date:</strong> 
                            <span class="highlight"><?php echo htmlspecialchars($date); ?></span>
                        </div>
                        <div class="debug-item">
                            <strong>🔐 Authorization Header:</strong>
                            <br><code style="word-break: break-all; font-size: 12px;"><?php echo htmlspecialchars($authorization); ?></code>
                        </div>
                        <div class="debug-item">
                            <strong>🌐 Full URL:</strong>
                            <br><code style="word-break: break-all;"><?php echo htmlspecialchars($full_url); ?></code>
                        </div>
                        <div class="debug-item">
                            <strong>📡 Request Headers:</strong>
                            <ul style="margin: 10px 0;">
                                <?php foreach ($headers as $header): ?>
                                    <li><code><?php echo htmlspecialchars($header); ?></code></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function copyResponse() {
            const responseContent = document.getElementById('response-content');
            const textArea = document.createElement('textarea');
            textArea.value = responseContent.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = 'Copied! ✓';
            btn.style.background = 'rgba(40, 167, 69, 0.8)';
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = 'rgba(255,255,255,0.2)';
            }, 2000);
        }
        
        // Show/hide password functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add show/hide toggle for merchant secret field
            const secretField = document.getElementById('merchant_secret');
            const showHideBtn = document.createElement('button');
            showHideBtn.type = 'button';
            showHideBtn.innerHTML = '👁️';
            showHideBtn.style.cssText = 'position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 16px;';
            
            secretField.parentNode.style.position = 'relative';
            secretField.parentNode.appendChild(showHideBtn);
            
            showHideBtn.addEventListener('click', function() {
                if (secretField.type === 'password') {
                    secretField.type = 'text';
                    showHideBtn.innerHTML = '🙈';
                } else {
                    secretField.type = 'password';
                    showHideBtn.innerHTML = '👁️';
                }
            });
        });
    </script>
</body>
</html>