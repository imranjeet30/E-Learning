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



🏗️ Project Structure
app/
 ├── Http/
 │    ├── Controllers/   # API controllers
 │    ├── Requests/      # Form requests (validation)
 │
 ├── Models/             # Eloquent Models
 │
 ├── Repositories/       # Data access layer
 │
 ├── Services/           # Business logic layer
 │
 └── Providers/
database/
 ├── migrations/         # Tables (users, courses, subscriptions, payments)
 ├── seeders/            # Dummy data
routes/
 ├── api.php             # API routes