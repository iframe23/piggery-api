# Piggery Management REST API

A comprehensive REST API for piggery farm management built with CodeIgniter 3.x. Designed specifically for managing pig farms, livestock operations, breeding programs, feeding schedules, and farm business operations with JWT authentication.

## 🐷 Features

### Core Piggery Management Modules
- **🐖 Pig Management**: Individual pig tracking, health records, and breeding history
- **🏭 Farm Operations**: Facility management, pen assignments, and equipment tracking
- **📊 Dashboard**: Real-time farm analytics, production metrics, and health monitoring
- **💰 Farm Accounting**: Financial tracking, feed costs, sales records, and profitability analysis
- **👤 User Management**: Role-based access control for farm workers and managers with JWT authentication
- **📅 Scheduling**: Feeding schedules, breeding programs, vaccination reminders, and farm activities
- **🍽️ Feed Management**: Feed inventory, nutrition tracking, and feeding schedules
- **📄 Reports**: Production reports, health certificates, and breeding documentation

### API Features
- RESTful API endpoints for all farm operations
- JWT token-based authentication for secure access
- File upload handling (pig photos, health certificates, breeding records, etc.)
- Cross-platform compatibility for mobile and web applications
- Comprehensive data validation for livestock data integrity

### File Management
The system handles various farm documentation:
- Pig identification photos
- Health and vaccination certificates
- Breeding records and genealogy
- Feed quality certificates
- Facility inspection documents
- Sales and transfer records
- Insurance and compliance documentation

## 🚀 Quick Start

### Prerequisites
- PHP 7.0 or higher
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- Composer (optional, for dependencies)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/iframe23/piggery-api.git
   cd piggery-api
   ```

2. **Set up the database**
   ```sql
   -- Create database and import the schema
   CREATE DATABASE piggery;
   mysql -u your_username -p piggery < schema.sql
   ```

3. **Configure the application**
   ```bash
   # Copy example configuration files
   cp application/config/database.example.php application/config/database.php
   cp application/config/config.example.php application/config/config.php
   ```

4. **Update configuration files**
   
   **Database Configuration** (`application/config/database.php`):
   ```php
   $db['default'] = array(
       'hostname' => 'localhost',
       'username' => 'your_db_username',
       'password' => 'your_db_password',
       'database' => 'piggery',
       // ... other settings
   );
   ```

   **Base Configuration** (`application/config/config.php`):
   ```php
   $config['base_url'] = 'http://yourdomain.com/';
   $config['encryption_key'] = 'your-32-character-secret-key';
   ```

5. **Set directory permissions**
   ```bash
   # Make upload directories writable
   chmod 755 images/
   chmod 755 application/logs/
   chmod 755 application/cache/
   ```

6. **Access the application**
   Navigate to `http://yourdomain.com/` in your browser to access the piggery management system.

## 📚 API Documentation

### Authentication Endpoints

#### Farm User Login
```http
POST /auth/login
Content-Type: application/json

{
    "username": "farm_manager",
    "password": "your_password"
}
```

Response:
```json
{
    "status": "success",
    "token": "jwt_token_here",
    "user_data": {
        "id": 1,
        "username": "farm_manager",
        "role": "manager"
    }
}
```

### Main API Endpoints

| Endpoint | Method | Description | Authentication |
|----------|--------|-------------|----------------|
| `/auth/login` | POST | Farm user authentication | No |
| `/user/*` | GET/POST/PUT/DELETE | Farm staff management | JWT |
| `/dashboard/*` | GET | Farm dashboard data & analytics | JWT |
| `/accounting/*` | GET/POST | Farm financial operations | JWT |
| `/pig/*` | GET/POST/PUT/DELETE | Individual pig management | JWT |
| `/schedule/*` | GET/POST/PUT/DELETE | Farm activity scheduling | JWT |
| `/feed/*` | GET/POST/PUT | Feed inventory & management | JWT |

### File Upload Endpoints

Upload farm-related files:
```http
POST /upload/pig_photo
POST /upload/health_certificate
POST /upload/breeding_record
Content-Type: multipart/form-data
Authorization: Bearer {jwt_token}
```

### Pig Management Endpoints

**Get all pigs:**
```http
GET /pig/list
Authorization: Bearer {jwt_token}
```

**Add new pig:**
```http
POST /pig/create
Content-Type: application/json
Authorization: Bearer {jwt_token}

{
    "pig_id": "PIG001",
    "breed_id": 1,
    "birth_date": "2023-05-15",
    "pen_number": "A-12",
    "status": "healthy"
}
```

**Update pig information:**
```http
PUT /pig/update/{pig_id}
Content-Type: application/json
Authorization: Bearer {jwt_token}

{
    "weight": 85.5,
    "health_status": "healthy",
    "last_checkup": "2023-11-10"
}
```

## 🔧 Configuration

### Environment Setup

For production deployment:

1. **Security Settings**
   - Set `ENVIRONMENT` to `production` in `index.php`
   - Enable CSRF protection in `config.php`
   - Use HTTPS in production
   - Set strong encryption keys

2. **Database Optimization**
   - Configure database connection pooling
   - Set appropriate cache settings
   - Regular database maintenance

3. **File Upload Security**
   - Validate file types and sizes
   - Scan uploads for malware
   - Limit upload directory access

### JWT Configuration

Update `application/libraries/ImplementJWT.php` for JWT settings:
- Token expiration time
- Secret key for signing
- Refresh token logic

## 🗂 Project Structure

```
piggery-api/
├── application/
│   ├── config/         # Configuration files
│   ├── controllers/    # API controllers (Pig, Feed, Schedule, etc.)
│   ├── models/         # Data models (Pig_model, Feed_model, etc.)
│   ├── libraries/      # Custom libraries (JWT, REST, PDF)
│   └── views/          # View templates for reports
├── images/             # Farm file uploads (organized by type)
│   ├── pig_pics/       # Individual pig photos
│   ├── certifications/ # Health and breeding certificates
│   └── ...            # Other farm documentation
├── system/             # CodeIgniter core
├── schema.sql          # Database structure for piggery
└── README.md           # This file
```

## 🧪 Testing

1. **API Testing**
   Use tools like Postman or curl to test endpoints:
   ```bash
   # Test farm manager login
   curl -X POST http://localhost/piggery-api/auth/login \
        -H "Content-Type: application/json" \
        -d '{"username":"farm_manager","password":"password"}'
   
   # Get pig list (requires JWT token)
   curl -X GET http://localhost/piggery-api/pig/list \
        -H "Authorization: Bearer YOUR_JWT_TOKEN"
   ```

2. **Database Testing**
   Verify database connections and pig data queries work correctly.

## 🛡 Security Considerations

- **Authentication**: JWT tokens for secure farm API access
- **Authorization**: Role-based access control (farm managers, workers, veterinarians)
- **Data Protection**: All pig and farm data inputs are sanitized
- **File Upload Security**: Restricted file types for farm documentation
- **SQL Injection Prevention**: Using CodeIgniter's Query Builder for database operations
- **Farm Data Privacy**: XSS protection enabled for sensitive livestock information

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📋 TODO

- [ ] Add pig health monitoring alerts
- [ ] Implement breeding program automation
- [ ] Add feed consumption analytics
- [ ] Mobile app integration for farm workers
- [ ] Advanced pig growth tracking dashboard
- [ ] Integration with veterinary systems
- [ ] Automated feeding schedule optimization
- [ ] Market price integration for sales planning

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](license.txt) file for details.

## 🆘 Support

For support and questions:
- Create an issue in this repository
- Check the CodeIgniter documentation for framework-specific questions
- Review the API documentation above

## 📊 Database Schema

The system uses the following main database tables for piggery management:
- `pigs` - Individual pig records, health status, and tracking
- `breeds` - Pig breed information and characteristics
- `pens` - Farm facility and pen management
- `feeds` - Feed inventory and nutrition data
- `schedules` - Feeding schedules, breeding programs, and farm activities
- `health_records` - Vaccination records, medical treatments, and health monitoring
- `accounting` - Farm financial records, feed costs, and sales data
- `users` - Farm staff accounts and access control

See `schema.sql` for complete database structure designed for pig farm operations.

---

**Note**: This is a comprehensive pig farm management system designed specifically for piggery operations. The API handles all aspects of pig farming including livestock tracking, breeding programs, feed management, health monitoring, and farm business operations. Ensure compliance with local agricultural regulations and animal welfare standards.