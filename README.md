# Nucleus Starter

Type-safe SaaS boilerplate combining a **Next.js 14** frontend with a **Symfony** API backend. Built for projects that need JWT authentication, role-based access control, and a consistent error-handling pipeline from day one — without the usual setup overhead.

## Stack

- **Frontend:** Next.js 14 (App Router), TypeScript (strict mode), Tailwind CSS
- **Backend:** Symfony 7, Doctrine ORM, LexikJWTAuthenticationBundle
- **Tooling:** ESLint, Prettier, GitHub Actions CI

## Features

- 🔐 **JWT Authentication** — access/refresh token flow with automatic expiry handling
- 🛡️ **Role-Based Middleware** — route protection via `middleware.ts` (frontend) and Voters (backend)
- ⚠️ **Centralized Error Handling** — typed API errors propagate cleanly into a toast notification system
- 🧱 **Type-Safe Service Layer** — no raw `fetch` calls in components; all API access goes through `services/`
- 📦 **Opinionated Folder Structure** — `hooks/`, `services/`, `types/`, `constants/` separation enforced

## Project Structure

```
frontend/
├── src/
│   ├── app/
│   ├── hooks/
│   ├── services/       # type-safe API layer
│   ├── types/
│   ├── constants/
│   └── middleware.ts    # route guarding logic

backend/
├── src/
│   ├── Entity/
│   ├── Security/       # JWT + Voters
│   └── Controller/
```

## Getting Started

```bash
# Frontend
cd frontend
npm install
npm run dev

# Backend
cd backend
composer install
symfony server:start
```

Copy `.env.example` to `.env` in both directories and configure your JWT keys and database connection before running.

## Why This Exists

Most starter kits either skip auth entirely or hardcode it in a way that's painful to extend. Nucleus keeps the auth and error-handling logic isolated and typed, so extending it with new roles, endpoints, or notification types doesn't mean rewriting the core.

## License

MIT
