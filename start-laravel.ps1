# Laravel Auto-Setup and Server Start Script
# This script automates Laravel project setup and starts the development server

param(
    [string]$ProjectPath = "C:\PROJECTS\SOFTWARE ENGINEERING\NearU\NearU-Laravel"
)

Write-Host "Laravel Auto-Setup Script" -ForegroundColor Cyan
Write-Host "========================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Navigate to project folder
Write-Host "Step 1: Navigating to project folder..." -ForegroundColor Yellow
try {
    Set-Location -Path $ProjectPath -ErrorAction Stop
    Write-Host "SUCCESS: Successfully navigated to: $ProjectPath" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Could not navigate to $ProjectPath" -ForegroundColor Red
    Write-Host "Error details: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 2: Check and create .env file
Write-Host ""
Write-Host "Step 2: Checking .env file..." -ForegroundColor Yellow
$envFile = Join-Path $ProjectPath ".env"
$envExample = Join-Path $ProjectPath ".env.example"

if (-not (Test-Path $envFile)) {
    if (Test-Path $envExample) {
        try {
            Copy-Item $envExample $envFile -ErrorAction Stop
            Write-Host "SUCCESS: Created .env file from .env.example" -ForegroundColor Green
        } catch {
            Write-Host "ERROR: Could not copy .env.example to .env" -ForegroundColor Red
            Write-Host "Error details: $($_.Exception.Message)" -ForegroundColor Red
            exit 1
        }
    } else {
        Write-Host "ERROR: .env.example file not found" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "SUCCESS: .env file already exists" -ForegroundColor Green
}

# Step 3: Generate application key
Write-Host ""
Write-Host "Step 3: Generating Laravel application key..." -ForegroundColor Yellow
try {
    $keyGenOutput = & php artisan key:generate 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "SUCCESS: Application key generated successfully" -ForegroundColor Green
    } else {
        Write-Host "ERROR: Failed to generate application key" -ForegroundColor Red
        Write-Host "Command output: $keyGenOutput" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "ERROR: Could not run php artisan key:generate" -ForegroundColor Red
    Write-Host "Error details: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 4: Clear config cache
Write-Host ""
Write-Host "Step 4: Clearing Laravel config cache..." -ForegroundColor Yellow
try {
    $cacheOutput = & php artisan config:cache 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "SUCCESS: Config cache cleared successfully" -ForegroundColor Green
    } else {
        Write-Host "ERROR: Failed to clear config cache" -ForegroundColor Red
        Write-Host "Command output: $cacheOutput" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "ERROR: Could not run php artisan config:cache" -ForegroundColor Red
    Write-Host "Error details: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 5: Start Laravel development server
Write-Host ""
Write-Host "Step 5: Starting Laravel development server..." -ForegroundColor Yellow
Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Cyan
Write-Host ""

try {
    & php artisan serve
} catch {
    Write-Host ""
    Write-Host "ERROR: Could not start Laravel development server" -ForegroundColor Red
    Write-Host "Error details: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "SUCCESS: Laravel setup and server start completed!" -ForegroundColor Green