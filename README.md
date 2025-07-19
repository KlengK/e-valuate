e-VALuate: Customer Satisfaction Survey System
e-VALuate is a modern, gamified survey management system designed for the Valenzuela City Public Library. It provides a simple and engaging way for patrons to give feedback while offering powerful creation and analytics tools for library staff.

✨ Features
Admin Dashboard: A secure, Vue.js-powered single-page application for managing surveys.

Unified Survey & Question Creation: Create a survey and all its questions on a single, dynamic form.

Survey Status Control: Easily manage the lifecycle of surveys by switching them between Draft, Active, and Closed states.

Gamified Public Surveys: A mobile-first, one-question-per-page experience for patrons with progress bars and a clean UI.

Multiple Question Types: Supports star ratings, open-text answers, and multiple-choice questions.

Shareable Links & QR Codes: Generate shareable URLs and downloadable QR codes for each survey to use on flyers and posters.

Results & Analytics: A dedicated reporting page for each survey showing total completions and a breakdown of answers per question.

🛠️ Tech Stack
Backend: PHP, Laravel

Frontend: Vue.js, Inertia.js, Tailwind CSS

Database: MySQL

Development Environment: Laravel Herd

Production Server: Ubuntu, Apache2

📋 Prerequisites
Before you begin, ensure you have the following software installed on your local machine:

Laravel Herd (or another local server environment with PHP 8.2+, Composer, and Node.js)

A MySQL database server (included with Herd Pro, or you can use a separate installation like XAMPP or a standalone server).

Git for version control.

🚀 Installation & Setup
Follow these steps to get your local development environment running.

1. Clone the Repository
Open your terminal and clone the project to your local machine.

git clone <your-repository-url> e-valuate
cd e-valuate

2. Install Dependencies
Install all the required PHP and Node.js packages.

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

3. Configure Your Environment
Create your local environment configuration file and generate an application key.

# Create the .env file from the example
cp .env.example .env

# Generate a new application key
php artisan key:generate

4. Set Up the Database
Open your preferred MySQL client (e.g., DBeaver, MySQL Workbench).

Create a new, empty database named e_valuate.

Open the .env file in your code editor and update the database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_valuate
DB_USERNAME=root
DB_PASSWORD=

5. Run Database Migrations
This command will create all the necessary tables in your database.

php artisan migrate

6. Run the Development Server
Compile the frontend assets and start the Vite development server.

npm run dev

Your Laravel application will be served automatically by Herd at http://e-valuate.test.

📖 Usage Guide
Navigate to http://e-valuate.test in your browser.

Click the "Register" link to create a new admin account.

After registering, you will be redirected to the dashboard.

Click on the "Surveys" navigation link.

Click "Create Survey" to build your first survey and add its questions.

Once created, you can manage the survey's status, view reports, or generate a shareable link and QR code from the survey list.