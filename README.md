# shopAmar

A PHP + MySQL + Bootstrap e-commerce application with:
- Bangla + English language support
- Responsive mobile + desktop design
- Visitor, User, Reseller, and Admin roles
- Admin management modules for products, categories, orders, coupons, and roles
- Invoice generation for user orders
- Payment gateway configuration placeholders for COD, Bkash, Nagad, Rocket, and SSLCommerz

## Tech Stack
- PHP
- MySQL
- Bootstrap 5
- HTML/CSS/JavaScript

## Project Setup
1. Create a MySQL database named `shopamar`.
2. Import `database/shopamar.sql`.
3. Update database credentials in `config/config.php`.
4. Serve the project using Apache, Nginx, or PHP built-in server.

## Main Routes
- `/public/index.php`
- `/public/login.php`
- `/public/register.php`
- `/public/products.php`
- `/public/cart.php`
- `/public/checkout.php`
- `/public/invoice.php?id=1`
- `/admin/dashboard.php`
- `/user/dashboard.php`
- `/reseller/dashboard.php`

## Payment Note
This phase includes payment method integration placeholders for COD and gateway-ready configuration in `config/payment.php`.

## Notes
This is a starter implementation for the requested multi-role ecommerce platform.
