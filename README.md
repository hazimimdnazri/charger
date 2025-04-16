# EV Charger Management System

A Docker-based solution for managing electric vehicle charging stations, featuring:
- REST API (Laravel/PHP)
- OCPP Server (Node.js)
- Web Interface (Vue.js)
- MariaDB Database

## Prerequisites

- Docker
- Docker Compose
- Node.js 18+ (for local development)

## Getting Started

1. Clone the repository:
```bash
git clone https://github.com/hazimimdnazri/charger.git
cd evcharger
```

2. Start the services:
```bash
docker-compose up --build
```

3. Run database migrations:
```bash
docker exec charger-api sh run.sh migrate
```

Services will be available at:
- API: http://localhost:3001
- Web App: http://localhost:3000
- OCPP Server: Internally
- Database: localhost:3308 (user: root, pass: 123456)

## Project Structure

Services Overview:
- API: REST API for managing charging stations
- OCPP Server: Handles communication with electric vehicle charging stations
- Web Interface: Vue.js-based web application for managing charging stations
- Database: MariaDB for storing charging station data

Environment Variables:
- Laravel .env should reference the database connection
- Docker-compose.yml includes DB_HOST=db, DB_PORT=3306, and MYSQL_ROOT_PASSWORD=123456, MYSQL_DATABASE=evcharge

Common Commands:
- Starting services: docker-compose up
- Running migrations: docker exec charger-api sh run.sh migrate

Notes:
- Ports: API runs on 3001, OCPP Server on 3000, Web Interface on 3000 mapped to 4000, Database on 3308
- Volumes: App and OCPP services have volumes that mount the local code into the container
- Technologies: Laravel, Vue.js, MariaDB, Docker

## Services Overview

### 1. API Service (Laravel)
- Port: 3001 → 8000
- Environment: PHP 8.2
- Features:
  - User authentication
  - Charging station management
  - Transaction processing

### 2. OCPP Service (Node.js)
- Port: 3000
- Implements OCPP 1.6 protocol
- Handles direct communication with charging stations

### 3. Web App (Vue.js)
- Port: 3000 → 4000
- Modern web interface
- Real-time monitoring
- User dashboard

### 4. Database (MariaDB)
- Port: 3308 → 3306
- Persistent storage
- Database: evcharge
- Credentials: root/123456

## Environment Configuration

Create `.env` file in the API directory:
```ini
DB_CONNECTION=mariadb
DB_HOST=db
DB_PORT=3306
DB_DATABASE=evcharge
DB_USERNAME=root
DB_PASSWORD=123456
```

## Common Commands

Start services in detached mode:
```bash
docker-compose up -d --build
```

View running containers:
```bash
docker ps
```

Follow API logs:
```bash
docker logs -f charger-api
```

Run artisan commands:
```bash
docker exec charger-api php artisan [command]
```

Stop all services:
```bash
docker-compose down
```

## Development Notes

- Hot-reloading enabled for frontend (Vue.js) and OCPP server
- Database persists in `docker/mariadb` directory
- API includes automatic migration/seed setup
- Use `yarn dev` for frontend development

---

**Technologies Used:** Laravel 12, Vue.js 3, MariaDB 11, Docker

This README provides a comprehensive overview of the project, including prerequisites, getting started instructions, project structure, services overview, environment variables, and common commands. It's designed to be clear and easy to navigate, with code blocks for commands and structured sections for easy navigation.