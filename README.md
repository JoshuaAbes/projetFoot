# ⚽ Football Story Adventure – Laravel + Vue Fullstack ⚽

## 🎓 WebMobUi - Media Engineering - HEIG-VD 🎓

**Football Story Adventure** is a fullstack interactive storytelling platform built with Laravel and Vue.js, themed around football. Users experience a “choose your own adventure” narrative where their decisions shape the story path, and their progress is automatically saved.

---

## ✨ Features ✨

- 📖 **Interactive Storytelling** – Branching narratives based on user choices  
- 💾 **User Progress Tracking** – Saves progress per user and story  
- 📱 **Responsive Design** – Optimized for both mobile and desktop  
- 🔍 **Visual Path Indicators** – Highlights previously explored story paths  
- 🔐 **Authentication** – Secure access and user-specific story progress  
- 🔧 **RESTful API** – Well-structured versioned API (`/api/v1/...`)  
- ✅ **Server-side Validation** – Ensures robust form and API inputs  

---

## 🧰 Tech Stack 🧰

| Layer       | Tech                                  |
|-------------|----------------------------------------|
| Backend     | Laravel 10+                            |
| Frontend    | Vue.js 3+, Pinia                       |
| Database    | SQLite / MySQL / PostgreSQL            |
| Styling     | Custom responsive CSS                  |
| Auth        | Laravel Breeze / Sanctum (if used)     |
| API         | RESTful with versioning (`/api/v1/`)   |

---

## ⚙️ Installation Guide ⚙️

### 1. Clone Repository 📦

```bash
git clone https://github.com/your-username/football-story-adventure.git
cd football-story-adventure
```

### 2. Install Dependencies 🔧

```bash
# Backend (PHP)
composer install

# Frontend (JavaScript)
npm install
```

### 3. Configure Environment 📝

```bash
cp .env.example .env
```

> 🎯 Edit `.env` to configure your database (SQLite recommended for quick setup)

### 4. Generate Application Key 🔑

```bash
php artisan key:generate
```

### 5. Migrate and Seed Database 🗃️

```bash
php artisan migrate
php artisan db:seed --class=FootballStorySeeder
```

### 6. Build Frontend Assets 🧱

```bash
npm run build
```

### 7. Run the Application ▶️

```bash
php artisan serve
```

🌍 Visit [http://localhost:8000](http://localhost:8000) to start your adventure!

---

## 🗂️ Project Structure

### Backend (Laravel)
- `Models/Story`, `Chapter`, `Choice` – Core data structure  
- `Controllers/` – Handle API logic  
- `FormRequests/` – Request validation  
- `Routes/api.php` – Versioned API routing  
- `Database/` – Migrations and seeders

### Frontend (Vue.js)
- `views/` – Main pages: home, story view  
- `components/` – Reusable UI blocks  
- `stores/` – Pinia for state management  
- `router/` – Vue Router configuration  
- `utils/` – API helpers and logic

---

## 📚 API Overview

### 📘 Stories
- `GET /api/v1/stories` – List all stories  
- `GET /api/v1/stories/{id}` – Get specific story  
- `POST /api/v1/stories` – Create (auth required)  
- `PUT /api/v1/stories/{id}` – Update (auth required)  
- `DELETE /api/v1/stories/{id}` – Delete (auth required)  

### 📗 Chapters
- `GET /api/v1/stories/{story}/first-chapter` – Start story  
- `GET /api/v1/chapters/{id}` – Load chapter  
- `POST /api/v1/chapters` – Create (auth required)  
- `PUT /api/v1/chapters/{id}` – Update (auth required)  
- `DELETE /api/v1/chapters/{id}` – Delete (auth required)  

### 📙 Progress
- `POST /api/v1/progress` – Save progress  
- `GET /api/v1/progress/{storyId}` – Load progress  
- `DELETE /api/v1/progress/{storyId}` – Reset progress  

---

## 🚀 Development Workflow

For development with hot-reload:

```bash
# Laravel backend
php artisan serve

# Vue frontend
npm run dev
```

---

## 👏 Credits

- Developed with Laravel and Vue.js  
- Premier League color scheme & football theme  
- Character illustrations generated for dynamic storytelling  

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).
