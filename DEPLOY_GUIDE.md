# SaaSNinja Shared Hosting Deployment Guide

This guide walks you through deploying the SaaSNinja Software Platform to a cPanel-based shared hosting environment using Git Version Control linked to your GitHub repository.

---

## Step 1: Prepare Database & User in cPanel
1. Log in to your **cPanel**.
2. Navigate to **MySQL Database Wizard**.
3. Create a new database: e.g., `saasninja_db`.
4. Create a new database user: e.g., `saasninja_user`, and assign a strong password.
5. Grant **All Privileges** to the user on the database.
6. Note down the Database Name, Username, and Password for the `.env` configuration.

---

## Step 2: Configure Environment Variables
1. Rename `.env.example` to `.env` in the root folder of your project.
2. Edit the following variables:
   ```env
   APP_NAME=SaaSNinja
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://saasninja.top

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_cpanel_db_name
   DB_USERNAME=your_cpanel_db_user
   DB_PASSWORD=your_database_password
   ```
3. Set your **Envato Verification Token**:
   ```env
   ENVATO_PERSONAL_TOKEN=your_real_envato_token_here
   ```
4. Configure SMTP settings (for customer ticket notifications):
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=mail.saasninja.top
   MAIL_PORT=465
   MAIL_USERNAME=support@saasninja.top
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=ssl
   MAIL_FROM_ADDRESS=support@saasninja.top
   ```

---

## Step 3: Git Pull & cPanel Deployment Configuration
1. Open the **Git™ Version Control** interface in cPanel.
2. Create or link your repository pointing to `https://github.com/motechgroup/ninjasaas.top.git`.
3. In the repository directory, open `.cpanel.yml` and replace `your_cpanel_username` with your actual cPanel username.
4. Click **Deploy** in the cPanel Git interface to copy files to `public_html/`.

---

## Step 4: Run Migrations, Seeding, and Symlinks Programmatically
Since shared hosting environments block terminal commands and SSH access, we have built a secure web-based console to trigger Laravel setup actions directly from your browser.

1. In your `.env` file, configure a unique, secure deployment key:
   ```env
   DEPLOY_SECRET=YourSuperSecretKey123!
   ```
2. Navigate to your browser and run these URLs to finalize configuration (replace `YourSuperSecretKey123!` with your actual token):
   * **Run Migrations**: 
     `https://saasninja.top/deploy/run?key=YourSuperSecretKey123!&action=migrate`
   * **Seed Demo/Admin Accounts**: 
     `https://saasninja.top/deploy/run?key=YourSuperSecretKey123!&action=seed`
   * **Create Public Storage Symlink**: 
     `https://saasninja.top/deploy/run?key=YourSuperSecretKey123!&action=storage`
   * **Clear Cache**: 
     `https://saasninja.top/deploy/run?key=YourSuperSecretKey123!&action=clear`

---

## Step 5: Configure Cron Scheduler (For Support Tickets Escalation)
To keep the system checking for Envato support expirations and service requests, add a recurring cron job in cPanel running **Every Minute**:
```bash
* * * * * php -d register_argc_argv=On /home/your_cpanel_username/public_html/artisan schedule:run >> /dev/null 2>&1
```

---

## Troubleshooting & Support
* **Vite Assets Not Loading**: Ensure that the compiled production assets exist inside `public_html/build/`. If missing, build them locally (`npm run build`) before pushing to GitHub.
* **404 Errors on Subpages**: Make sure your `.htaccess` file is present in the root directory (which handles rewriting requests directly to `/public`).
