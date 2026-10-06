# shopAmar

A responsive multi-role e-commerce website built with PHP, MySQL, Bootstrap, and multilingual support for Bangla and English.

## Features
- Visitor, user, reseller, and admin panels
- Bangla + English language toggle
- Responsive mobile + desktop UI
- Product, category, coupon, order, and role management
- Basic authentication and role-based access structure
- MySQL schema for core e-commerce entities

## Tech Stack
- PHP 8+
- MySQL
- Bootstrap 5
- HTML, CSS, JavaScript

## Project Structure
- `public/` – public storefront pages
- `admin/` – admin dashboard and management screens
- `includes/` – shared layout and auth helpers
- `config/` – DB and app configuration
- `db/` – SQL schema
- `assets/` – CSS and JS

## Quick Setup
1. Create a MySQL database named `shopamar`.
2. Import `db/schema.sql`.
3. Update database credentials in `config/database.php`.
4. Put the project in a PHP-enabled web server root.
5. Open `http://localhost/` or your configured virtual host.

## Default Admin Login
- Email: `admin@shopamar.com`
- Password: `admin123`

## Notes
This is a solid starting scaffold for your project. You can extend it with payment integrations, invoice generation, image upload, and more advanced admin permissions.
