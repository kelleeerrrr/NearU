# NearU - Dorm Listing Platform

A Laravel 12 application for connecting students with dorm listings, featuring a two-layer owner verification system.

## 📁 Project Structure

### Application Structure (`app/`)

```
app/
├── Http/
│   ├── Controllers/           # HTTP Controllers
│   │   ├── AuthController.php
│   │   ├── Controller.php
│   │   ├── DormController.php
│   │   ├── HomeController.php
│   │   ├── MessageController.php
│   │   ├── OwnerVerificationController.php
│   │   └── UserController.php
│   ├── Middleware/            # Custom Middleware
│   │   └── EnsureOwnerIsVerified.php
│   └── Requests/              # Form Request Classes
│       └── StoreOwnerVerificationRequest.php
├── Models/                    # Eloquent Models
│   ├── User.php
│   ├── DormListing.php
│   ├── Message.php
│   ├── OwnerVerification.php
│   ├── SavedListing.php
│   └── VisitSchedule.php
├── Providers/                 # Service Providers
│   └── AppServiceProvider.php
└── Services/                  # Business Logic Services
    └── FileUploadService.php
```

### Routes (`routes/`)

```
routes/
├── web.php                    # Web routes
└── console.php               # Console routes
```

### Views (`resources/views/`)

```
resources/views/
├── layouts/
│   └── app.blade.php         # Main layout
├── auth/                     # Authentication views
│   ├── login.blade.php
│   └── register.blade.php
├── dorms/                    # Dorm listing views
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── compare.blade.php
│   └── map.blade.php
├── owner/                    # Owner-specific views
│   └── dashboard.blade.php
├── owner-verification/       # Verification views
│   └── form.blade.php
├── admin/                    # Admin views
│   └── owner-verifications/
│       ├── index.blade.php
│       └── review.blade.php
├── messages/                 # Messaging views
│   ├── index.blade.php
│   └── show.blade.php
├── home.blade.php           # Home page
├── profile.blade.php        # User profile
├── saved.blade.php          # Saved listings
├── visits.blade.php         # Visit schedules
└── welcome.blade.php        # Welcome page
```

### Database (`database/`)

```
database/
├── factories/                # Model Factories
├── migrations/              # Database Migrations
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_03_09_114859_create_dorm_listings_table.php
│   ├── 2026_03_09_114904_create_messages_table.php
│   ├── 2026_03_09_114904_create_saved_listings_table.php
│   ├── 2026_03_09_114905_create_visit_schedules_table.php
│   ├── 2026_04_29_053746_add_profile_photo_path_to_users.php
│   └── 2026_05_01_000000_create_owner_verifications_table.php
├── seeders/                 # Database Seeders
│   └── DatabaseSeeder.php
└── database.sqlite         # SQLite Database
```

### Tests (`tests/`)

```
tests/
├── Feature/                  # Feature Tests
│   ├── AuthenticationTest.php
│   └── ExampleTest.php
└── Unit/                     # Unit Tests
    └── ExampleTest.php
```

## 🚀 Key Features

### 🔐 Authentication System
- **Multi-field Login**: Email or phone number
- **Role-based Redirects**: Students → `/home`, Owners → `/owner/dashboard`
- **Registration**: No auto-login, redirects to login with success message
- **Session Management**: Proper regeneration and invalidation

### 🏠 Owner Verification System
- **Two-layer System**: User type + verification status
- **Document Upload**: 6 required documents with validation
- **Admin Review**: Approve/reject with reasons
- **Status Tracking**: Pending, approved, rejected states

### 📱 Mobile-First UI
- **Responsive Design**: Optimized for mobile devices
- **Toast Notifications**: Session-based success/error messages
- **Dark Mode Support**: Toggle between light/dark themes
- **Touch-Friendly**: Optimized for touch interactions

## 🛠 Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (or configure other database)

### Installation
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Build assets
npm run build
```

### Development
```bash
# Start development servers
composer run dev
```

### Testing
```bash
# Run tests
php artisan test

# Run specific test
php artisan test tests/Feature/AuthenticationTest.php
```

## 📊 Database Schema

### Users Table
- `id`, `name`, `email`, `phone`, `user_type`, `password`
- `profile_photo_path`, `email_verified_at`
- `created_at`, `updated_at`

### Owner Verifications Table
- `user_id` (foreign key)
- Document paths: `government_id_path`, `selfie_with_id_path`, etc.
- `status` (pending/approved/rejected)
- `rejection_reason`, `verified_by`, `verified_at`

### Dorm Listings Table
- Owner relationship, location data, pricing, amenities
- Photo arrays, status management

## 🔒 Security Features

- **CSRF Protection**: All forms protected
- **Input Validation**: Comprehensive validation rules
- **File Upload Security**: Type and size restrictions
- **Authorization**: Role-based access control
- **Session Security**: Proper regeneration

## 🎯 Code Quality

- **PSR-4 Autoloading**: Standard namespace structure
- **Laravel Conventions**: Follows framework best practices
- **Service Layer**: Business logic separated from controllers
- **Form Requests**: Validation logic in dedicated classes
- **Comprehensive Tests**: Feature and unit test coverage

## 📝 API Endpoints

### Authentication
- `GET /login` - Show login form
- `POST /login` - Process login
- `GET /register` - Show registration form
- `POST /register` - Process registration
- `POST /logout` - Logout user

### Owner Verification
- `GET /owner/verification` - Show verification form
- `POST /owner/verification` - Submit documents
- `GET /api/owner-verification/status/{userId}` - Get status

### Protected Routes (Verified Owners Only)
- `GET /dorms/create` - Create listing form
- `POST /dorms` - Store new listing
- `GET /owner/dashboard` - Owner dashboard

## 🤝 Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Use meaningful commit messages

## 📄 License

This project is licensed under the MIT License.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
