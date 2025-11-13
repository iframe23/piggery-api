@echo off
echo ============================================
echo Piggery Management System - Setup Script
echo ============================================
echo.

echo Step 1: Creating configuration files from examples...
if not exist "application\config\database.php" (
    copy "application\config\database.example.php" "application\config\database.php"
    echo Created database.php from example
) else (
    echo database.php already exists
)

if not exist "application\config\config.php" (
    copy "application\config\config.example.php" "application\config\config.php"
    echo Created config.php from example
) else (
    echo config.php already exists
)

echo.
echo Step 2: Setting up directory structure...
echo Directory structure is ready with .gitkeep files

echo.
echo ============================================
echo Setup Complete!
echo ============================================
echo.
echo Next steps:
echo 1. Edit application\config\database.php with your database credentials
echo 2. Edit application\config\config.php with your base URL and encryption key
echo 3. Import schema.sql into your MySQL database
echo 4. Configure your web server to point to this directory
echo.
echo For detailed instructions, see README.md
echo.
pause