# Specify the port number
$portNumber = 5173

# Find the process using the port
$process = Get-NetTCPConnection -LocalPort $portNumber -ErrorAction SilentlyContinue | 
    Select-Object -ExpandProperty OwningProcess -ErrorAction SilentlyContinue | 
    ForEach-Object { Get-Process -Id $_ }

# If a process is found, kill it
if ($process) {
    $process | Stop-Process -Force
    Write-Host "Process running on port $portNumber has been terminated."
} else {
    Write-Host "No process found running on port $portNumber."
}

# Specify the port number
$portNumber = 8000

# Find the process using the port
$process = Get-NetTCPConnection -LocalPort $portNumber -ErrorAction SilentlyContinue | 
    Select-Object -ExpandProperty OwningProcess -ErrorAction SilentlyContinue | 
    ForEach-Object { Get-Process -Id $_ }

# If a process is found, kill it
if ($process) {
    $process | Stop-Process -Force
    Write-Host "Process running on port $portNumber has been terminated."
} else {
    Write-Host "No process found running on port $portNumber."
}
