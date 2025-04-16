# PHP + Vite Starter Kit

A lightweight PHP/Node setup using Vite for modern front-end tooling and a PHP backend with environment flexibility (local, host, and build modes).

---

## 1 – Prerequisites

To use this starter, you’ll need:

- Node.js
- npm
- PHP

---

## 2 – Features

- ⚡ Vite-powered bundling for JS, SCSS, and fonts
- 🐘 Native PHP with custom router and environment setup
- 📦 Simple production build output (`build/`)
- 🔁 Hot Module Replacement (HMR) and full reload support for PHP file changes
- 🧭 Clean URL routing: /about loads pages/about.php (no .php in the URL)

---

## 3 – Project Structure

    ├── .env                    # Environment variables
    ├── .env-example            # Sample .env for local setup
    ├── .htaccess               # Enables clean URLs in production (Apache)
    ├── build/                  # Production build output (generated by Vite)
    │   └── bundle/
    │       ├── *.js, *.css     # Hashed and optimized assets
    │       ├── *.woff, *.woff2 # Fonts
    │       └── .vite/          # Vite manifest.json
    ├── composer.json           # PHP dependencies
    ├── index.php               # Main entry point and router logic
    ├── pages/                  # PHP pages routed via index.php
    │   ├── home.php
    │   ├── contact.php
    │   └── 404.php
    ├── php/
    │   └── helpers/            # PHP utility classes
    ├── router.php              # Dev-only router for PHP built-in server
    ├── scripts/                # Node scripts (set env vars, get IP)
    ├── src/                    # Source front-end files
    │   ├── fonts/              # Original font files
    │   ├── js/                 # Entry point JS file
    │   └── scss/               # Global styles (Sass)
    ├── templates/              # HTML structure (head, footer, etc.)
    └── vite.config.js          # Vite configuration


## 4 – Setup

1. **Install dependencies**

       npm install
       composer install

2. **Copy and configure your `.env` file**

       cp .env-example .env

3. **Available commands**

       npm run dev       # Start local development server
       npm run host      # Start dev server on local network (LAN)
       npm run build     # Build for production
       npm run preview   # Preview the production build locally

---

## 5 – Routing Logic

To create a new page, simply add a `.php` file inside the `pages/` directory.  
Each file corresponds to a clean URL, without needing `.php` in the browser.

Examples:
- `pages/about.php` → accessible at `/about`
- `pages/contact.php` → accessible at `/contact`
- `pages/404.php` → shown automatically if the requested page doesn't exist

This system works with clean URLs thanks to the `index.php` router,  
which dynamically loads the correct page file based on the URL path.

In local development, `router.php` is used to simulate the same behavior when using PHP’s built-in server.

---

## 6 – Notes

- The `base` path in `vite.config.js` must match the public path where assets will be deployed (`/build/bundle`).
- `router.php` ensures fonts and other static files are properly served during local preview.
- This is a work-in-progress starter setup and is not guaranteed to be bug-free.
- You can use it for both personal and commercial projects. Feel free to adapt it to your needs.