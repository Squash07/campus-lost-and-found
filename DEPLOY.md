# Deploying to InfinityFree

InfinityFree has no command line, so nothing can be installed or migrated on the server. Everything is prepared locally:

| You upload | What it is |
| --- | --- |
| `deploy/campus-lost-and-found/` (whole folder contents) | The app with production-only `vendor/`, demo photos in `public/uploads/items`, and a `.env` with a fresh `APP_KEY` |
| `database/deploy/lostfound-mysql.sql` | All tables, the 4 foreign keys and the demo data, ready for phpMyAdmin |

The `deploy/` folder is git-ignored because it contains `vendor/` and a real `.env`. Never push it to GitHub.

## 1. Create the site and database

1. Sign up at infinityfree.com and create a hosting account with a free subdomain, for example `lostfound-its.infinityfreeapp.com`.
2. In the control panel, open **MySQL Databases** and create a database. Note the **MySQL host** (looks like `sqlXXX.infinityfree.com`, not `localhost`), database name, username and password. The password is shown in the account details.
3. Open **phpMyAdmin** for that database, go to **Import**, choose `database/deploy/lostfound-mysql.sql` and click **Import**. You should see 12 tables.

## 2. Fill in `.env`

Open `deploy/campus-lost-and-found/.env` and replace every `CHANGE_ME`:

- `APP_URL`: your site address. Use `http://` until SSL is active, then switch to `https://`.
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: the values from step 1.

`APP_KEY` is already filled in. Don't change it after people have logged in, or their sessions break.

## 3. Upload the files

1. In the control panel, open **FTP Details** and connect with FileZilla (host `ftpupload.net`, port 21).
2. Open the `htdocs` folder on the server and delete the default `index2.html` if it's there.
3. Upload **the contents** of `deploy/campus-lost-and-found/` into `htdocs`, so that `htdocs/.htaccess`, `htdocs/.env`, `htdocs/app/`, `htdocs/public/` and `htdocs/vendor/` exist. In FileZilla, turn on **Server > Force showing hidden files** so `.env` and `.htaccess` are uploaded.

The upload is about 7,200 files and takes a while over FTP. That's normal.

## 4. Check the site

Open your site address and check:

- [ ] The homepage shows the item feed with photos.
- [ ] You can log in as `budi@lostfound.test` / `password` and see the dashboard.
- [ ] `your-site/.env` shows **403 Forbidden** (the config file is protected).
- [ ] Posting an item with a photo works.

## 5. Turn on HTTPS

1. In the control panel, open **SSL Certificates** and request a free certificate for your domain. Follow the DNS steps it shows.
2. When it's active, change `APP_URL` in `htdocs/.env` to `https://...` and re-upload `.env`.

The app trusts InfinityFree's proxy and forces `https` links in production, so forms won't show "not secure" warnings.

## Before you share the link

- Every demo account uses the password `password`, including the admin. Graders may need it, but anyone can read the README, so log in as admin and change the admin password under **Account settings** if the site will stay up after grading.
- Keep `APP_DEBUG=false`. With `true`, error pages show your database password.
- Keep `SHOW_DEMO_ACCOUNTS=false` unless you want one-click demo logins on the public login page.

## Troubleshooting

| What you see | Fix |
| --- | --- |
| Blank page or **500** | Check `htdocs/storage/logs/laravel.log` over FTP. Usually a wrong `DB_*` value in `.env`. |
| "Database connection refused" | `DB_HOST` must be the `sqlXXX.infinityfree.com` host, not `localhost`. |
| Page loads without styling | The site needs internet access to `cdn.tailwindcss.com` and `cdn.jsdelivr.net`. Check the browser console for blocked scripts. |
| Photos don't appear | Make sure `public/uploads/items/` was uploaded. |
| **419 Page expired** after login | Clear cookies for the site, and make sure `APP_URL` uses the same `http`/`https` as the address bar. |
| URLs contain `/public/` | Check that `htdocs/.htaccess` (the one next to `.env`) was uploaded. |

## Updating the live site later

Re-upload only the files you changed. If a change adds a migration, export the new tables locally (or write the SQL) and import it in phpMyAdmin, since `php artisan migrate` can't run on the server.
