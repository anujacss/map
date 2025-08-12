# Map Sanctions Association

This repository is a PHP-based web application for managing and visualizing international sanctions data on a world map. It provides both frontend and backend interfaces for administrators and users to interact with country-specific sanctions information.

## Features
- Interactive world map displaying sanctions by country
- Admin panel for managing sanctions data
- Upload and manage sanction documents (PDF, DOCX)
- Country detail and highlight AJAX endpoints
- Export and backup database functionality
- Dropbox integration for file management

## Directory Structure
- `admin/` - Backend admin panel scripts and uploads
- `asset/` - CSS, JS, and image assets for frontend and backend
- `backups/` - Database export, backup scripts, and Dropbox SDK
- `frontend/` - Frontend AJAX handlers and GeoJSON data
- `uploads/` - Uploaded documents and images
- `connection.php` - Database connection
- `countryList.php` - Country list logic
- `index.php` - Main entry point
- `map.php` - World map visualization

## Setup
1. Clone the repository to your web server directory.
2. Configure database credentials in `connection.php`.
3. Ensure the `uploads/` and `admin/uploads/` directories are writable.
4. Import the database from `backups/database/acssmap_map_YYYY-MM-DD.sql` if needed.
5. Access the application via your web server (e.g., http://localhost/Map-Sanctionsassociation).

## Requirements
- PHP 7.2+
- MySQL/MariaDB
- Web server (Apache, Nginx, etc.)

## Usage
- **Frontend:** View sanctions by country, download documents, and explore the interactive map.
- **Admin:** Log in to manage sanctions, upload new documents, and edit country data.

## Contributing
Pull requests and suggestions are welcome. Please open an issue for bug reports or feature requests.

## License
This project is licensed under the MIT License.
