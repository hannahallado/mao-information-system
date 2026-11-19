# AgriMap - Municipal Agriculture Office Information System (raw PHP MVC starter)

Prototype scope: front-end screens for all six DFD processes; **functional** for
1.0 Registry, 5.0 Programs and Assistance, and the priority reports
(Farmer/Fisherfolk Masterlist, Beneficiary Assistance History).

## Run locally
1. Copy `.env.example` to `.env` and set DB credentials.
2. Import `database/schema_base.sql` (users + id_sequences), then add the D1-D5 tables from the ERD.
3. Create a login: `php database/create_user.php admin "MAO Agriculturist" yourpassword agriculturist all`
4. Start: `php -S localhost:8000 -t public public/index.php`  (or point XAMPP/Laragon at `public/`)
5. Open http://localhost:8000/login

If the project sits inside `htdocs`, set `BASE_URL=/agrimap/public` in `.env`.

## How the stubs work
Every sidebar item already has a route and a controller method that shows a placeholder
screen (`views/placeholder.php`) listing what the checklist expects on that screen.
Replace `$this->mockup([...])` with `$this->view('your/view', [...])` as you build.

- Sidebar: `config/menu.php`          - Routes: `config/routes.php`
- Report catalogue: `config/reports.php`
- Shared IDs: `app/services/IdGenerator.php` (`farmerId()`, `programId()`, `assistanceId()`)

## Check for dead ends (no database needed)
`sh tests/check_all_routes.sh` visits every sidebar item and sample detail pages as both roles
and prints 200 / 403 / 404 for each.
