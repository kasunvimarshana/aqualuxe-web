# More aggressive blank line removal for WordPress coding standards
$content = Get-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php' -Raw

# Remove all instances where there's a closing brace followed by a blank line
$original_content = $content

# Pattern 1: } followed by blank line then any code
$replacement1 = '$1' + "`r`n" + '$2'
$content = $content -replace '(\r?\n\s*})\s*\r?\n\s*\r?\n(\s*\S)', $replacement1

# Pattern 2: Multiple consecutive blank lines after }
while ($content -match '\}\s*\r?\n\s*\r?\n\s*\r?\n') {
    $replacement2 = '$1' + "`r`n" + "`r`n"
    $content = $content -replace '(\r?\n\s*})\s*\r?\n\s*\r?\n\s*\r?\n', $replacement2
}

# Pattern 3: Any remaining double blank lines after }
$replacement3 = '$1' + "`r`n"
$content = $content -replace '(\r?\n\s*})\s*\r?\n\s*\r?\n', $replacement3

# Save the file
Set-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php' -Value $content -NoNewline

if ($original_content -ne $content) {
    Write-Host "Made changes to remove blank lines after control structures"
} else {
    Write-Host "No changes needed - file already compliant"
}

Write-Host "File processed successfully"
