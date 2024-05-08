Stop-ProcessByPort 5173 "Vite"
Stop-ProcessByPort 8000 "php artisan serve"

function Stop-ProcessByPort {
    param (
        [int]$PortNumber,
        [string]$ProcessName
    )

    $process = Get-NetTCPConnection -LocalPort $PortNumber -ErrorAction SilentlyContinue | 
        Select-Object -ExpandProperty OwningProcess -ErrorAction SilentlyContinue | 
        ForEach-Object { Get-Process -Id $_ }

    if ($process) {
        $process | Stop-Process -Force
        Write-Host "Process '$ProcessName' running on port $PortNumber has been terminated."
    } else {
        Write-Host "No process '$ProcessName' found running on port $PortNumber."
    }
}