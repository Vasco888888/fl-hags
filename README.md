# FL\HAGS - Freelance \ Home And Garden Services

FL\HAGS is a specialized marketplace platform designed to connect freelancers offering home and garden services with clients. The system facilitates service discovery, order management, and direct communication between users.

## Project Overview

This project was developed as part of the **Linguagens e Tecnologias Web (LTW)** course at the **Faculty of Engineering of the University of Porto (FEUP)**.

- **Group ID:** ltw02g01

## Features

### General Users
- Secure account registration and authentication.
- Full profile management, including personal details and credentials.

### Freelancers
- Detailed service listings including categories, pricing, descriptions, and media support.
- Centralized management of offered services and orders.
- Direct communication channel with clients to discuss requirements and provide custom offers.
- Capability to mark services as completed upon delivery.

### Clients
- Advanced service browsing with filters for categories, price ranges, and ratings.
- Real-time messaging with freelancers for inquiries and custom orders.
- Streamlined service hiring and simulated checkout process.
- Rating and review system to provide feedback on completed services.

### Administrators
- Administrative dashboard for system oversight.
- User management and role elevation (Admin status).
- Management of service categories and global system entities.

## Technologies Used

- **Back-end:** PHP (MVC Architecture)
- **Database:** SQLite
- **Front-end:** HTML5, CSS3, JavaScript
- **Server:** Built-in PHP Development Server

## Getting Started

### Prerequisites
- PHP (version 7.4 or higher recommended)
- SQLite3

### Installation and Execution
1. Initialize the SQLite database:
   ```bash
   ./bin/sqlite3 App/Database/database.db < App/Database/database.sql
   ```
2. Launch the development server:
   ```bash
   php -S localhost:9000 -t public
   ```
3. Open your browser and navigate to: `http://localhost:9000`

## Demo Credentials

For testing and demonstration purposes, all accounts share a common password.

**Default Password:** `test`

### Administrative Accounts
- `admin1`
- `admin2`

### Freelancer Accounts
- `freelancer1` through `freelancer10`

### Client Accounts
- `client1` through `client15`

## Credits

This project was developed by:

- **João Júnior** (up202306719)
- **Mário Pereira** (up202304965)
- **Vasco Sá** (up202306731)

---
FEUP - LTW 2025

