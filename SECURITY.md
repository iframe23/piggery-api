# Security Policy

## Supported Versions

Use this section to tell people about which versions of your project are currently being supported with security updates.

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within this piggery management system, please send an email to the project maintainers. All security vulnerabilities will be promptly addressed.

**Please do not report security vulnerabilities through public GitHub issues.**

## Security Best Practices

### For Developers

1. **Environment Configuration**
   - Always use environment-specific configuration files
   - Never commit sensitive data (passwords, keys, tokens) to version control
   - Use the provided `.example` configuration files as templates

2. **Database Security**
   - Use strong, unique passwords for database accounts
   - Limit database user permissions to only what's necessary
   - Regularly update database software
   - Enable database connection encryption when possible

3. **JWT Token Security**
   - Use strong, randomly generated secret keys for JWT signing
   - Implement proper token expiration times
   - Consider token refresh mechanisms
   - Store tokens securely on the client side

4. **File Upload Security**
   - Validate file types and sizes before upload
   - Scan uploaded files for malware
   - Store uploads outside the web root when possible
   - Use appropriate file permissions (644 for files, 755 for directories)

5. **Input Validation**
   - Sanitize all user inputs
   - Use parameterized queries to prevent SQL injection
   - Implement proper CSRF protection
   - Enable XSS filtering

### For Production Deployment

1. **Server Configuration**
   ```apache
   # .htaccess example for additional security
   <Files "*.php">
       Require all granted
   </Files>
   
   <Files "*.log">
       Require all denied
   </Files>
   
   # Prevent access to sensitive files
   <FilesMatch "\.(env|ini|log|sql)$">
       Require all denied
   </FilesMatch>
   ```

2. **PHP Configuration**
   ```ini
   # Recommended php.ini settings
   expose_php = Off
   display_errors = Off
   log_errors = On
   session.cookie_httponly = 1
   session.cookie_secure = 1
   session.use_strict_mode = 1
   ```

3. **CodeIgniter Security Settings**
   ```php
   // In application/config/config.php
   $config['csrf_protection'] = TRUE;
   $config['csrf_regenerate'] = TRUE;
   $config['global_xss_filtering'] = TRUE;
   
   // Set strong encryption key (32+ characters)
   $config['encryption_key'] = 'your-very-strong-32-character-key-here';
   ```

4. **Database Security**
   ```sql
   -- Create dedicated database user with minimal privileges
   CREATE USER 'piggery_user'@'localhost' IDENTIFIED BY 'strong_password_here';
   GRANT SELECT, INSERT, UPDATE, DELETE ON piggery.* TO 'piggery_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

### Environment Variables

Consider using environment variables for sensitive configuration:

```bash
# .env file (never commit this)
DB_HOSTNAME=localhost
DB_USERNAME=piggery_user
DB_PASSWORD=your_secure_password
DB_DATABASE=piggery
JWT_SECRET_KEY=your_jwt_secret_key
ENCRYPTION_KEY=your_encryption_key
```

Then load these in your configuration files:
```php
// In database.php
$db['default'] = array(
    'hostname' => getenv('DB_HOSTNAME') ?: 'localhost',
    'username' => getenv('DB_USERNAME') ?: '',
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_DATABASE') ?: '',
    // ... other settings
);
```

### Regular Security Maintenance

1. **Updates and Patches**
   - Keep CodeIgniter framework updated
   - Regular PHP and server software updates
   - Update all third-party libraries
   - Monitor security advisories

2. **Security Monitoring**
   - Enable and monitor application logs
   - Implement intrusion detection
   - Regular security audits
   - Backup and recovery procedures

3. **Access Control**
   - Implement proper user role management
   - Regular review of user permissions
   - Strong password policies
   - Two-factor authentication (when possible)

### Code Review Checklist

Before deploying or merging code, ensure:

- [ ] No hardcoded passwords or sensitive data
- [ ] All user inputs are validated and sanitized
- [ ] SQL queries use parameterized statements
- [ ] File uploads are properly validated
- [ ] Authentication and authorization are properly implemented
- [ ] Error messages don't expose sensitive information
- [ ] Logging doesn't include sensitive data

### Emergency Response

In case of a security incident:

1. **Immediate Actions**
   - Isolate affected systems
   - Preserve evidence and logs
   - Assess the scope of the breach
   - Notify relevant stakeholders

2. **Containment**
   - Change all passwords and API keys
   - Update security configurations
   - Apply necessary patches
   - Monitor for continued threats

3. **Recovery**
   - Restore from clean backups if necessary
   - Verify system integrity
   - Update security measures
   - Document lessons learned

## Security Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CodeIgniter Security Guidelines](https://codeigniter.com/user_guide/general/security.html)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [MySQL Security Guidelines](https://dev.mysql.com/doc/refman/8.0/en/security-guidelines.html)

---

**Remember**: Security is an ongoing process, not a one-time setup. Regularly review and update your security measures.