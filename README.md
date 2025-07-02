<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

# Task Manager — Modular Project & Task Management App (Laravel)

> A full-stack-ready backend system built with **Laravel 12** and **PHP 8.2** — designed for **real-world team collaboration**, task workflows, and clean role-based access management.  
> Thoughtfully crafted to support future **React SPA** and **API-first** architecture.

---

## 💡 Why This Project Matters

This isn’t just another CRUD app — it’s a demonstration of:
- Modular thinking with clear model boundaries (`User`, `Project`, `Folder`, `Task`)
- Role management with pivot tables (`belongsToMany` with dynamic role checkers)
- Enum-driven logic to reduce magic strings and improve consistency (`TaskStatus`, `TaskPriority`)
- Scalable structure for frontend separation and RESTful API expansion
- Clean Laravel best practices: Factories, Seeders, Migrations, Accessors, Policies-ready

---

## 🔍 Core Features

- ✅ **Project Management** — Define and manage multiple projects per user  
- 📁 **Folder Hierarchy** — Organize tasks into nested folders  
- 📌 **Task Management** — Assign tasks, track `status` and `priority` using enums  
- 🧑‍🤝‍🧑 **Role-Based Access** — Assign multiple roles per user (Admin, Member, etc.)  
- 📎 **File Attachments** — Attach documentation to tasks (upload system in progress)  
- 🔐 **Authentication-Ready** — Laravel Sanctum integrated for API token auth  
- 🚀 **Frontend-Ready** — Backend structured for easy React (or Vue) integration

---

## 🧱 Tech Stack

| Layer       | Tools                           |
|-------------|---------------------------------|
| Language    | PHP 8.2                         |
| Framework   | Laravel 12                      |
| Database    | MySQL with Eloquent ORM         |
| Auth        | Laravel Sanctum (Token-based)   |
| Future UI   | React (Planned SPA frontend)    |
| Extras      | Enum (PHP native), Seeders, Factories, Laravel Artisan

---

## ⚙️ Installation Guide

```bash
git clone https://github.com/akram-khodami/task-manager.git
cd task-manager

composer install
cp .env.example .env
php artisan key:generate

# Configure your DB settings in .env
php artisan migrate --seed

php artisan serve
# App will run at http://localhost:8000
