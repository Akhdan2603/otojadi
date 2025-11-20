# Deployment Guide

## 1. Backend & Frontend (Vercel)

Since this project is built with **Next.js Fullstack**, you can deploy the entire application (Frontend + API) to Vercel in one go.

### Steps:
1.  **Push to GitHub:** Ensure your code is committed and pushed to a GitHub repository.
2.  **Create Vercel Project:**
    *   Go to [Vercel Dashboard](https://vercel.com).
    *   Click "Add New..." -> "Project".
    *   Import your GitHub repository.
3.  **Configure Project:**
    *   **Framework Preset:** Next.js (should be auto-detected).
    *   **Root Directory:** Click `Edit` and select `web` (since the app lives in the `web/` folder).
4.  **Environment Variables:**
    *   Copy the contents of `.env.example`.
    *   Paste them into the "Environment Variables" section in Vercel.
    *   **Important:** You must provide a real `DATABASE_URL` (see Database section below).
5.  **Deploy:** Click "Deploy".

## 2. Database (MySQL)

You need a publicly accessible MySQL database.

### Options:
*   **PlanetScale:** Excellent for serverless/Next.js.
*   **Railway:** Easy to spin up a MySQL container.
*   **Aiven:** Managed MySQL (Free tier available).
*   **Supabase (PostgreSQL):** *Note: You would need to change `provider = "mysql"` to `provider = "postgresql"` in `prisma/schema.prisma` if you choose this.*

### Setup:
1.  Get the connection string (e.g., `mysql://user:pass@host:port/dbname`).
2.  Set this as `DATABASE_URL` in your Vercel Environment Variables.
3.  Run migrations during build or manually:
    *   In Vercel "Build Command", you can override it to: `cd web && npx prisma db push && next build`.
    *   Or run locally connected to the prod DB: `DATABASE_URL="your-prod-url" npx prisma db push`.

## 3. Storage (Files)

Since Vercel is serverless, you cannot store uploaded PowerPoint files on the local filesystem (`public/` uploads will disappear).

**Recommended Solution:** **AWS S3** or **Vercel Blob**.
*   Update the `POST /api/products` logic to upload to S3/Blob and save the URL to the database.
