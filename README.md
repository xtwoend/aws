# Laravel Dashboard dengan Tabler.io Theme

Dashboard Laravel modern menggunakan Tabler.io theme yang diintegrasikan dengan Vite untuk performance yang optimal.

## 🚀 Features

- **Laravel 12.x** dengan authentication lengkap
- **AWS Logger Dashboard** untuk monitoring weather station data
- **Tabler.io dark theme** dengan SCSS architecture
- **Modular SCSS structure** untuk maintainability yang lebih baik
- **Theme toggle** untuk switch antara light/dark mode
- **Real-time data visualization** dengan charts dan graphs
- **Data export functionality** (CSV format)
- **Device monitoring** dan status tracking
- **Hot Module Replacement** untuk development cepat  
- **Bootstrap 5** components dengan dark mode support
- **Alpine.js** untuk interaktivitas
- **Custom Tabler utilities** dan icon system
- **Production-ready** build system dengan Sass compilation

## 🛠️ Installation & Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 4. Create Admin User
```bash
# Menggunakan seeder
php artisan db:seed --class=UserSeeder

# Atau menggunakan custom command
php artisan admin:create
```

## � SCSS Architecture

### File Structure
```
resources/css/
├── app.scss                    # Main entry point
├── _variables.scss            # Custom SCSS variables
├── themes/
│   ├── _dark.scss            # Dark theme styles
│   └── _light.scss           # Light theme styles
└── components/
    ├── _dashboard.scss       # Dashboard components
    ├── _theme-toggle.scss    # Theme toggle button
    └── _animations.scss      # Animations & transitions
```

### Customization
- **Variables**: Edit `_variables.scss` untuk color palette & spacing
- **Dark Theme**: Modify `themes/_dark.scss` untuk dark mode customization
- **Light Theme**: Modify `themes/_light.scss` untuk light mode customization
- **Components**: Add custom components di folder `components/`

## �🎯 Development

### Start Development Server
```bash
# Vite dev server saja
npm run dev

# Laravel server saja  
npm run serve

# Keduanya bersamaan (recommended)
npm run dev-all
```

### Build untuk Production
```bash
npm run build
```

### Dark Theme Features
- **Default**: Dark theme aktif secara default
- **Theme Toggle**: Klik tombol moon/sun di header untuk switch theme
- **Persistent**: Theme preference tersimpan di localStorage
- **Smooth Transitions**: Animasi halus saat switch theme

## 👤 Login Credentials

**Admin User:**
- Email: `admin@admin.com`
- Password: `admin123`

**Test User:**
- Email: `test@test.com`  
- Password: `password`

## 🌐 URLs

- **App**: http://127.0.0.1:8000
- **Login**: http://127.0.0.1:8000/login
- **Dashboard**: http://127.0.0.1:8000/dashboard
- **AWS Logger**: http://127.0.0.1:8000/aws-logger

## 📊 AWS Logger Dashboard

Dashboard untuk monitoring AWS (Automatic Weather Station) dengan fitur:

### Data Monitoring
- **Real-time weather data** dari multiple weather stations
- **Temperature, humidity, pressure** monitoring
- **Wind speed dan direction** tracking
- **Rainfall dan solar radiation** measurements
- **Device status** dan connectivity monitoring

### Visualizations  
- **Interactive charts** menggunakan ApexCharts
- **Temperature trends** over time
- **Wind patterns** dan speed analysis
- **Device comparison** dan performance metrics

### Data Management
- **CSV export** untuk analisis data
- **Historical data** dengan filtering
- **Device-specific views** dan detailed analysis
- **Summary statistics** dan aggregate data

Built with ❤️ using Laravel & Tabler.io via Vite.
