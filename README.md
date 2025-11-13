# Piggery REST API

A comprehensive REST API for piggery management built with CodeIgniter 3.x. Provides endpoints for managing livestock operations, scheduling, accounting, and educational curriculum with JWT authentication.

## 🐷 Features

### Core Management Modules
- **🏫 School Management**: Educational curriculum and scheduling system
- **📊 Dashboard**: Real-time analytics and overview
- **💰 Accounting**: Financial tracking and reporting
- **👤 User Management**: Role-based access control with JWT authentication
- **📅 Scheduling**: Event and activity scheduling
- **📋 Curriculum**: Educational content management
- **📄 Reports**: Printable reports and documentation

### API Features
- RESTful API endpoints
- JWT token-based authentication
- File upload handling (certificates, IDs, pig photos, etc.)
- Cross-platform compatibility
- Comprehensive data validation

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
   -- Import the database schema
   mysql -u your_username -p your_database_name < schema.sql
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
   Navigate to `http://yourdomain.com/` in your browser.

## 📚 API Documentation

### Authentication Endpoints

#### Login
```http
POST /auth/login
Content-Type: application/json

{
    "username": "your_username",
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
        "username": "admin",
        "role": "administrator"
    }
}
```

### Main API Endpoints

| Endpoint | Method | Description | Authentication |
|----------|--------|-------------|----------------|
| `/auth/login` | POST | User authentication | No |
| `/user/*` | GET/POST/PUT/DELETE | User management | JWT |
| `/dashboard/*` | GET | Dashboard data | JWT |
| `/accounting/*` | GET/POST | Financial operations | JWT |
| `/curriculum/*` | GET/POST/PUT | Educational content | JWT |
| `/schedule/*` | GET/POST/PUT/DELETE | Event scheduling | JWT |
| `/school/*` | GET/POST | School operations | JWT |

### File Upload Endpoints

Upload files to various categories:
```http
POST /upload/profile_pic
POST /upload/pig_photo
POST /upload/certificate
Content-Type: multipart/form-data
Authorization: Bearer {jwt_token}
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
piggeryRest/
├── application/
│   ├── config/         # Configuration files
│   ├── controllers/    # API controllers
│   ├── models/         # Data models
│   ├── libraries/      # Custom libraries (JWT, REST, PDF)
│   └── views/          # View templates
├── images/             # File uploads (organized by type)
├── system/             # CodeIgniter core
├── schema.sql          # Database structure
└── README.md           # This file
```

## 🧪 Testing

1. **API Testing**
   Use tools like Postman or curl to test endpoints:
   ```bash
   curl -X POST http://localhost/piggery/auth/login \
        -H "Content-Type: application/json" \
        -d '{"username":"admin","password":"password"}'
   ```

2. **Database Testing**
   Verify database connections and queries work correctly.

## 🛡 Security Considerations

- **Authentication**: JWT tokens for API access
- **Authorization**: Role-based access control
- **Input Validation**: All inputs are sanitized
- **File Upload Security**: Type and size restrictions
- **SQL Injection Prevention**: Using CodeIgniter's Query Builder
- **XSS Protection**: Output escaping enabled

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📋 TODO

- [ ] Add API rate limiting
- [ ] Implement real-time notifications
- [ ] Add data export/import features
- [ ] Mobile app integration
- [ ] Advanced reporting dashboard
- [ ] Multi-language support

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](license.txt) file for details.

## 🆘 Support

For support and questions:
- Create an issue in this repository
- Check the CodeIgniter documentation for framework-specific questions
- Review the API documentation above

## 📊 Database Schema

The system uses the following main database tables:
- `users` - User accounts and authentication
- `breeds` - Pig breed information
- `schedules` - Event and activity scheduling
- `accounting` - Financial records
- `curriculum` - Educational content

See `schema.sql` for complete database structure.

---

**Note**: This is a livestock management system designed for educational and farm management purposes. Ensure compliance with local regulations regarding animal management and data privacy.