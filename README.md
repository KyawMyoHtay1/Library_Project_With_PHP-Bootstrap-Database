# Libraria Deployment Notes

This project can be deployed to Railway as a plain PHP application.

## What is already prepared

- `dbconnect.php` now reads Railway-friendly MySQL environment variables.
- `railway.json` tells Railway to use Railpack and check `/health.php`.
- `health.php` returns HTTP 200 for Railway health checks.

## Railway setup

1. Push this repository to GitHub.
2. In Railway, create a new project and deploy this repository as a service.
3. Add a MySQL service to the same Railway project.
4. In the web service's Variables tab, add reference variables from the MySQL service for:
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`
   - `MYSQLDATABASE`
   - Optional: `MYSQL_URL`
5. Import your local `librarydb` schema and data into the Railway MySQL database.
6. Redeploy the web service if Railway does not trigger a deploy automatically after variables are added.

## Important caveats

- There is no SQL dump in this repository, so you will need to export your local `librarydb` database and import it into Railway manually.
- Book image uploads are stored in `bookimage/`. Railway's filesystem is ephemeral, so new uploads can disappear after redeploys unless you move uploads to persistent storage.
- The safest long-term option for uploads is object storage such as S3 or Cloudinary. A Railway volume is possible too, but you would need to seed your existing `bookimage/` files carefully before relying on it in production.

## Local development

If you want to keep using local MySQL, create environment variables that match `.env.example`, or keep a local MySQL database available at:

- host: `127.0.0.1`
- port: `3306`
- user: `root`
- password: empty
- database: `librarydb`
