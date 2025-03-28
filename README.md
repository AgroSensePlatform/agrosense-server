# AgroSense Backend

AgroSense is a backend application designed to support farmers in smart water management using IoT technologies and mobile devices. This server is built with Laravel and provides RESTful APIs for user management, farm management, sensor registration, and real-time measurement tracking.

---

## Features

### User Management
- User registration and login using email and password.
- Token-based authentication using Laravel Sanctum.
- Logout functionality.

### Farm Management
- Create, update, view, and delete farms.
- Store farm boundaries using GPS coordinates.

### Sensor Management
- Register sensors by scanning QR codes.
- Automatically associate sensors with farms.
- Update sensor details when scanned again.
- View and delete sensors.

### Measurement Management
- Post real-time measurements (e.g., humidity) from sensors.
- Store measurements in the database for analysis.

---

## Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Laravel 10.x

### Steps
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd agrosense-server
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the .env file and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run the migrations to set up the database:
   ```bash
   php artisan migrate
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

---

## API Endpoints

### Authentication
- `POST /register` - Register a new user.
- `POST /login` - Log in and receive an authentication token.
- `POST /logout` - Log out the authenticated user.
- `GET /user` - Get the authenticated user's details.

### Farms
- `GET /farms` - List all farms for the authenticated user.
- `POST /farms` - Create a new farm.
- `GET /farms/{farm}` - View a specific farm.
- `PUT /farms/{farm}` - Update a specific farm.
- `DELETE /farms/{farm}` - Delete a specific farm.

### Sensors
- `GET /sensors` - List all sensors for the authenticated user.
- `POST /sensors` - Add a new sensor.
- `POST /sensors/scan` - Create or update a sensor by scanning its QR code.
- `GET /sensors/{sensor}` - View a specific sensor.
- `PUT /sensors/{sensor}` - Update a specific sensor.
- `DELETE /sensors/{sensor}` - Delete a specific sensor.

### Measurements
- `POST /measurements` - Post a measurement from a sensor.

---

## Running Tests

To run the test suite, use the following command:

```bash
php artisan test
```

The test suite includes:
- Unit tests for models and relationships.
- Feature tests for API endpoints.

---

## Project Structure

- **Models**:
  - `User`: Handles user data and authentication.
  - `Farm`: Represents farms and their boundaries.
  - `Sensor`: Represents IoT sensors associated with farms.
  - `Measurement`: Stores real-time data from sensors.

- **Controllers**:
  - `AuthController`: Manages user authentication.
  - `FarmController`: Handles CRUD operations for farms.
  - `SensorController`: Manages sensor registration and updates.
  - `MeasurementController`: Handles posting of sensor measurements.

- **Policies**:
  - Authorization policies ensure users can only interact with their own farms and sensors.

---

## Database Schema

### Users
| Column           | Type       | Description              |
|-------------------|------------|--------------------------|
| id               | BIGINT     | Primary key              |
| name             | STRING     | User's name              |
| email            | STRING     | User's email (unique)    |
| password         | STRING     | User's hashed password   |
| email_verified_at| TIMESTAMP  | Email verification time  |
| remember_token   | STRING     | Token for "remember me"  |
| timestamps       | TIMESTAMP  | Created/updated times    |

### Farms
| Column      | Type       | Description                     |
|-------------|------------|---------------------------------|
| id          | BIGINT     | Primary key                     |
| user_id     | BIGINT     | Foreign key to `users` table    |
| name        | STRING     | Farm name                       |
| coordinates | JSON       | GPS coordinates of the farm     |
| timestamps  | TIMESTAMP  | Created/updated times           |

### Sensors
| Column      | Type       | Description                     |
|-------------|------------|---------------------------------|
| id          | BIGINT     | Primary key                     |
| user_id     | BIGINT     | Foreign key to `users` table    |
| farm_id     | BIGINT     | Foreign key to `farms` table    |
| code        | STRING     | Unique sensor code              |
| lat         | DECIMAL    | Latitude of the sensor          |
| lon         | DECIMAL    | Longitude of the sensor         |
| timestamps  | TIMESTAMP  | Created/updated times           |

### Measurements
| Column      | Type       | Description                     |
|-------------|------------|---------------------------------|
| id          | BIGINT     | Primary key                     |
| sensor_id   | BIGINT     | Foreign key to `sensors` table  |
| humidity    | DECIMAL    | Humidity value (0-100%)         |
| timestamp   | TIMESTAMP  | Time of the measurement         |
| timestamps  | TIMESTAMP  | Created/updated times           |

---

## Future Enhancements
- Add real-time notifications for low humidity levels.
- Implement historical data visualization with charts.
- Add support for offline data synchronization.

---

## License
This project is open-source and available under the MIT License.

---
