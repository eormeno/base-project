cls
cd src

Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "npm run dev" -NoNewWindow
Start-Process -FilePath "powershell.exe" -ArgumentList "-Command", "php artisan serve" -NoNewWindow

$host.ui.RawUI.WindowTitle = 'artisan and vite services'
