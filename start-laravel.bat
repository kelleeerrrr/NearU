@echo off
REM Laravel Auto-Setup Batch Wrapper
REM This batch file runs the PowerShell script for Laravel setup

echo Starting Laravel Auto-Setup...
echo.

powershell -ExecutionPolicy Bypass -File "%~dp0start-laravel.ps1"

echo.
echo Press any key to exit...
pause >nul