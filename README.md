# BookWave 📚

BookWave is a modern, responsive e-commerce application designed for selling books online. Built with a custom PHP MVC framework, it provides a seamless shopping experience for users and powerful management tools for administrators.

## 🚀 Features

### Front-End (Customer)

- **Shop & Browse:** View all books with pagination or search for specific titles.
- **Shopping Cart:** Add items, update quantities, and verify totals instantly.
- **Secure Checkout:** Validate shipping details and place orders seamlessly.
- **User Accounts:** Registration, Login, and personalized "My Orders" history.
- **Responsive Design:** Fully optimized for desktop, tablet, and mobile devices.

### Admin Dashboard

- **Dashboard Overview:** Real-time stats on products, orders, users, and messages.
- **Order Management:**
  - View all orders.
  - Update order statuses (Pending, Completed, Cancelled).
  - Delete orders.
  - **User Order History:** View aggregate orders for specific users.
- **Product Management:** Add, edit, and remove books.
- **User Management:** detailed list of registered users.
- **Messages:** Read and manage contact form inquiries.

## 🛠️ Technology Stack

- **Backend:** PHP (Custom MVC Architecture)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Styling:** Custom CSS + FontAwesome Icons

## ⚙️ Installation

1.  **Clone the repository** to your local server (e.g., Laragon/XAMPP `www` or `htdocs` folder).
2.  **Import Database:**
    - Create a MySQL database named `shop_db`.
    - Import the `shop_db.sql` file located in the root directory.
3.  **Configure Database:**
    - Open `config/database.php`.
    - Ensure the `BASE_URL` matches your local setup.
    - Update DB credentials if necessary.
4.  **Run:**
    - Access the site via your browser (e.g., `http://bookwave.test/public` or `http://localhost/bookwave/public`).

## 📁 Project Structure

```
bookwave/
├── app/
│   ├── Controllers/  # Logic (Admin & Public)
│   ├── Models/       # Database interactions
│   └── Views/        # HTML Templates
├── config/           # Database configuration
├── core/             # Framework core (App, Controller, Model)
├── public/           # Assets (CSS, JS, Images)
└── shop_db.sql       # Database Schema
```

## 🔐 Credentials

- **Admin Login:** (Check `users` table with `user_type = 'admin'`)
- **User Login:** Register a new account to test user features.
