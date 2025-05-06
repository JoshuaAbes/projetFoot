
# ✨ Laravel + Vue Fullstack Boilerplate ✨

## 🎓 WebMobUi - Media Engineering - HEIG-VD 🎓

This is a fullstack Laravel and Vue.js boilerplate designed for the course.

---

## ⚙️ Installation Steps ⚙️

Follow these steps to get your project up and running:

### 1. Create Project Folder 📁

```bash
mkdir YourAppName
cd YourAppName
```

### 2. Clone Repository & Set Upstream 🔄

Clone the boilerplate repository and then point the remote origin to your own GitHub repository (make sure you create it on GitHub first!).

```bash
# Clone the boilerplate
git clone https://github.com/Chabloz/WebMobUI52-fullstack.git .

# Set your repository as the origin
git remote set-url origin https://github.com/YourGitHubUsername/YourAppName.git
```

### 3. Install Dependencies 📦

Install both the Node.js and PHP dependencies.

```bash
# Install Node.js dependencies and build assets
npm install
npm run build

# Install PHP dependencies
composer install
```

### 4. Configure Environment 📝

Copy the example environment file to create your own configuration.

```bash
cp .env.example .env
```

👉 **Important:** Edit the `.env` file if you need to configure database connections or other settings. By default, it uses SQLite.

### 5. Generate Key & Run Migrations 🔑

Generate the unique application key and set up the database schema.

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate
```

### 6. Run the Application ▶️

Start the development server.

```bash
composer run dev
```

🎉 Your application should now be running! 🎉