# Credentials & Admin Setup

## 1. Environment Variables (.env)

Create a `.env` file in the `web/` directory based on `.env.example`.

| Variable | Description |
| :--- | :--- |
| `DATABASE_URL` | Connection string for MySQL. |
| `AUTH_SECRET` | Random string for session encryption. Run `openssl rand -base64 32`. |
| `AUTH_URL` | URL of your site (e.g., `http://localhost:3000` or `https://your-site.vercel.app`). |
| `SMTP_*` | SMTP credentials for sending emails (e.g., Mailtrap, SendGrid). |
| `TWILIO_*` | Credentials for sending WhatsApp OTPs. |
| `MIDTRANS_*` | Payment gateway keys from Midtrans Dashboard. |

## 2. Creating an Admin User

Since there is no public registration for Admins, you must elevate a user manually in the database.

### Method A: Via Database GUI (TablePlus, phpMyAdmin)
1.  Register a normal user via the `/register` page.
2.  Open your database tool.
3.  Find the `User` table.
4.  Locate your user row.
5.  Change the `role` column from `USER` to `ADMIN`.

### Method B: Via Prisma Studio
1.  Run `cd web && npx prisma studio`.
2.  Open `http://localhost:5555`.
3.  Select the `User` model.
4.  Edit your user and set `role` to `ADMIN`.
5.  Save changes.

Once an Admin, you can access `/admin` to manage products and orders.

## 3. Twilio & Midtrans Setup

### Twilio (WhatsApp OTP)
*   Sign up for Twilio.
*   Get a specific WhatsApp sender number (Sandbox or Business API).
*   Update `web/lib/otp.ts` logic if you want to switch between SMS and WhatsApp strictly.

### Midtrans
*   Register at [Midtrans](https://midtrans.com).
*   Get Server Key and Client Key.
*   Set `MIDTRANS_IS_PRODUCTION="false"` for testing (Sandbox mode).
