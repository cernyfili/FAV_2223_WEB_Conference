# FAV_2223_WEB_WebApp

Semester project for **WEB (FAV / ZČU)**.

> **Stack:** PHP + Twig (server-rendered web app)

## Requirements

- PHP (8.x recommended)
- Composer
- A web server (Apache/Nginx) or the built-in PHP dev server
- (Optional) Database server (depends on your configuration)

## Getting started

### 1) Clone

```bash
git clone https://github.com/cernyfili/FAV_2223_WEB_WebApp.git
cd FAV_2223_WEB_WebApp
```

### 2) Install dependencies

```bash
composer install
```

### 3) Configure

This project may require environment-specific configuration (e.g., database credentials). Look for configuration files in common locations such as:

- `config/`
- `.env` / `.env.local`

If your project uses a database, make sure the connection details match your local setup.

### 4) Run locally

#### Option A: PHP built-in server

If the project has a public document root (commonly `public/`), start:

```bash
php -S localhost:8000 -t public
```

Then open:

- http://localhost:8000

#### Option B: Apache / Nginx

Configure your virtual host / server block to point the document root to the project’s public directory (commonly `public/`).

## Project structure (typical)

> Your exact structure may differ, but PHP+Twig projects commonly look like:

- `public/` – web root (front controller)
- `src/` – application source code
- `templates/` – Twig templates
- `config/` – configuration
- `vendor/` – Composer dependencies (generated)

## Development notes

- Templates are written in **Twig**.
- PHP dependencies are managed via **Composer**.

## Contributing

This is a school/semester project, but contributions and suggestions are welcome via issues/PRs.

## License

No license specified. If you want this to be open-source, add a `LICENSE` file (e.g., MIT).