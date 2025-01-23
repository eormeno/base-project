Clear-Host

Set-Location src

# if there is the '-r' argument, then remove the database.sqlite file
if ($args -contains '-r') {
    Remove-Item database/database.sqlite -ErrorAction SilentlyContinue
    # execute the migration force and seed
    php artisan migrate --force --seed
    php artisan games
}

$vite = Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "npm run dev" -PassThru -NoNewWindow
$artisan = Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "php artisan serve" -PassThru -NoNewWindow


# Save the process IDs to a file
$processes = @($vite.Id, $artisan.Id)
$processes | Out-File .\processes.txt

Set-Location ..