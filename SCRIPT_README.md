# Laravel Auto-Setup Script Usage Guide

## Files Created
- `start-laravel.ps1` - Main PowerShell script
- `start-laravel.bat` - Batch file wrapper for easier execution

## How to Use

### Option 1: Run PowerShell Script Directly
```powershell
# Navigate to the project folder first
cd "C:\PROJECTS\SOFTWARE ENGINEERING\NearU\NearU-Laravel"

# Run the script
powershell -ExecutionPolicy Bypass -File start-laravel.ps1
```

### Option 2: Use the Batch File (Easier)
```cmd
# Double-click the start-laravel.bat file in Windows Explorer
# OR run it from command prompt
start-laravel.bat
```

### Option 3: Run with Custom Path
```powershell
# You can specify a different project path
powershell -ExecutionPolicy Bypass -File start-laravel.ps1 -ProjectPath "C:\Your\Custom\Path"
```

## What the Script Does

1. **Navigate to Project**: Changes to the Laravel project directory
2. **Check .env File**: Creates `.env` from `.env.example` if it doesn't exist
3. **Generate App Key**: Runs `php artisan key:generate` to create application key
4. **Clear Cache**: Runs `php artisan config:cache` to clear configuration cache
5. **Start Server**: Runs `php artisan serve` to start the development server

## Features

- ✅ **Idempotent**: Safe to run multiple times
- ✅ **Error Handling**: Clear error messages for each step
- ✅ **Color-coded Output**: Easy to read success/error messages
- ✅ **Automatic Setup**: Handles missing files and configurations
- ✅ **Cross-platform**: Works on Windows PowerShell

## Troubleshooting

### "Execution Policy" Error
If you get execution policy errors, run:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### PHP Not Found
Make sure PHP is installed and in your PATH. Test with:
```cmd
php --version
```

### Permission Issues
Run PowerShell or Command Prompt as Administrator if you encounter permission errors.

## Expected Output
```
Laravel Auto-Setup Script
========================

Step 1: Navigating to project folder...
SUCCESS: Successfully navigated to: C:\PROJECTS\SOFTWARE ENGINEERING\NearU\NearU-Laravel

Step 2: Checking .env file...
SUCCESS: .env file already exists

Step 3: Generating Laravel application key...
SUCCESS: Application key generated successfully

Step 4: Clearing Laravel config cache...
SUCCESS: Config cache cleared successfully

Step 5: Starting Laravel development server...
Server will be available at: http://localhost:8000
Press Ctrl+C to stop the server

INFO  Server running on [http://127.0.0.1:8000].
```