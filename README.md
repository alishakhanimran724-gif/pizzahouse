# 🍕 Pizza House — Full-Stack Pizza Ordering Web App

> A modern, fully responsive pizza ordering platform built from scratch with PHP, MySQL & Vanilla JS — no frameworks, no shortcuts.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## 🌐 Live Demo

> 🔗 [View Live Demo](your-live-link-here)

---

## 📸 Screenshots

| Home Page | Menu Page | Cart Page |
|-----------|-----------|-----------|
| ![Home](screenshots/home.png) | ![Menu](screenshots/menu.png) | ![Cart](screenshots/cart.png) |

---

## ✨ Features

- 🍕 **Dynamic Menu** — Category filters, veg/non-veg toggle, real-time product count
- 🛒 **Smart Cart** — AJAX-powered add/remove/update, zero page reloads
- ❤️ **Wishlist System** — Save favourites, toggle with live badge sync
- 🔐 **User Auth** — Signup, Login, Session management with secure password hashing
- 💰 **Order System** — GST (5%) calculation, free delivery above ₹499, order placement
- 📦 **Product Pages** — Size selection, quantity control, live price calculation
- 📱 **Fully Responsive** — Mobile-first design, hamburger nav, works on all screen sizes
- 🎨 **Polished UI** — Scroll-reveal animations, sticky header, promo banner, floating chips
- 📬 **Contact Form** — Message submission + callback request feature

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP (vanilla, front-controller pattern) |
| Database | MySQL |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Icons | Font Awesome 6 |
| Images | Unsplash API |
| Architecture | MVC-inspired, single entry-point router |

---

## 📁 Project Structure

```
pizza-house/
│
├── index.php              # Front controller / router
├── config/
│   └── db.php             # Database connection
│
├── pages/
│   ├── home.php           # Landing page
│   ├── menu.php           # Full menu with filters
│   ├── product.php        # Product detail + add to cart
│   ├── cart.php           # Cart + order placement
│   ├── wishlist.php       # Saved items
│   ├── login.php          # User login
│   ├── signup.php         # User registration
│   ├── about.php          # About page
│   └── contact.php        # Contact + FAQ
│
├── includes/
│   ├── header.php         # Navbar + promo bar
│   └── footer.php         # Footer + newsletter
│
└── public/
    ├── css/
    │   └── style.css      # Global styles
    └── js/
        └── main.js        # Global scripts
```

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.0+
- MySQL 5.7+
- Apache / Nginx (or XAMPP / WAMP locally)

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/your-username/pizza-house.git
cd pizza-house
```

**2. Create the database**
```bash
mysql -u root -p
CREATE DATABASE pizza_house;
```

**3. Import the SQL file**
```bash
mysql -u root -p pizza_house < database/pizza_house.sql
```

**4. Configure database connection**
```php
// config/db.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'pizza_house');
```

**5. Start your local server**
```bash
# If using XAMPP — place project in /htdocs and visit:
http://localhost/pizza-house
```

---

## 🗄️ Database Tables

| Table | Description |
|-------|-------------|
| `users` | Registered user accounts |
| `products` | Pizza & side dish catalog |
| `product_sizes` | Size variants with pricing |
| `cart` | Session-based cart items |
| `wishlist` | User saved products |
| `orders` | Placed orders |
| `order_items` | Items within each order |
| `contacts` | Contact form submissions |

---

## 🚀 Roadmap

- [ ] Razorpay / Stripe payment gateway
- [ ] Admin dashboard (orders, products, users)
- [ ] Real-time order tracking
- [ ] Email confirmation on order placement
- [ ] PWA support for mobile install
- [ ] Coupon & promo code system
- [ ] Product reviews & ratings

---

## 💡 What I Learned

- Building a **front-controller router** in pure PHP
- **AJAX-powered** cart & wishlist without any library
- Handling **session-based state** across multiple pages
- Real-world pricing logic — GST, conditional delivery fees
- Writing **clean, reusable** PHP partials (header, footer)
- Designing a **mobile-first** UI from scratch

---

## 🤝 Contributing

Contributions, issues and feature requests are welcome!

1. Fork the project
2. Create your branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👨‍💻 Author

**Your Name**
- LinkedIn: https://www.linkedin.com/in/alisha-imran-a30601322/
- GitHub:https://github.com/alishakhanimran724-gif/
- Email: alishakhanimran724@gmail.com

---

> ⭐ If you found this project helpful or interesting, please consider giving it a **star** — it means a lot and keeps me motivated to build more! 🙏
