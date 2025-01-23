Clear-Host

$currentPath = Get-Location
# check if the currentPath ends with 'src' or 'src\'
if ($currentPath -match 'src$' -or $currentPath -match 'src\\$') {
    # if the currentPath ends with 'src' or 'src\', then change the directory to the parent directory
    Set-Location ..
}

Set-Location src

$filter = $args[0]
if ($filter -eq $null) {
    # if no filter is provided, then run all tests
    php artisan test
    return
}

# A dictionary with the prefix and the test class name
$testClasses = @{
    'gtn' = 'GTNPlayGameTest';
    'bba' = 'BBAPlayGameTest';
}

# Find the filter in prefix and get the test class name
$testClass = $testClasses[$filter]

if ($testClass -eq $null) {
    Write-Host "Invalid filter. Available filters are:" -ForegroundColor Red
    foreach ($key in $testClasses.Keys) {
        Write-Host "  $key" -ForegroundColor Yellow -NoNewline
        Write-Host " - $($testClasses[$key])" -ForegroundColor Green
    }
    return
}

php artisan test --filter=$testClass