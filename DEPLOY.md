# Deployment guide — recommended workflow

This project can be deployed quickly on Render's free plan using SQLite (configured by default in `render.yaml`). For production-like behavior you should use an external PostgreSQL database. Below are two clear paths:

1) Quick demo (free): SQLite — already wired in the repo. Good for demos and testing.

2) Recommended (best): External Postgres (Supabase or Neon) — free tiers available and behave like a real DB.

---

## Recommended: Use Supabase (free tier)

Supabase provides a managed Postgres instance and is straightforward to connect.

1. Create a Supabase account: https://supabase.com
2. Create a new project and take note of the connection string details (Host, Port, Database, User, Password).
   - In Supabase UI: Project -> Settings -> Database -> Connection string or connection info.
3. In Render dashboard for your web service, set the following environment variables (or add them to `render.yaml` but be careful with secrets):
   - `DB_CONNECTION=pgsql`
   - `DB_HOST=<your-host-from-supabase>`
   - `DB_PORT=5432` (usually)
   - `DB_DATABASE=<your-database>`
   - `DB_USERNAME=<your-username>`
   - `DB_PASSWORD=<your-password>`
4. Redeploy the web service on Render. The container's entrypoint will wait for the DB and run migrations.
5. Monitor the logs for migration success.

Notes:
- This gives you a real Postgres DB while remaining free for small usage.
- If you want to use SSL mode or a DATABASE_URL, you can also set `DATABASE_URL` in Render to the full connection string.

---

## Quick demo: Use SQLite (default)

If you want a zero-friction deploy on Render's free plan, the repo is already configured to use SQLite:

- `render.yaml` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=/var/www/html/database/database.sqlite`.
- The container `docker-entrypoint.sh` will create the SQLite file at runtime and run migrations.

Limitations:
- SQLite is fine for demos and single-instance setups but not recommended for production or multi-instance apps.

---

## If you prefer another managed DB provider

Other free/low-cost providers:
- Neon (https://neon.tech)
- ElephantSQL (https://www.elephantsql.com)
- Railway (https://railway.app)

The steps are the same: create a DB instance, copy connection details, add them to the Render service env vars, then redeploy.

---

## Troubleshooting

- If you see `Connection refused` pointing to `127.0.0.1:5432`, it means the app attempted to connect to Postgres but couldn't find it. Ensure your `DB_*` env vars point to the external DB host (not 127.0.0.1 which is the container itself).
- If migrations fail at startup, check the Render logs and the container entrypoint output. You can remove the `|| true` from the migration command in `docker-entrypoint.sh` if you want the container to stop on migration errors (safer to detect failures).

---

If you'd like, I can:
- Walk you through creating a Supabase DB and add exact `render.yaml` lines with placeholders you can copy/paste.
- Revert to Postgres automatically in the repo (update `render.yaml` to your Supabase credentials) — you'll need to supply the credentials or add them in Render's dashboard.

Tell me if you want me to (A) prepare a `render.yaml` snippet pre-filled for Supabase (you'll paste your credentials), or (B) keep SQLite and remove `|| true` from migrations so failures stop the container.
