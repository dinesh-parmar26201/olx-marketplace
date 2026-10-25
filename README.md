# 🛒 OLX Marketplace Clone

A modern, high-performance classifieds marketplace web application built with **Laravel 12 (PHP 8.2+)**, **MySQL**, and **Bootstrap 5.3**. Inspired by OLX India, it allows users to register, post ads for both products and services, search, filter, and discover deals locally across cities and categories.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Vercel](https://img.shields.io/badge/Vercel-Deployment-000000?style=for-the-badge&logo=vercel&logoColor=white)](https://vercel.com)

---

## 🌟 Key Features

### 👤 1. User Authentication & Profile
- User registration with name, email, phone number, city, and password.
- Secure login / logout with remember me and session regeneration.
- Personal User Dashboard:
  - Total ads count, active ads, draft/inactive ads, and views tracking.
  - "My Ads" management: edit ads, toggle active/inactive status, or delete listings.

### 📦 2. Add Products & Services (Post Ad)
- Comprehensive ad posting form supporting both **Products** and **Services**:
  - **Type**: Product vs. Service.
  - **Title & Details**: Rich description with validation.
  - **Category & Subcategory**: Dynamic AJAX cascading subcategory selector.
  - **Location**: Country (India), State (e.g. Maharashtra, Karnataka, Delhi, Gujarat), City (Mumbai, Bangalore, etc.), and Area (Bandra, Koramangala, etc.).
  - **Price**: Numeric price with type (Fixed, Negotiable, or Free).
  - **Condition**: New, Used, or Not Applicable (for services).
  - **Images**: Multiple image uploads with automatic thumbnail generation.
  - **Contact**: Verified phone number with optional privacy toggle ("Hide phone number").
- **Instant Frontend Live**: Upon submission, the ad is immediately published and redirects to its public live page.

### 🔍 3. Public Marketplace Pages
- **Homepage (`/`)**:
  - Hero banner with quick popular city pills and live marketplace snapshot.
  - Category ribbon with icon navigation for all parent categories.
  - Handpicked Featured Listings carousel.
  - Fresh Recommendations grid with instant badges (Product / Service, Featured, Location, Timestamp).
  - Hyper-local deals by top cities (Mumbai, Delhi, Bangalore, Ahmedabad, Pune, etc.).
- **Category-Wise Listing (`/category/{category_slug}/{subcategory_slug?}`)**:
  - Browse ads by parent category or specific subcategories.
  - Sidebar filters by Ad Type, City, Price range, and Condition.
- **City-Wise Listing (`/city/{city_slug}`)**:
  - Dedicated city landing page with active city count, city banner, and local ads.
  - Direct quick-links to browse specific categories inside that city.
- **City + Category Combined Listing (`/city/{city_slug}/category/{category_slug}`)**:
  - Hyper-local filtering (e.g., *Cars in Mumbai*, *Electronics in Bangalore*, *Services in Pune*).
  - Filter further by subcategory, price range, and sort order.
- **Single Listing Detail Page (`/listing/{slug}`)**:
  - Image gallery with main photo and thumbnails.
  - Price, condition, post timestamp, location badge.
  - "Click to Show Phone Number" interactive seller contact.
  - Seller profile card and security safety tips.
  - Related listings in the same city/category.

---

## 🗄️ Database Architecture

The application uses an optimized relational schema in MySQL:

| Table | Description |
|---|---|
| `users` | User accounts with authentication credentials, phone, avatar, and city. |
| `categories` | Hierarchical categories with parent-child relationship (self-referencing `parent_id`), slug, and FontAwesome icon. |
| `listings` | Core table containing products & services, prices, condition, state, city, city_slug, area, views counter, and status. |
| `listing_images` | Multiple images per listing with primary flag and sorting order. |
| `sessions`, `jobs`, `cache` | Laravel infrastructure tables for database sessions, background jobs, and caching. |

---

## 🚀 Local Development Setup

### Prerequisites
- PHP 8.2 or higher (with `pdo_mysql`, `pdo_sqlite`, `mbstring`, `fileinfo`, `gd`, `openssl` extensions enabled)
- MySQL 8.0+ (via XAMPP, WAMP, or standalone)
- Composer 2.x
- Node.js 18+ & npm (optional for Vite asset compiling)

### Step 1: Clone the Repository
```bash
git clone https://github.com/dinesh-parmar26201/olx-marketplace.git
cd olx-marketplace
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Configure Environment
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```
Update your MySQL database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations & Seed Sample Data
Ensure MySQL is running and database `marketplace_db` exists, then run:
```bash
php artisan migrate --seed
```
This populates:
- 4 demo verified users.
- 8 main categories and 30+ subcategories with icons.
- 12+ real-world product and service ads across Mumbai, Bangalore, Delhi, Ahmedabad, and Pune.

#### Demo User Credentials:
- **Email:** `rahul@example.com` | **Password:** `password123`
- **Email:** `priya@example.com` | **Password:** `password123`
- **Email:** `vikram@example.com` | **Password:** `password123`
- **Email:** `ananya@example.com` | **Password:** `password123`

### Step 5: Link Storage
```bash
php artisan storage:link
```

### Step 6: Start Development Server
```bash
php artisan serve
```
Visit **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.

---

## 🧪 Running Automated Tests

A comprehensive end-to-end feature test suite is included in `tests/Feature/MarketplaceFlowTest.php`:
- Homepage response & essential sections verification.
- User registration and database persistence.
- Product posting with images, category assignment, and frontend visibility across:
  - Single listing page (`/listing/{slug}`)
  - Category page (`/category/{slug}`)
  - City page (`/city/{city_slug}`)
  - City + Category page (`/city/{city_slug}/category/{category_slug}`)
- Service ad posting and validation.

To run tests:
```bash
php artisan test --filter=MarketplaceFlowTest
```
All 4 tests and 23 assertions run against an isolated in-memory SQLite database without modifying your development database.

---

## ☁️ Deployment to Vercel

The project is pre-configured for one-click or CLI deployment to **Vercel** via serverless PHP runtime (`vercel-php@0.7.2`).

### Files Included for Vercel:
- `vercel.json`: Configures routes, static asset caching, serverless function timeouts, and redirects `/` requests to `api/index.php`.
- `api/index.php`: Serverless entry point ensuring writable directories in `/tmp` for views, cache, and sessions.
- `.vercelignore`: Ignores non-production development artifacts.

### Deployment via Vercel CLI:
1. Install Vercel CLI (if not already installed):
   ```bash
   npm i -g vercel
   ```
2. Login to your Vercel account:
   ```bash
   vercel login
   ```
3. Deploy the project:
   ```bash
   vercel --prod
   ```
4. Set your production environment variables in your Vercel dashboard:
   - `APP_KEY`: Your generated `base64:...` app key.
   - `APP_URL`: Your Vercel production URL (e.g. `https://olx-marketplace.vercel.app`).
   - `DB_CONNECTION`: `mysql` (use a cloud MySQL database such as PlanetScale, Aiven, or Supabase PostgreSQL with `pgsql`).
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

---

## 📂 Project Structure

```
marketplace/
├── api/
│   └── index.php                  # Vercel serverless entry point
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # User register, login, logout
│   │   ├── DashboardController.php # My Ads, ad status toggle, user stats
│   │   ├── HomeController.php      # Homepage recommendations, stats, cities
│   │   └── ListingController.php   # Index, show, create, store, edit, delete, city & category routes
│   └── Models/
│       ├── Category.php            # Category model with parent/child relationships
│       ├── Listing.php             # Listing model with scopes, accessors, formatters
│       ├── ListingImage.php        # Listing image relationship & URL accessors
│       └── User.php                # Authenticatable User model
├── database/
│   ├── migrations/                 # Schema migrations for users, categories, listings, images
│   └── seeders/                    # Realistic marketplace seeders
├── resources/
│   └── views/
│       ├── auth/                   # Register & Login forms
│       ├── dashboard/              # User dashboard & My Ads view
│       ├── layouts/
│       │   └── app.blade.php       # Master layout with navbar, category ribbon, footer
│       ├── listings/
│       │   ├── card.blade.php      # Reusable listing card component
│       │   ├── category.blade.php  # Category wise listing view
│       │   ├── city.blade.php      # City wise listing view
│       │   ├── city_category.blade.php # City + Category combination view
│       │   ├── create.blade.php    # Post Product / Service form
│       │   ├── edit.blade.php      # Edit ad form
│       │   ├── index.blade.php     # Search & browse all listings
│       │   ├── show.blade.php      # Single listing detail page
│       │   └── sidebar-filter.blade.php # Reusable faceted filter sidebar
│       └── home.blade.php          # Homepage
├── routes/
│   └── web.php                     # Clean, semantic RESTful routes
├── tests/
│   └── Feature/
│       └── MarketplaceFlowTest.php # Comprehensive test suite
├── vercel.json                     # Vercel serverless configuration
└── README.md                       # Documentation
```

---

## 📄 License
This project is open-source software licensed under the [MIT license](LICENSE).
