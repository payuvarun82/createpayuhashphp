# Simple PHP Project

A basic PHP web application demonstrating core PHP concepts and functionality.

## Features
- 🚀 PHP 7.4+ Compatible
- 📝 Session Management
- 🎨 Responsive Design
- 📋 Form Validation
- 🔗 Simple Routing
- 📡 API Endpoints

## Getting Started

### Prerequisites
- PHP 7.4 or higher
- Web browser

### Installation
1. Download and extract the project files
2. Navigate to the project directory
3. Start the PHP development server:
   ```bash
   php -S localhost:8000
   ```
4. Open your browser and visit: `http://localhost:8000`

## Project Structure
```
simple-php-project/
├── index.php          # Main homepage
├── about.php           # About page
├── contact.php         # Contact form
├── api.php            # API endpoints
├── clear-session.php  # Session management
├── css/
│   └── styles.css     # Stylesheet
├── js/
│   └── scripts.js     # JavaScript
└── includes/          # PHP includes (future use)
```

## Usage
- **Homepage**: View features and register users
- **About**: Learn about the project
- **Contact**: Send messages via contact form
- **API**: Test JSON endpoints at `/api.php?action=info`

## API Endpoints
- `GET /api.php?action=info` - Server information
- `GET /api.php?action=session` - Session data

Built with ❤️ and PHP
