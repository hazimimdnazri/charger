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
cd charger
```
2. Setting up the API, create a copy of .env.example with the name .env:
```
cd api
cp .env.example .env
```

3. Make sure the configuration are as follows:
```
DB_CONNECTION=mysql
DB_HOST=charger-db
DB_PORT=3306
DB_DATABASE=evcharge
DB_USERNAME=root
DB_PASSWORD=123456

OCPP_URL="http://charger-ocpp:3000"
OCPP_SECRET="hazimimdnazri"
```

4. Buld and start the services as daemon:
```bash
docker-compose up -d --build
```

5. Run database migrations (remove the `migrate` if you only want to serve the API):
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
- OCPP Server: Simulate communication with electric vehicle charging stations
- Web Interface: Vue.js-based web application for managing charging stations
- Database: MariaDB for storing charging station data

Environment Variables:
- Laravel .env should reference the database connection
- Docker-compose.yml includes DB_HOST=db, DB_PORT=3306, and MYSQL_ROOT_PASSWORD=123456, MYSQL_DATABASE=evcharge

Common Commands:
- Starting services: docker-compose up
- Running migrations: docker exec charger-api sh run.sh migrate (Remove the `migrate` if you only want to serve the API)

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
- Port: 3000 (Internally)
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

# API Unit Tests #

To run unit tests against the API, run this.
```
docker exec charger-api php artisan test
```

# Using the Frontend #

Go to `localhost:3000` to access the frontend.

There are 3 users available to use from the start. You can log into as one of the users:

```
email: admin@example.com
password: 123456
role: admin

email: john@example.com
password: 123456
role: user/customer

email: jane@example.com
password: 123456
role: user/customer
```

The admin is able to view customers and chargers list. The admin will be able to add charger and control any charging sessions.
The user will be only allowed to view his/her own charging sessions. You may click on the `Start New Session` to initiate a charging session.
Click on the `Disconnect` button to stop the charging session.

# Accessing API via Postman #

Several API endpoints are available based on the `api.php` route.
Set the `Accept` header to `application/json`.