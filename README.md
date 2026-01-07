# e-VALuate: Customer Satisfaction Survey System

![Laravel](https://img.shields.io/badge/Backend-Laravel_10-FF2D20)
![Vue.js](https://img.shields.io/badge/Frontend-Vue.js_3-4FC08D)
![Inertia](https://img.shields.io/badge/Architecture-Inertia.js-purple)
![Tailwind](https://img.shields.io/badge/Styling-Tailwind_CSS-38B2AC)
![Status](https://img.shields.io/badge/Status-Production_Ready-success)

> **Project Context:** Developed for the **Valenzuela City Public Library**, *e-VALuate* is a modern, gamified survey platform designed to increase patron feedback rates through an engaging mobile-first interface while providing powerful analytics for library administrators.

## 📖 Project Overview

Traditional library survey forms suffer from low engagement and manual data entry. **e-VALuate** solves this by digitizing the entire feedback loop. It features a **Single-Page Application (SPA)** admin dashboard for creating dynamic surveys and a "gamified" public interface that rewards users with animations upon completion.

Built using the **TALL stack's modern cousin (LIVT: Laravel, Inertia, Vue, Tailwind)**, this application demonstrates a seamless monolith architecture where the frontend and backend are tightly integrated without the complexity of a separate API.

## 🚀 Key Features

### 🏛️ For Administrators (Dashboard)
* **Dynamic Survey Builder:** Create surveys with mixed question types (Star Ratings, Multiple Choice, Open Text) and toggle "Required" fields instantly.
* **Real-Time Analytics:** * Automated **Pie Charts** for multiple-choice data.
    * Tabbed browsing for individual response auditing.
    * **PDF & CSV Exports** using `maatwebsite/excel` and `dompdf`.
* **Survey Lifecycle Management:**
    * **One-Click Duplication:** Clone existing surveys to save time.
    * **State Management:** Switch surveys between *Draft*, *Active*, and *Closed*.
* **Dark Mode UI:** Fully responsive dark/light mode interface built with Tailwind CSS.

### 📱 For Patrons (Public Interface)
* **Gamified UX:** One-question-per-page flow with smooth transitions to reduce cognitive load.
* **Visual Feedback:** Custom progress bars and a "Confetti" celebration animation on submission to encourage future participation.
* **QR Code Integration:** Auto-generates branded QR codes for each active survey for easy lobby scanning.

## 🛠️ Technical Architecture

This project utilizes **Inertia.js** to build a modern Single-Page Application (SPA) using classic server-side routing.

* **Backend:** PHP (Laravel) handles validation, Eloquent relationships, and data export logic.
* **Frontend:** Vue.js (Composition API) handles the reactive UI, while Inertia acts as the glue to render Vue components directly from Laravel controllers.
* **Database:** MySQL with a normalized schema for `Surveys` → `Questions` → `Responses`.
* **State Management:** Minimal client-side state required due to Inertia's seamless data passing.

## 📸 Screenshots

| **Admin Dashboard** | **Analytics View** |
|:-------------------:|:------------------:|
| *(Place screenshot of dashboard here)* | *(Place screenshot of pie charts here)* |

| **Public Survey Mobile View** | **QR Code Generation** |
|:-----------------------------:|:----------------------:|
| *(Place screenshot of phone view here)* | *(Place screenshot of QR code modal here)* |

## 💻 Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/KlengK/e-valuate.git](https://github.com/KlengK/e-valuate.git)
    cd e-valuate
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update your `.env` file with your MySQL database credentials.*

4.  **Database Migration**
    ```bash
    php artisan migrate
    ```

5.  **Build Assets & Run**
    ```bash
    npm run build
    php artisan serve
    ```

## 👤 Author

**[Your Name]**
*Full Stack Developer*
*Valenzuela City Library IT Team*

---
*© 2026 Valenzuela City Public Library*
