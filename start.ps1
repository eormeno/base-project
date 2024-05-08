Clear-Host

$currentPath = Get-Location
# check if the currentPath ends with 'src' or 'src\'
if ($currentPath -match 'src$' -or $currentPath -match 'src\\$') {
    # if the currentPath ends with 'src' or 'src\', then change the directory to the parent directory
    Set-Location ..
}

Set-Location src

Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "npm run dev" -NoNewWindow
Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "php artisan serve" -NoNewWindow

$host.ui.RawUI.WindowTitle = 'artisan and vite services'
