# DOPAS — Dengue Outbreak Prediction and Alert System

DOPAS is a PHP/MySQL web application with Python-based dengue case prediction for Petaling District.

## Local setup (XAMPP)

1. Copy the project folder into `xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import `dopas.sql` to create the database schema. The SQL file is schema-only; original table data has been omitted.
4. Check `db.php` and update the database connection values for your local setup.
5. Install Python dependencies listed in `requirements.txt` in the Python environment used by the prediction scripts.
6. Configure any mail credentials in your local environment before testing alert email functionality. Do not commit passwords or API keys.
7. Open the project through `http://localhost/PSM/` (adjust the URL to match your folder name).

## Main files

- `login&registration.*`, `login.php`, `registration.php` — authentication
- `publicdashboard.php` and `admindashboard.php` — public and admin views
- `climatedataform.*` and `denguecaseform.*` — data entry
- `prediction.py` and `model/` — prediction scripts and saved model
- `dopas.sql` — database schema

## Notes

This repository is intended for demonstration and academic review. Review database credentials, email settings, and deployment security before hosting publicly.
