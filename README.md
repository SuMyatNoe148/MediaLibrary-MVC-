# 🎬 Media Library

A PHP-based Media Library web application that allows users to browse, search, and manage digital media content. The project follows a layered architecture approach to improve maintainability and separation of concerns.

## ✨ Features

- User Registration & Authentication
- Browse Media Catalog
- Search and Filter Media
- Media Detail Pages
- User Favorites
- Responsive Design
- Stripe Payment Integration
- Secure Session Management
- Admin Media Management

## 🏗️ Architecture

The project is organized using a layered architecture:

```text
src/
├── Domain/
├── Application/
├── Infrastructure/
└── Presentation/
```

### Layers

- **Domain** – Core business entities and repository contracts.
- **Application** – Business logic and services.
- **Infrastructure** – Database access, external services, and integrations.
- **Presentation** – Controllers, views, and UI handling.

## 🛠️ Technologies

- PHP 8+
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap
- Stripe API
- Composer

## 📦 Installation

### Clone the repository

```bash
git clone https://github.com/SuMyatNoe148/MediaLibrary-MVC.git
cd media-library
```

### Install dependencies

```bash
composer install
```

### Configure database

Update your database configuration file with your MySQL credentials.

### Import database

Import the SQL file into MySQL:

```sql
media_library.sql
```

### Run the application

Using XAMPP:

1. Start Apache
2. Start MySQL
3. Place project inside:

```text
htdocs/
```

4. Open:

```text
http://localhost/media-library
```

## 💳 Stripe Integration

Configure your Stripe keys:

```env
STRIPE_SECRET_KEY=your_secret_key
STRIPE_PUBLISHABLE_KEY=your_publishable_key
```

For local webhook testing:

```bash
ngrok http 80
```

Use the generated URL as your Stripe webhook endpoint.

## 📂 Project Structure

```text
media-library/
│
├── config/
├── public/
├── src/
│   ├── Domain/
│   ├── Application/
│   ├── Infrastructure/
│   └── Presentation/
├── vendor/
└── composer.json
```

## 🔒 Security

- Password Hashing
- Prepared Statements
- Session Authentication
- Input Validation
- Output Escaping
- Stripe Secure Payments

## 🚀 Future Improvements

- Dependency Injection Container
- PHPUnit Testing
- REST API
- JWT Authentication
- Docker Support
- Cloud Storage Integration


This project is for educational purposes.
