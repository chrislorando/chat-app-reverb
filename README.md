# Chat Reverb App

![image](https://github.com/user-attachments/assets/730b8f76-4f89-48e3-9cc6-fbf98d77e883)

A real-time chat application built with Laravel, Livewire, Alpine.js, Tailwind CSS, and Reverb. This project serves both as a learning medium to deepen my understanding of modern web development technologies and as a showcase piece for my professional portfolio. The app demonstrates modern Laravel real-time capabilities with a minimal stack.

## Features

✨ **Real-time messaging**  
💡 **Typing indicators**  
🟢 **Online/offline status**  
📁 **File uploads** (photos & documents)  
🎨 **Modern UI** with Tailwind CSS  
⚡ **Interactive components** with Livewire & Alpine.js

## Tech Stack
- [Laravel](https://laravel.com/) — Backend framework
- [Livewire](https://livewire.laravel.com/) — Reactive components without JavaScript
- [Alpine.js](https://alpinejs.dev/) — Lightweight JS framework for interactivity
- [Tailwind CSS](https://tailwindcss.com/) — Utility-first CSS framework
- [Flowbite](https://flowbite.com/) — Tailwind UI components
- [Laravel Reverb](https://laravel.com/docs/reverb) — Real-time WebSocket server
- [SQLite](https://www.sqlite.org/) — Lightweight database for local development

## Installation Guide

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 16+
- Sqlite

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/chrislorando/chat-reverb-app.git
   cd chat-reverb-app

2. **Install dependencies**
   ```bash
   composer install
   npm install

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate

4. **Set up SQLite database**
   ```bash
   touch database/database.sqlite

   Update .env with:
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite

5. **Set up SQLite database**
   ```bash
   php artisan migrate

6. **Build assets**
   ```bash
   npm run dev

7. **Start the application**
   ```bash
   php artisan serve
   php artisan reverb:start