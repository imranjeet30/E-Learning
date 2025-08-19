"# E-Learning" 
###########***************** API *****************************###################

📚 E-learning Platform

It is an E-learning platform , following modern software design principles (Repository Pattern, Service Layer, Dependency Injection, SOLID).
It allows user registration, course subscriptions, and multiple payment gateways (Stripe, PayPal, Razorpay).

🚀 Features

User Authentication (Login/Register)

Courses (CRUD operations)

Subscription Module with Payment Integration (Stripe, PayPal, Razorpay)

Repository & Service Pattern for clean architecture

API-first approach (REST API for mobile/web clients)

Seeder support for dummy data (users, courses, subscriptions)

Extensible for adding new payment providers



🏗️backend/ # Laravel Backend (API)
├── app/
│ ├── Http/
│ │ ├── Controllers/ # API controllers
│ │ ├── Requests/ # Form requests (validation)
│ │
│ ├── Models/ # Eloquent Models
│ ├── Repositories/ # Data access layer
│ ├── Services/ # Business logic layer
│ └── Providers/
│
├── database/
│ ├── migrations/ # Tables (users, courses, subscriptions, payments)
│ ├── seeders/ # Dummy data
│
├── routes/
│ └── api.php # API routes



###########***************** FRONTEND *****************************###################

# 🎓 E-Learning Platform

A modern E-Learning platform built with **Laravel (API)**, **Vue 3**, **Pinia**, and **Tailwind CSS**.  
Supports user authentication, course management, subscriptions, and payments (Stripe/Razorpay ready).

---

## 🚀 Features

- User Authentication (Register / Login / Logout)
- Role-based Access (Admin, Instructor, Student)
- Course Management (CRUD for admins/instructors)
- Course Enrollment & Subscription
- Payment Integration (Stripe / Razorpay)
- My Subscriptions Dashboard
- REST API (Laravel) + SPA (Vue 3)

---

## 🛠️ Tech Stack

- **Frontend:** Vue 3, Pinia, Vue Router, Tailwind CSS, Axios
- **Backend:** Laravel 11 (REST API)
- **Database:** MySQL
- **Payments:** Stripe (default) / Razorpay (optional)

---

## 📂 Project Structure

frontend/ # Vue 3 Frontend
├── src/
│ ├── assets/ # Images, icons, etc.
│ ├── components/ # Vue components (Navbar, Footer, etc.)
│ ├── pages/ # Views (CoursesPage, LoginPage, etc.)
│ ├── store/ # Pinia stores (userStore, courseStore)
│ ├── router/ # Vue Router (routes.js)
│ ├── App.vue
│ └── main.js
│
├── package.json
└── vite.config.js