# CargoTrace — Global Shipment Visibility and Tracking Platform

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tests](https://img.shields.io/badge/PHPUnit-30%20Passed%20(100%25)-10B981?style=for-the-badge&logo=php&logoColor=white)](https://phpunit.de)

> **Final Project — Web Programming 2**  
> A global ocean and intermodal freight tracking platform engineered with Laravel 11, the **Service Layer Pattern**, **MySQL**, an interactive **Leaflet.js Radar Map**, live port telemetry via **Open-Meteo API**, and a token-authenticated **Laravel Sanctum REST API**.

---

## 1. Problem Statement and Solution

In maritime and intermodal logistics:
- Shipment telemetry data is often fragmented across disparate carriers, making centralized dispatching difficult.
- Customers lack end-to-end transparency regarding their cargo milestones and estimated arrival times.
- Transit delays caused by severe weather, customs holds, or vessel mechanical issues are rarely computed deterministically in real time.

**CargoTrace** addresses these operational bottlenecks through:

1. **Strict Lifecycle State Machine**:
   Shipment status transitions follow a rigorous sequence: `CREATED` -> `BOOKED` -> `PICKED_UP` -> `IN_TRANSIT` -> `DELIVERED`. The terminal status (`DELIVERED`) is locked at the database and service layer to prevent illegal rollbacks.

2. **Deterministic Delay and ETA Engine**:
   Calculates deviations between estimated arrival and expected schedules, categorizing delay severity objectively:
   - **LOW**: Delay of 0 to 6 hours
   - **MEDIUM**: Delay of >6 to 24 hours
   - **HIGH**: Delay exceeding 24 hours

3. **Interactive Maritime Dispatch Workbench**:
   Ocean corridors and vessel positions are plotted on an interactive **Leaflet.js** map with automatic viewport panning (*flyTo*) upon selecting any shipment record.

4. **Live Port Weather Integration**:
   Retrieves live temperature, wind speed, and weather conditions for origin and destination ports via the **Open-Meteo API**, protected by a 3-second timeout and offline fallback handling.

5. **Role-Based Access Control (RBAC)**:
   Strict server-side policy enforcement separating **Administrator**, **Logistics Operator**, and **Customer** roles, including complete protection against Insecure Direct Object References (IDOR).

6. **Industry-Standard REST API**:
   Fully authenticated endpoints secured with **Laravel Sanctum Bearer Tokens** and standardized JSON responses.

---

## 2. System Architecture: Service Layer Pattern

Business rules are decoupled from HTTP controllers to adhere to the Single Responsibility Principle:

```
[Web Browser / Postman Client]
               │
               ▼
   [Routes & Middleware (auth, can)]
               │
               ▼
    [Controllers (HTTP Handling & Input Validation Only)]
               │
               ▼
       [Service Layer (Reusable Domain Logic)]
       ├── ShipmentService (Lifecycle state validation & shipment numbering)
       ├── TrackingService (Chronological event logging & coordinates)
       ├── EtaService (Mathematical delay calculations & severity)
       └── WeatherService (HTTP client, timeout thresholds, & fallback data)
               │
               ▼
[Eloquent Models & MySQL Database]
```

---

## 3. Demonstration Credentials

All pre-seeded test accounts use the default password: **`password`**

| Role | Email Address | Access Scope |
| :--- | :--- | :--- |
| **Administrator** | `admin@cargotrace.com` | Complete access to shipments, carriers, locations, and audit logs |
| **Logistics Operator** | `operator@cargotrace.com` | Manages operational shipments, creates voyages, logs checkpoints, and resolves exceptions |
| **Customer** | `budi@agrologistics.com` | Restricted to viewing own cargo records only (IDOR-guarded) |

---

## 4. Local Installation Guide

### System Requirements
- PHP 8.2 or 8.3
- Composer 2.x
- MySQL or MariaDB (via XAMPP)
- Modern Web Browser (Chrome, Edge, Firefox)

### Setup Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/zahraism/CargoTrace.git
   cd CargoTrace/cargotrace
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Configure Environment Variables**
   Verify the database parameters in your `.env` file match your local MySQL configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cargotrace
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations and Database Seeders**
   php artisan migrate:fresh --seed
   ```

5. **Start the Local Development Server**
   ```bash
   php artisan serve
   ```
   Open your browser and navigate to: **`http://127.0.0.1:8000`**

---

## 5. Automated Testing

The application includes automated unit and feature test suites covering authentication, lifecycle transition rules, IDOR security policies, and REST API contracts:

```bash
php artisan test
```

**Test Results: 30 Passed (82 Assertions) — 100% Green**  
*Configured to run on in-memory SQLite to prevent altering development seed data.*

---

## 6. REST API Endpoint Reference

Base URL: `http://127.0.0.1:8000/api`

### 1. Authentication
- `POST /api/register` — Register a new customer account
- `POST /api/login` — Authenticate and issue a Sanctum Bearer Token
- `POST /api/logout` — Revoke the current access token (Requires `Bearer Token`)
- `GET /api/user` — Retrieve profile data for the authenticated user (Requires `Bearer Token`)

### 2. Shipments and Tracking
- `GET /api/shipments` — Retrieve paginated shipment manifest (supports status filtering)
- `POST /api/shipments` — Register a new shipment record (Operator and Admin only)
- `GET /api/shipments/{id}` — Retrieve detailed view of a single shipment
- `GET /api/shipments/{id}/tracking` — Retrieve chronological tracking event history
- `POST /api/shipments/{id}/tracking` — Append a new tracking checkpoint with GPS coordinates

A ready-to-import Postman collection file is available in the root directory: `cargotrace_postman_collection.json`.

---

## 7. License

This project was developed for academic evaluation purposes and is licensed under the [MIT License](LICENSE).
