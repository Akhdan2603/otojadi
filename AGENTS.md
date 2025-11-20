# AGENTS.md

## Project Scope
This is the `otojadi` marketplace repository. The active codebase is in the `web/` directory.

## Rules for AI/Agents
1.  **Root Directory:** Always work inside `web/`. Do not modify files in `legacy/`.
2.  **Stack:** Next.js App Router, TypeScript, Prisma, Tailwind CSS.
3.  **Styling:** Use Tailwind CSS classes. Use `clsx` and `tailwind-merge` for conditional classes.
4.  **Database:** Always update `prisma/schema.prisma` and run `npx prisma generate` if models change.
5.  **Auth:** Use `next-auth` (imported from `@/auth`) for session checks.
6.  **Type Safety:** Ensure no `any` types are used in production code (Admin dashboard currently uses `any` as a temporary patch).

## Commands
*   **Dev:** `npm run dev`
*   **Build:** `npm run build`
*   **Lint:** `npm run lint`
*   **DB Studio:** `npx prisma studio`
