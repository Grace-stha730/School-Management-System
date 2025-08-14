# 🏫 School Management System

A comprehensive web-based School Management System built with Laravel 11.x and Bootstrap 5.x. This system provides complete functionality for managing academic institutions, including student management, teacher administration, course management, examination systems, fee management, and much more.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🚀 Features

### 👨‍💼 Admin Features
- **User Management**: Complete user registration, roles, and permissions
- **Academic Management**: School sessions, classes, sections, semesters
- **Student Management**: Student profiles, academic info, parent details
- **Faculty Management**: Teacher profiles and course assignments
- **Course Management**: Course creation, syllabus, and curriculum
- **Examination System**: Exam creation, grading rules, mark calculation
- **Fee Management**: Fee structures, payment tracking, discounts
- **Notice Board**: Announcements and communication system
- **Reports & Analytics**: Comprehensive reporting dashboard

### 👨‍🏫 Teacher Features
- **Course Management**: View assigned courses and syllabi
- **Student Management**: Access to assigned student lists
- **Examination**: Create exams, set rules, and enter marks
- **Assignment Management**: Create and distribute assignments
- **Grade Management**: Submit final marks and generate reports
- **Attendance Tracking**: Take and view student attendance
- **Academic Resources**: Upload and share course materials

### 👨‍🎓 Student Features
- **Personal Dashboard**: Academic overview and progress tracking
- **Course Access**: View enrolled courses and materials
- **Assignment Portal**: Access and download assignments
- **Grade Viewing**: Check marks, grades, and academic performance
- **Attendance**: View personal attendance records
- **Fee Status**: Check fee payments and outstanding amounts

## 🛠️ Technology Stack

- **Backend**: [Laravel 11.x](https://laravel.com/docs/11.x)
- **Frontend**: [Bootstrap 5.x](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
- **Database**: MySQL
- **Permission System**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- **PHP Version**: 8.2+
- **Dependency Management**: Composer

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest stable version
- **Node.js**: 16.x or higher (for asset compilation)
- **MySQL**: 5.7 or higher / MariaDB 10.3+
- **Web Server**: Apache/Nginx
- **Extensions**: PHP extensions required by Laravel 11.x

## ⚡ Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/Grace-stha730/School-Management-System.git
cd School-Management-System
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (if applicable)
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Database Migrations
```bash
# Run migrations
php artisan migrate

# Run seeders (optional)
php artisan db:seed
```

### 6. Create Storage Symlink
```bash
php artisan storage:link
```

### 7. Optimize Application
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit **http://127.0.0.1:8000** in your browser.

## 🔐 Default Login Credentials

**Admin Access:**
- **Email**: admin@school.com
- **Password**: admin123

**Teacher Demo:**
- **Email**: teacher@school.com  
- **Password**: teacher123

**Student Demo:**
- **Email**: student@school.com
- **Password**: student123

> ⚠️ **Important**: Change default passwords immediately after first login in production environment.

## 📖 Complete Setup Guide

### Initial System Configuration

After successful installation and login, follow these steps to set up your school management system:

### 1. Create Academic Session
Navigate to **Academic Settings** → **Session Management**
- Create a new academic session (e.g., "2024-2025")
- Set start and end dates
- Mark as current session

### 2. Set Up Semesters
Go to **Academic Settings** → **Semester Management**
- Create semesters (e.g., "Fall 2024", "Spring 2025")
- Define semester duration (typically 3-6 months)
- Associate with academic session

### 3. Create Classes
Navigate to **Academic Settings** → **Class Management**
- Add classes (e.g., "Grade 1", "Grade 10 Science")
- Assign to current session
- Set class capacity and details

### 4. Set Up Sections
Go to **Academic Settings** → **Section Management**
- Create sections for each class (e.g., "Section A", "Section B")
- Assign room numbers
- Link to respective classes

### 5. Configure Courses
Navigate to **Course Management**
- Create courses for each class and semester
- Set course codes, names, and credits
- Assign to appropriate classes

### 6. Add Faculty Members
Go to **User Management** → **Teachers**
- Add teacher profiles with complete information
- Set roles and permissions
- Upload profile pictures and documents

### 7. Assign Teachers to Courses
Navigate to **Academic Settings** → **Teacher Assignment**
- Assign teachers to specific courses
- Link with classes and sections
- Set semester assignments

### 8. Register Students
Go to **User Management** → **Students**
- Add student profiles and academic information
- Include parent/guardian details
- Assign to appropriate classes and sections

### 9. Set Up Fee Structure
Navigate to **Fee Management**
- Create fee heads (Tuition, Library, Sports, etc.)
- Set up fee structures for different classes
- Configure payment schedules

### 10. Configure Examination System
Go to **Examination** → **Grading System**
- Create grading systems for each class
- Set up grade rules and point systems
- Configure examination types

## 🎯 User Roles & Permissions

### 🔑 Admin Capabilities
- **Complete System Control**: Full access to all modules
- **User Management**: Create, edit, delete users and assign roles
- **Academic Configuration**: Set up sessions, classes, courses
- **Financial Management**: Configure fees and track payments
- **Report Generation**: Generate comprehensive reports
- **System Settings**: Configure system-wide preferences

### 👨‍🏫 Teacher Capabilities
- **Course Management**: Manage assigned courses and materials
- **Student Assessment**: Create exams, enter marks, generate grades
- **Attendance Management**: Take and track student attendance
- **Assignment Distribution**: Create and share assignments
- **Communication**: Send notices and announcements
- **Progress Tracking**: Monitor student academic progress

### 👨‍🎓 Student Capabilities
- **Academic Overview**: View courses, grades, and progress
- **Assignment Access**: Download assignments and submit work
- **Attendance Tracking**: View personal attendance records
- **Fee Information**: Check payment status and outstanding amounts
- **Resource Access**: Download course materials and syllabi

## 📱 Module Documentation

### 🏫 Academic Management
- **Session Management**: Year-wise academic session control
- **Class & Section Organization**: Hierarchical class structure
- **Course Management**: Subject and curriculum handling
- **Semester Planning**: Term-based academic planning

### 👥 User Management System
- **Multi-role Support**: Admin, Teacher, Student roles
- **Profile Management**: Comprehensive user profiles
- **Permission Control**: Role-based access control
- **Parent Integration**: Parent information and communication

### 📊 Examination & Grading
- **Flexible Exam Creation**: Multiple exam types support
- **Automated Grade Calculation**: Rule-based grading system
- **Mark Entry System**: Secure mark entry and verification
- **Result Generation**: Automated result compilation

### 💰 Fee Management
- **Flexible Fee Structure**: Multiple fee heads support
- **Payment Tracking**: Complete payment history
- **Discount Management**: Rule-based discount system
- **Financial Reports**: Comprehensive financial reporting

### 📚 Academic Resources
- **Digital Library**: Course materials and resources
- **Assignment System**: Create, distribute, and track assignments
- **Syllabus Management**: Curriculum and syllabus handling
- **Note Sharing**: Teacher-student resource sharing

## 🔧 Advanced Configuration

### Environment Setup for Production
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Configure database for production
DB_CONNECTION=mysql
DB_HOST=your_production_host
DB_DATABASE=your_production_database

# Set up mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### Performance Optimization
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Security Configuration
- Enable CSRF protection
- Configure secure session settings
- Set up proper file upload restrictions
- Implement rate limiting
- Configure SSL/HTTPS

## 🚀 Deployment Guide

### Server Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.15+
- **PHP**: 8.2+ with required extensions
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **SSL Certificate**: Required for production
- **Memory**: Minimum 512MB RAM

### Deployment Steps
1. **Upload Files**: Transfer all project files to server
2. **Set Permissions**: Configure proper file/folder permissions
3. **Environment**: Configure production `.env` file
4. **Dependencies**: Run `composer install --optimize-autoloader --no-dev`
5. **Database**: Run migrations and seeders
6. **Optimize**: Cache configuration, routes, and views
7. **SSL Setup**: Configure HTTPS and security headers

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
```bash
# Check database credentials in .env
# Ensure MySQL service is running
# Verify database exists and user has permissions
```

**Permission Denied Errors**
```bash
# Set correct permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
```

**Composer Issues**
```bash
# Clear composer cache
composer clear-cache
# Update dependencies
composer update
```

**Migration Errors**
```bash
# Reset migrations (development only)
php artisan migrate:reset
php artisan migrate
```

## 📞 Support & Documentation

### Getting Help
- **Documentation**: Check this README and inline code comments
- **Issue Tracking**: Report bugs via GitHub Issues
- **Community**: Join our community discussions
- **Updates**: Follow repository for latest updates

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests for new features
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Community**: For the amazing framework
- **Bootstrap Team**: For the responsive UI framework
- **Spatie**: For the excellent permission package
- **Contributors**: All developers who have contributed to this project

---

## 📸 System Screenshots

### Admin Dashboard
<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-27-05 Unifiedtransform.png"></h1>

### 1. Create a School Session:
After logging in for the first time, you will see following message at the top nav bar.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-31-38 Unifiedtransform.png"></h1>

To create a new session, go to **Academic Settings** page.

#### Academic Settings page:
<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-32-44 Unifiedtransform.png"></h1>

Successful creation of session using following form will display success message:

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-33-45 Unifiedtransform.png"></h1>

### 2. Create a Semester
Now create a semester. A semester duration usually is 3 - 6 months.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-34-45 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-36-39 Unifiedtransform.png"></h1>

### 3. Create classes
Now create classes. Give common names such as: **Class 1** or **Class 11 (Science)**.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-35-16 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-37-26 Unifiedtransform.png"></h1>

### 4. Create sections
Now create sections for each classes. Give section's name (e.g.: Section A, Section B), room number and assign them to respective class.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-36-27 Unifiedtransform.png"></h1>

### 5. Create Courses
Now create courses and assign them to respective semester and class.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-38-13 Unifiedtransform.png"></h1>

### 6. Set attendance type
Attendance can be maintained in two ways: 1. By section, 2. By course. Stick to one type for a semester. Default: **By section**.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-37-09 Unifiedtransform.png"></h1>

### 7. Add teachers
Now add teachers.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-11-34 Unifiedtransform.png"></h1>

### 8. Assign teacher
Now assign teachers to semester, class, section, and course.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-12-05 Unifiedtransform.png"></h1>

### 9. Add students
Now add students and assign them to class, and section.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-43-37 Unifiedtransform.png"></h1>

### 10. View added teachers and students
Now browse to **View Teachers** and **View Students** pages.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-55-18 Unifiedtransform.png"></h1>

### 11. View student and teacher profile
Now browse to **Profile** from student and teacher list.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 18-29-30 Unifiedtransform.png"></h1>

### 12. View and Edit Classes and Sections
Now go to **Classes**. Here you can view all classes and their respective sections, syllabi, and courses. Classes, sections, and courses can be edited from here.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-30-30 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-30-55 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-31-14 Unifiedtransform.png"></h1>

### 13. Create Grading Systems
Now create grading system for each class and a semester.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-32-31 Unifiedtransform.png"></h1>

### 14. View Grading Systems
Now browse to created Grading Systems.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-33-23 Unifiedtransform.png"></h1>

### 15. Add and view Grading System Rules
Now add rules to the grading system and browse them.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-33-36 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 12-16-04 Unifiedtransform.png"></h1>

### 16. Add Notices
Admin can add notice. Right now, notices can be written using a rich text editor.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-03-55 Unifiedtransform.png"></h1>

### 17. Create and view Routines
Routines can be created for each class and section.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-27-54 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 02-26-21 Unifiedtransform.png"></h1>

### 18. Add Syllabi
Syllabus for each class and course can be added. Admin can view them from **Classes** page. Syllabus can be downloaded.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 18-14-31 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-55-50 Unifiedtransform.png"></h1>

### 19. Browse by Sessions
You can browse previous sessions like a snapshot. This mode is **Read only**. Nobody should be able to change the previous sessions' data.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 02-28-23 Unifiedtransform.png"></h1>

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-37-02 Unifiedtransform.png"></h1>

### 20. Allow Teachers to submit Final Marks
Submitting final marks of a semester should be controlled. By enabling this feature, it is possible to open a Mark Submission Window for a short time period. **Default: Disallowed**.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 00-38-37 Unifiedtransform.png"></h1>

### 21. Promote students
Students can only be promoted to a new class and section when a new Session along with its classes and sections are created.

<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 02-27-32 Unifiedtransform.png"></h1>
<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 02-28-00 Unifiedtransform.png"></h1>

### Teacher Dashboard
<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-41-04 Unifiedtransform.png"></h1>

### Student Dashboard  
<h1 align="center"><img src="public/docs/imgs/ut/Screenshot 2021-12-07 at 01-57-15 Unifiedtransform.png"></h1>

## 🔄 Workflow Examples

### For School Administrators
1. **Initial Setup**: Create sessions, classes, sections, and courses
2. **User Management**: Add teachers and students to the system  
3. **Academic Planning**: Set up grading systems and examination rules
4. **Fee Management**: Configure fee structures and track payments
5. **Monitoring**: Generate reports and monitor system usage

### For Teachers
1. **Course Access**: View assigned courses and student lists
2. **Content Management**: Upload syllabi, create assignments
3. **Assessment**: Create exams, enter marks, calculate grades
4. **Communication**: Post notices and announcements
5. **Reporting**: Generate student progress reports

### For Students  
1. **Academic Tracking**: Monitor courses, grades, and attendance
2. **Resource Access**: Download assignments and course materials
3. **Progress Monitoring**: Check academic performance and grades
4. **Communication**: View notices and important announcements
5. **Fee Management**: Check payment status and fee details

---

## 📱 API Documentation

### Available Endpoints
- **Authentication**: `/api/auth/*` - Login, logout, password reset
- **Users**: `/api/users/*` - User management endpoints
- **Academic**: `/api/academic/*` - Academic data management
- **Examination**: `/api/exams/*` - Examination system APIs
- **Fees**: `/api/fees/*` - Fee management endpoints

### API Authentication
The system supports token-based authentication for API access. Include the bearer token in your requests:
```
Authorization: Bearer {your-token}
```

---

## 🛠️ Development

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature
./vendor/bin/phpunit --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

### Code Quality
```bash
# PHP CodeSniffer
./vendor/bin/phpcs

# PHP Stan (Static Analysis)
./vendor/bin/phpstan analyse

# PHP CS Fixer
./vendor/bin/php-cs-fixer fix
```

### Database Management
```bash
# Create new migration
php artisan make:migration create_example_table

# Create model with migration
php artisan make:model Example -m

# Create seeder
php artisan make:seeder ExampleSeeder

# Refresh database with seeders
php artisan migrate:fresh --seed
```

