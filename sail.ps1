<#
.SYNOPSIS
    Laravel Sail / Docker Compose helper for Windows PowerShell
.DESCRIPTION
    Provides shorthand commands for Docker containers in Windows PowerShell.
    Examples:
        .\sail.ps1 up -d
        .\sail.ps1 down
        .\sail.ps1 artisan migrate
        .\sail.ps1 composer install
        .\sail.ps1 npm run build
        .\sail.ps1 bash
#>

param(
    [Parameter(Position = 0)]
    [string]$Command = "help",

    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$RemainingArgs
)

switch ($Command.ToLower()) {
    "up" {
        docker compose up @RemainingArgs
    }
    "down" {
        docker compose down @RemainingArgs
    }
    "stop" {
        docker compose stop @RemainingArgs
    }
    "restart" {
        docker compose restart @RemainingArgs
    }
    "ps" {
        docker compose ps @RemainingArgs
    }
    "logs" {
        docker compose logs -f @RemainingArgs
    }
    "artisan" {
        docker compose exec laravel.test php artisan @RemainingArgs
    }
    "composer" {
        docker compose exec laravel.test composer @RemainingArgs
    }
    "npm" {
        docker compose exec laravel.test npm @RemainingArgs
    }
    "tinker" {
        docker compose exec -it laravel.test php artisan tinker
    }
    "bash" {
        docker compose exec -it laravel.test bash
    }
    "shell" {
        docker compose exec -it laravel.test bash
    }
    "mysql" {
        docker compose exec -it mysql mysql -u sail -ppassword laravel
    }
    "help" {
        Write-Host "Laravel Docker / Sail Helper Commands:" -ForegroundColor Cyan
        Write-Host "  .\sail.ps1 up -d          - Khoi dong container background"
        Write-Host "  .\sail.ps1 down           - Dung va go bo container"
        Write-Host "  .\sail.ps1 ps             - Kiem tra trang thai container"
        Write-Host "  .\sail.ps1 logs           - Xem logs thoi gian thuc"
        Write-Host "  .\sail.ps1 artisan <cmd>  - Chay php artisan ben trong container"
        Write-Host "  .\sail.ps1 composer <cmd> - Chay composer ben trong container"
        Write-Host "  .\sail.ps1 npm <cmd>      - Chay npm ben trong container"
        Write-Host "  .\sail.ps1 bash           - Truy cap terminal container"
        Write-Host "  .\sail.ps1 mysql          - Truy cap MySQL CLI"
    }
    Default {
        # Fallback passing all arguments directly to docker compose
        docker compose $Command @RemainingArgs
    }
}
