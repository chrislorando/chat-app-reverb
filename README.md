# Chat Reverb App
**Upload file**
![image](https://github.com/user-attachments/assets/a837b236-d3a4-4e8c-a5e1-58d0bf83a213)
**View chats**
![image](https://github.com/user-attachments/assets/730b8f76-4f89-48e3-9cc6-fbf98d77e883)
**View profile**
![image](https://github.com/user-attachments/assets/8f3b01ca-9b48-4f1a-a699-ba2f22a9a3fd)
**Writing help with gemini-2.0-flash-lite**
![image](https://github.com/user-attachments/assets/66b309c8-3740-4801-b5d5-19ef6c374caf)


A real-time chat application built with Laravel, Livewire, Alpine.js, Tailwind CSS, and Reverb. This project serves both as a learning medium to deepen my understanding of modern web development technologies and as a showcase piece for my professional portfolio. The app demonstrates modern Laravel real-time capabilities with a minimal stack.

## Features

✨ **Real-time messaging**  
💡 **Typing indicators**  
🟢 **Online/offline status**  
📁 **File uploads** (photos & documents)  
🎨 **Modern UI** with Tailwind CSS  
⚡ **Interactive components** with Livewire & Alpine.js
🧠 **Smart replies** with Gemini AI integration

## Tech Stack
- [Laravel](https://laravel.com/) — Backend framework
- [Laravel Reverb](https://reverb.laravel.com/) — First-party WebSocket server for Laravel applications
- [Livewire](https://livewire.laravel.com/) — Reactive components without JavaScript
- [Alpine.js](https://alpinejs.dev/) — Lightweight JS framework for interactivity
- [Tailwind CSS](https://tailwindcss.com/) — Utility-first CSS framework
- [Flowbite](https://flowbite.com/) — Tailwind UI components
- [Laravel Reverb](https://laravel.com/docs/reverb) — Real-time WebSocket server
- [SQLite](https://www.sqlite.org/) — Lightweight database for local development
- [Nginx](https://nginx.org/) — HTTP web server
- [Gemini AI](https://ai.google.dev/gemini-api/docs) — LLMs

## Installation Guide

### Prerequisites
- PHP 8.2+
- Laravel 11
- Composer
- Node.js 16+
- SQLite
- Nginx 

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

6. **Reverb configuration**
   ```bash
   REVERB_APP_ID=chatapp
   REVERB_APP_KEY=somekey
   REVERB_APP_SECRET=somesecret
   REVERB_HOST=127.0.0.1
   REVERB_PORT=8080

   AI_PROVIDER=gemini
   GEMINI_API_KEY=12345678
   GEMINI_IMAGE_MODEL=gemini-2.0-flash-lite
   GEMINI_CONTENT_API="generateContent"
   GEMINI_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models

7. **Build assets**
   ```bash
   npm run dev

8. **Start the application**
   ```bash
   php artisan serve
   php artisan reverb:start

## Demo Link
   https://chatsapp.demolite.my.id

## Credits
   https://avatar-placeholder.iran.liara.run/avatars