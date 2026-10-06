# shopAmar

A responsive multi-role e-commerce website built with PHP, MySQL, Bootstrap, and multilingual support for Bangla and English.

## Features
- Visitor, user, reseller, and admin panels
- Bangla + English language toggle
- Responsive mobile + desktop layout
- Product, category, order, coupon, and role management
- Basic authentication and role-based access control
- MySQL database schema for a starter e-commerce app

## Tech Stack
- PHP 8+
- MySQL
- Bootstrap 5
- HTML/CSS/JavaScript

## Folder Structure
- `config/` – app settings and database configuration
- `includes/` – shared layouts and helpers
- `public/` – customer-facing pages
- `admin/` – admin dashboard pages
- `assets/` – CSS and JS files
- `db/` – SQL schema and seed data

## Setup
1. Create a MySQL database named `shopamar`.
2. Import `db/schema.sql`.
3. Update the database credentials in `config/database.php` if needed.
4. Run the app from a PHP-enabled web server.

## Default Admin
- Email: `admin@shopamar.com`
- Password: `admin123`

## Notes
This is a working starter project that matches your requested architecture and can be extended with payment gateways, invoice PDF generation, reseller commission logic, and real admin CRUD features.
