# NearU - Dormitory Listing Platform

A comprehensive Laravel 12 web application that connects students with nearby dormitory accommodations. NearU provides a seamless platform for dorm owners to list their properties and for students to find, compare, and book their ideal living space.

Built with modern web technologies and following Laravel best practices, NearU features a robust verification system, real-time messaging, and an intuitive admin dashboard.

## 📌 Project Overview

NearU is a full-featured dormitory management platform designed to simplify the process of finding and renting student accommodations. The platform serves three main user groups:

- **Students**: Search, compare, save, and inquire about dormitories
- **Owners**: List, manage, and verify their properties with comprehensive tools
- **Administrators**: Oversee platform operations, manage users, and generate reports

The application includes advanced features such as owner verification, real-time messaging, visit scheduling, reviews, and comprehensive analytics dashboards.

## 🚀 Key Features

### 🔐 Multi-Role Authentication System
- **Flexible Login**: Email or phone number authentication
- **Role-Based Access**: Students, Owners, and Administrators with dedicated dashboards
- **Secure Registration**: Email validation and password confirmation
- **Session Management**: Proper session regeneration and security

### 🏠 Owner Verification System
- **Two-Layer Verification**: User type + document verification status
- **Document Upload**: Government ID, proof of ownership, and property photos
- **Admin Review**: Comprehensive approval/rejection workflow with reasons
- **Status Tracking**: Not verified, under review, approved, or rejected states

### 📱 Modern User Interface
- **Mobile-First Design**: Fully responsive across all devices
- **Dark Mode Support**: Toggle between light and dark themes
- **Interactive Elements**: Touch-friendly components and smooth animations
- **Toast Notifications**: Real-time feedback for user actions

### 🔍 Advanced Search & Discovery
- **Smart Filtering**: Filter by price, location, amenities, and policies
- **Interactive Maps**: Geographic visualization of dormitory locations
- **Comparison Tool**: Side-by-side comparison of multiple properties
- **Save Listings**: Bookmark favorite dormitories for later review

### 💬 Real-Time Communication
- **Messaging System**: Direct communication between students and owners
- **Visit Scheduling**: Book and manage property visits
- **Notifications**: Real-time alerts for inquiries, bookings, and updates
- **Conversation History**: Complete message thread management

### ⭐ Reviews & Ratings
- **Student Reviews**: Rate and review dormitory experiences
- **Average Ratings**: Automatic calculation of property ratings
- **Review Moderation**: Admin oversight for content quality

### 📊 Analytics & Reporting
- **Owner Statistics**: Listing performance, views, and inquiry metrics
- **Admin Reports**: User activity, listing statistics, and platform analytics
- **Data Export**: CSV exports for detailed analysis
- **Visual Charts**: Interactive graphs and data visualization

## 🛠 Technologies Used

### Backend
- **Laravel 12**: Modern PHP framework with elegant syntax
- **PHP 8.2+**: Latest PHP version with performance improvements
- **Postgress**: Database for development and testing
- **Eloquent ORM**: Powerful database abstraction layer

### Frontend
- **Blade Templates**: Laravel's templating engine
- **TailwindCSS 4.0**: Utility-first CSS framework
- **Vite**: Fast build tool and development server
- **JavaScript ES6+**: Modern JavaScript features

### Additional Libraries
- **Intervention Image**: Image manipulation and processing
- **Axios**: HTTP client for API requests
- **Concurrently**: Run multiple development servers

## 📁 Project Structure

```
NearU-Laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # HTTP Controllers
│   │   │   ├── Admin/           # Admin-specific controllers
│   │   │   ├── AuthController.php
│   │   │   ├── DormController.php
│   │   │   ├── MessageController.php
│   │   │   └── ...
│   │   └── Middleware/          # Custom middleware
│   ├── Models/                  # Eloquent Models
│   │   ├── User.php
│   │   ├── DormListing.php
│   │   ├── Message.php
│   │   ├── Review.php
│   │   └── ...
│   └── Providers/               # Service Providers
├── database/
│   ├── migrations/              # Database schema migrations
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories
├── resources/
│   ├── views/                   # Blade templates
│   │   ├── admin/               # Admin interface views
│   │   ├── auth/                # Authentication views
│   │   ├── owner/               # Owner dashboard views
│   │   ├── student/             # Student interface views
│   │   └── layouts/             # Layout templates
│   ├── css/                     # Compiled CSS
│   └── js/                      # JavaScript files
├── routes/
│   ├── web.php                  # Web routes
│   └── console.php              # Console routes
├── storage/
│   ├── app/                     # Application files
│   └── public/                  # Public uploads
├── tests/                       # Test files
└── public/                      # Public web root
```

## 🗄️ Database Schema

### Core Tables

#### Users
- **Fields**: id, name, email, phone, user_type, password, profile_photo_path
- **Roles**: student, owner, admin
- **Verification**: verification_status, status
- **Timestamps**: created_at, updated_at

#### Dorm Listings
- **Fields**: owner_id, title, description, price, location details
- **Features**: amenities, furnishings, appliances, bills_included
- **Policies**: gender_policy, curfew, pets_allowed
- **Media**: Multiple images with cover photo support
- **Status**: Available, Unavailable, Pending

#### Messages & Conversations
- **Real-time messaging** between users
- **Conversation threading** for organized communication
- **Listing association** for context-aware messages

#### Reviews & Ratings
- **Student reviews** with star ratings
- **Detailed feedback** on dormitory experience
- **Moderation tools** for administrators

#### Visit Schedules
- **Appointment booking** system
- **Status tracking**: Pending, Confirmed, Cancelled
- **Calendar integration** for easy management

## 🎯 System Architecture

### Authentication Flow
1. User registration with email/phone validation
2. Role assignment (student/owner/admin)
3. Email verification (optional)
4. Owner document verification for property listing

### Owner Verification Process
1. Owner uploads verification documents
2. Admin reviews submitted documents
3. Approval/rejection with detailed feedback
4. Verified owners can create and manage listings

### Messaging System
- **Real-time notifications** for new messages
- **Conversation organization** by listing
- **Read/unread status tracking**
- **File attachment support**

### Search & Discovery
- **Advanced filtering** by multiple criteria
- **Geographic search** with map integration
- **Sorting options** by price, distance, rating
- **Saved listings** for user favorites

## 🔒 Security Features

- **CSRF Protection**: All forms protected against cross-site request forgery
- **Input Validation**: Comprehensive validation rules for all user inputs
- **File Upload Security**: Type and size restrictions for uploaded files
- **Role-Based Authorization**: Proper access control for different user types
- **Session Security**: Proper session regeneration and secure cookies
- **Password Hashing**: Bcrypt encryption for user passwords
- **SQL Injection Prevention**: Eloquent ORM parameterized queries

## 🧪 Testing

The application includes comprehensive test coverage:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter AuthenticationTest

# Run with coverage
php artisan test --coverage
```

### Test Categories
- **Feature Tests**: Complete user workflows and API endpoints
- **Unit Tests**: Individual model and service method testing
- **Browser Tests**: User interface interactions (if using Laravel Dusk)

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer 2.0 or higher
- Node.js 18+ and NPM
- SQLite or MySQL/PostgreSQL (for production)

### Quick Start

1. **Clone Repository**
```bash
git clone https://github.com/kelleeerrrr/NearU.git
cd NearU
```

2. **Install Dependencies**
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

3. **Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Setup**
```bash
# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

5. **Create Storage Link**
```bash
php artisan storage:link
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Development Server**
```bash
# Using Laravel's built-in server
php artisan serve

# Or use the development script
composer run dev
```

### Environment Configuration

Configure your `.env` file with the following settings:

```env
APP_NAME=NearU
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# For production use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nearu
# DB_USERNAME=username
# DB_PASSWORD=password
```

## 📊 API Endpoints

### Authentication
- `POST /login` - User authentication
- `POST /register` - User registration
- `POST /logout` - User logout

### Dorm Listings
- `GET /dorms` - List all dormitories
- `GET /dorms/{id}` - Show specific dormitory
- `POST /dorms` - Create new listing (verified owners only)
- `PUT /dorms/{id}` - Update listing (owners only)
- `DELETE /dorms/{id}` - Delete listing (owners only)

### Messages
- `GET /messages` - List user conversations
- `GET /messages/{listingId}/{userId}` - Show conversation
- `POST /messages/send/{listingId}/{userId}` - Send message

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User management
- `GET /admin/reports` - Platform analytics
- `POST /admin/settings` - System configuration

## 🎨 UI/UX Features

### Student Interface
- **Modern Homepage**: Featured listings and quick search
- **Advanced Search**: Comprehensive filtering options
- **Detailed Listings**: High-quality images and detailed information
- **Comparison Tool**: Side-by-side property comparison
- **Saved Listings**: Bookmark favorite properties
- **Messaging**: Direct communication with owners

### Owner Dashboard
- **Property Management**: Create, edit, and manage listings
- **Inbox**: Respond to student inquiries
- **Visit Scheduling**: Manage property visit requests
- **Analytics**: Track listing performance
- **Verification Status**: Monitor verification progress

### Admin Panel
- **User Management**: Oversee all platform users
- **Verification Review**: Process owner verification requests
- **System Settings**: Configure platform parameters
- **Reports & Analytics**: Comprehensive platform insights
- **Content Moderation**: Review and manage user-generated content

## 🌱 Project Significance

NearU addresses the common challenges students face when searching for accommodation:

- **Transparency**: Verified listings with detailed information
- **Convenience**: One-stop platform for all housing needs
- **Trust**: Owner verification and student reviews
- **Efficiency**: Direct communication and streamlined booking
- **Accessibility**: Mobile-first design for on-the-go access

The platform demonstrates modern web development practices including:
- **RESTful API design**
- **Responsive web development**
- **Database optimization**
- **Security best practices**
- **User experience optimization**

## 🚀 Future Enhancements

### Planned Features
- **Mobile Applications**: Native iOS and Android apps
- **Payment Integration**: Online rent payment processing
- **Advanced Analytics**: Machine learning for price recommendations
- **Multi-language Support**: International expansion
- **IoT Integration**: Smart dormitory features
- **Virtual Tours**: 360° property viewing

### Technical Improvements
- **API-First Architecture**: Separate frontend and backend
- **Microservices**: Modular service architecture
- **Real-time Features**: WebSocket implementation
- **Performance Optimization**: Caching and CDN integration
- **Enhanced Security**: Two-factor authentication

## 🤝 Contributing

We welcome contributions to the NearU platform! Please follow these guidelines:

1. **Fork the repository** and create a feature branch
2. **Follow PSR-12 coding standards**
3. **Write tests** for new features
4. **Update documentation** as needed
5. **Submit pull requests** with clear descriptions

### Development Guidelines
- Use meaningful commit messages
- Follow Laravel conventions
- Write clean, readable code
- Include appropriate error handling
- Test thoroughly before submitting

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Team** for the excellent framework
- **TailwindCSS** for the utility-first CSS framework
- **OpenAI** for assistance in development and documentation
- **Contributors** who help improve the platform

## 📞 Support

For support, questions, or contributions:

- **GitHub Issues**: Report bugs and request features
- **Email**: [your-email@example.com]
- **Documentation**: Check the `/docs` directory for detailed guides

---

**NearU** - Making student housing search simple, secure, and efficient. 🏠✨

