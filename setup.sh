#!/bin/bash

echo "============================================"
echo "Piggery Management System - Setup Script"
echo "============================================"
echo ""

echo "Step 1: Creating configuration files from examples..."
if [ ! -f "application/config/database.php" ]; then
    cp "application/config/database.example.php" "application/config/database.php"
    echo "Created database.php from example"
else
    echo "database.php already exists"
fi

if [ ! -f "application/config/config.php" ]; then
    cp "application/config/config.example.php" "application/config/config.php"
    echo "Created config.php from example"
else
    echo "config.php already exists"
fi

echo ""
echo "Step 2: Setting directory permissions..."
chmod 755 images/
chmod 755 application/logs/
chmod 755 application/cache/
echo "Permissions set"

echo ""
echo "============================================"
echo "Setup Complete!"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Edit application/config/database.php with your database credentials"
echo "2. Edit application/config/config.php with your base URL and encryption key"
echo "3. Import schema.sql into your MySQL database:"
echo "   mysql -u username -p database_name < schema.sql"
echo "4. Configure your web server to point to this directory"
echo ""
echo "For detailed instructions, see README.md"
echo ""