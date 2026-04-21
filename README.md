# Conference Web App (KIV/WEB) — Semester Project

Web application for a conference website created as a semester project for **KIV/WEB**.

Date: **2022-01-10**

## Tech stack

- **PHP** (backend)
- **MySQL** (database)
- **Twig** (templates)
- **Bootstrap** (UI / responsive layout)
- **jQuery** + **JavaScript** (Bootstrap behavior, UI interactions)
- **FontAwesome** (icons)
- **HTML5 / CSS**

## Project structure

Typical directories used in this project:

- `css/` – additional/custom CSS
- `database/` – database export
- `docs/` – documentation (includes `web_doc.txt`)
- `img/` – images used by the application
- `js/` – custom JavaScript files
- `src/` – main application directory (includes `index.php`)
  - `src/app/` – application code
    - `src/app/controllers/` – controllers (rendering + coordination)
    - `src/app/models/` – database access + sessions + form helpers
    - `src/app/views/` – view-related files
    - `src/app/templates/` – Twig templates
  - `src/composer/` – vendor/imported libraries (Bootstrap, jQuery, FontAwesome, …)

## Architecture overview

### Controllers
Controllers implement `IController` and provide a render method used to display pages.  
Each page has its own controller; a shared/base controller is defined in `BasicSiteController`.

### Models
- `MyDatabase` – database communication (fetching/sending data)
- `MySession` – session handling
- `FormCheck` – form submission checking/processing helpers

### Views / Templates (Twig)
Templates used to render the pages include (from documentation):

- `contributions.twig`
- `inc-contribution_detail.twig`
- `inc-footer.twig`
- `inc-header.twig`
- `inc-navbar.twig`
- `macro.twig`
- `site-contacts.twig`
- `site-contribution_detail.twig`
- `site-contribution_detail_management.twig`
- `site-edit_login_info.twig`
- `site-error.twig`
- `site-form_contribution.twig`
- `site-form_review.twig`
- `site-form_review_assignment.twig`
- `site-intro.twig`
- `site-partners.twig`
- `site-registration.twig`
- `site-user_detail.twig`
- `template-basic.twig`
- `template-management.twig`
- `template-management_item.twig`

## Setup (local)

### Requirements
- PHP (compatible with the project)
- MySQL / MariaDB
- Web server (e.g., Apache/Nginx) or local dev environment (XAMPP/WAMP/etc.)

### Steps
1. Import the database dump located in `database/`.
2. Configure database connection credentials in the project’s configuration (in `src/` / model configuration, depending on implementation).
3. Start the app by serving the `src/` directory (entry point is `src/index.php`).

## Default users (from documentation)

### Administrators
- Login: `Admin`
- Password: `pass`

### Reviewers
- Login: `Recenzent1` / Password: `pass`
- Login: `Recenzent2` / Password: `pass`
- Login: `Recenzent3` / Password: `pass`

### Authors
- Login: `Autor`
- Password: `pass`
