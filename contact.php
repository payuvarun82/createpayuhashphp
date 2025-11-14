<?php
session_start();
$message = "";
$success = false;

if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($msg)) {
        $success = true;
        $message = "Thank you, " . htmlspecialchars($name) . "! Your message has been sent.";
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Simple PHP Project</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📞 Contact Us</h1>
            <a href="index.php" class="back-link">← Back to Home</a>
        </header>
        <main>
            <?php if ($message): ?>
                <div class="alert <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <section class="contact-form">
                <h2>📧 Send us a message</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name: *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject: *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message: *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 Simple PHP Project. Built with ❤️ and PHP.</p>
        </footer>
    </div>
</body>
</html>