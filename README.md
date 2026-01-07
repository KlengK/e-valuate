e-VALuate: Customer Satisfaction Survey System
e-VALuate is a modern, gamified survey management system designed for the Valenzuela City Public Library. It provides a simple and engaging way for patrons to give feedback while offering powerful creation and analytics tools for library staff.

✨ Features
Admin Dashboard
Comprehensive Dashboard: Get an at-a-glance overview of key metrics, including total surveys, active surveys, responses today, and total responses.

Dynamic UI: A secure, Vue.js-powered single-page application for managing surveys.

Dark Mode: A beautiful, consistent dark theme across the entire admin panel.

Mobile Responsive: The admin dashboard is fully responsive for management on the go.

Survey Management
Unified Survey & Question Creation: Create a survey and all its questions, descriptions, and settings on a single, dynamic form.

Full Editing Capability: Edit a survey's title, description, and dynamically add, remove, or modify questions after creation.

Duplicate Surveys: Instantly create a copy of an existing survey and its questions with a single click.

Secure Deletion: A confirmation modal ensures surveys and all their associated data are not deleted accidentally.

Status Control: Easily manage the lifecycle of surveys by switching them between Draft, Active, and Closed states.

Question Types & Customization
Multiple Question Types: Supports Star Ratings, Open Text, Multiple Choice (single answer), and Checkboxes (multiple answers).

Optional Descriptions: Add optional descriptions to each question for extra context.

Required/Optional Toggle: A user-friendly toggle switch for each question to specify whether an answer is required.

Reporting & Analytics
Tabbed Report Interface: View aggregated "Summary" data or browse through "Individual" responses one by one with pagination.

Data Visualization: Multiple-choice and checkbox question results are automatically rendered as interactive pie charts.

Exporting: Export the summary report to either CSV or PDF format for offline analysis and record-keeping.

Public Survey Experience
Gamified Interface: A mobile-first, one-question-per-page experience for patrons.

Engaging UI: Features a custom background, progress bar, and smooth fade-in/fade-out transitions between questions.

Rewarding Completion: A celebratory confetti animation is displayed on the "Thank You" page.

Shareable Links & QR Codes: Generate shareable URLs and downloadable QR codes (with a merged logo) for each survey.

🛠️ Tech Stack
Backend: PHP, Laravel

Frontend: Vue.js, Inertia.js, Tailwind CSS

Database: MySQL

Development Environment: Laravel Herd

Key Packages: maatwebsite/excel, barryvdh/laravel-dompdf, vue-chartjs, @vueuse/core

🚀 Installation & Setup
1. Clone the Repository
git clone <your-repository-url> e-valuate
cd e-valuate

2. Install Dependencies
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

3. Configure Your Environment
# Create the .env file from the example
cp .env.example .env

# Generate a new application key
php artisan key:generate

4. Set Up the Database
Create a new, empty MySQL database named e_valuate.

Open the .env file and update the database credentials.

Update the timezone in config/app.php: 'timezone' => 'Asia/Manila'.

5. Run Database Migrations
php artisan migrate

6. Place Static Assets
Place your logo at public/images/logo.png and your desired survey background at public/images/background.png.

7. Run the Development Server
npm run dev

Your application will be available at http://e-valuate.test.
