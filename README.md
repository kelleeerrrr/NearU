<p align="center">
  <img src="public/logo/nearu-logo.png" alt="NearU Logo" width="150" />
</p>

<h1 align="center"> 
  Student Housing Discovery & Management Platform
</h1>

<p align="center">
  <b>Helping students find safe, affordable, and convenient housing near their university.</b><br>
  Built with <b>Laravel 12</b>, <b>TailwindCSS</b>, and <b>PostgreSQL</b>.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-v12-FF2D20?logo=laravel" alt="Laravel Badge" />
  <img src="https://img.shields.io/badge/TailwindCSS-v4-38BDF8?logo=tailwindcss" alt="TailwindCSS Badge" />
  <img src="https://img.shields.io/badge/PostgreSQL-Enabled-336791?logo=postgresql" alt="PostgreSQL Badge" />
  <img src="https://img.shields.io/badge/Status-Active-brightgreen" alt="Status Badge" />
</p>

---

# 🎯 Overview

**NearU** is a web-based **Student Housing Discovery and Management System** designed to simplify the process of finding and managing dormitory accommodations near universities and colleges.

The platform connects:

- 🎓 **Students** looking for nearby housing
- 🏠 **Dorm Owners** managing rental properties
- 🛡️ **Administrators** overseeing platform operations

NearU provides a centralized platform where students can browse listings, compare dormitories, communicate directly with owners, and schedule visits — all in one system.

For dorm owners, the system offers listing management, verification tools, inquiry handling, and analytics dashboards to improve property visibility and engagement.

> 💡 *NearU aims to make student housing discovery safer, easier, and more accessible through verified listings and streamlined communication.*

---

# 🎯 Project Objectives

### Main Objective
To develop a centralized dormitory listing and management platform that helps students easily discover and connect with nearby housing providers.

### Specific Objectives
- Provide students with an easy-to-use dormitory search and comparison system.
- Allow property owners to manage listings and inquiries efficiently.
- Implement a verification process to improve trust and listing credibility.
- Streamline communication between students and dorm owners.
- Deliver a responsive and accessible platform across devices.

---

# 🌍 Sustainable Development Goals (SDGs) Supported

| SDG | Badge | Description |
| :-- | :---- | :----------- |
| 🏭 **SDG 9 – Industry, Innovation, and Infrastructure** | ![SDG9](https://img.shields.io/badge/SDG9-Innovation-5A84F7) | Promotes digital innovation through a modern housing discovery platform. |
| ⚖️ **SDG 10 – Reduced Inequalities** | ![SDG10](https://img.shields.io/badge/SDG10-Reduced%20Inequalities-FA485D) | Helps students access affordable and verified accommodations regardless of background. |
| 🏙️ **SDG 11 – Sustainable Cities and Communities** | ![SDG11](https://img.shields.io/badge/SDG11-Sustainable%20Communities-FFB300) | Supports safer and more organized student housing communities. |

---

# 🗓️ Project Timeline

| Month | Milestone |
| :---- | :--------- |
| **February 2026** | Project proposal and planning |
| **March 2026** | UI/UX design and database planning |
| **April 2026** | Core system development and Feature integration |
| **May 2026** | Final revisions and testing |

---

# ✅ Feature Implementation Status

| Feature | Description | Status |
| :------ | :----------- | :----: |
| **Authentication System** | Multi-role login and registration | 🟢 Completed |
| **Student Dashboard** | Search, save, and compare dormitories | 🟢 Completed |
| **Owner Dashboard** | Manage listings and inquiries | 🟢 Completed |
| **Dormitory Listings** | Create and display property listings | 🟢 Completed |
| **Owner Verification** | Document submission and admin approval | 🟢 Completed |
| **Messaging System** | Communication between students and owners | 🟢 Completed |
| **Visit Scheduling** | Schedule dormitory visits | 🟢 Completed |
| **Search & Filtering** | Search by location, price, and amenities | 🟢 Completed |
| **Reviews & Ratings** | Student feedback and ratings | 🟢 Completed |
| **Admin Dashboard** | User management and analytics | 🟢 Completed |
| **Notification System** | Real-time alerts and updates | 🟢 Completed |
| **Map Integration** | Interactive dormitory location maps | 🟢 Completed |
| **Mobile Optimization** | Improved mobile responsiveness | 🟢 Completed |

---

# 🚀 Key Features

## 🔐 Multi-Role Authentication
- Student, Owner, and Admin access
- Email or phone-based login
- Secure password hashing and validation
- Session-based authentication

## 🏠 Dormitory Listing Management
- Create and manage dormitory listings
- Upload multiple dorm images
- Set amenities, policies, and pricing
- Manage availability status

## 🔍 Smart Search & Discovery
- Search dormitories by location
- Filter by price, amenities, and policies
- Save and compare listings
- Responsive listing interface

## 💬 Messaging & Communication
- Direct student-owner messaging
- Inquiry management
- Visit scheduling system
- Conversation history tracking

## 🛡️ Owner Verification System
- Document upload and verification
- Admin review workflow
- Verification status tracking
- Improved listing credibility

## 📊 Dashboard & Analytics
- Listing performance insights
- Inquiry statistics
- Admin monitoring dashboard
- Platform activity tracking

---

# 🛠 Technologies Used

## Backend
- **Laravel 12**
- **PHP 8.2+**
- **PostgreSQL**
- **Eloquent ORM**

## Frontend
- **Blade Templates**
- **TailwindCSS 4**
- **JavaScript ES6+**
- **Vite**

## Additional Libraries
- **Axios**
- **Intervention Image**
- **Chart.js**

---

# ⚙️ How to Run the Project

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/kelleeerrrr/NearU.git
cd NearU
```

## 2️⃣ Install Dependencies

```bash
composer install
npm install
```

## 3️⃣ Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

## 4️⃣ Setup Database

```bash
php artisan migrate
php artisan db:seed
```

## 5️⃣ Create Storage Link

```bash
php artisan storage:link
```

## 6️⃣ Run the Application

```bash
npm run dev
php artisan serve
```

> ⚡ The application should now be running at `http://127.0.0.1:8000`

---

# 📌 Notes / Recommendations

- Ensure **PHP 8.2+** and **Node.js 18+** are installed.
- PostgreSQL is recommended for production deployment.
- Run `php artisan storage:link` to properly display uploaded images.
- Some admin features may require seeded administrator accounts.

---

# 🌱 Project Significance

NearU addresses common student housing challenges by providing:

- ✅ Verified and trustworthy dormitory listings
- ✅ Faster communication between students and owners
- ✅ Easier housing discovery and comparison
- ✅ Centralized management for dormitory owners
- ✅ Improved accessibility through responsive design

The platform promotes safer and more efficient student housing discovery through modern web technologies and user-centered design.

---

# 🚀 Future Enhancements

## Planned Features
- 📱 Mobile application support
- 💳 Online payment integration
- 🗺️ Interactive map navigation
- 🔔 Real-time push notifications
- 🌐 Multi-language support
- 📸 Virtual dormitory tours

## Technical Improvements
- API-first architecture
- WebSocket real-time messaging
- Performance optimization
- Enhanced analytics system
- Two-factor authentication

---

# 👥 Developers

| Name | GitHub |
| :--- | :----- |
| **Rivera, Irish** | https://github.com/kelleeerrrr |
| **Rectin, Marielle** | https://github.com/onlymarkive |

---

# 🧑‍🏫 Instructor & Course Information

| Detail | Information |
| :----- | :---------- |
| **Course** | CS 322 –  Software Engineering |
| **Instructor** | Mr. Dominic Miko G. Valdez |
| **Academic Term** | 2nd Semester, A.Y. 2025–2026 |

---

<p align="center">
  💡 <b>
    Simplifying Student Housing Discovery with NearU
  </b>
</p>

