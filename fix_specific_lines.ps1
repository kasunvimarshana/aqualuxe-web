$content = Get-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php'

# Function to remove blank line at specific line number
function Remove-BlankLineAt($lines, $lineNumber) {
    if ($lineNumber -lt $lines.Count -and $lines[$lineNumber-1] -match '^\s*$') {
        $lines = $lines[0..($lineNumber-2)] + $lines[$lineNumber..($lines.Count-1)]
    }
    return $lines
}

# Convert to array for manipulation
$lines = @($content)

# Remove blank lines at the specific line numbers reported by phpcs
# Note: After removing each line, subsequent line numbers shift up
$linesToFix = @(322, 570, 573, 692, 1045, 1057, 1061, 1150, 1160, 1268, 1270, 1275, 1277, 1303, 1334, 1395)

# Sort in descending order to avoid line number shifting issues
$linesToFix = $linesToFix | Sort-Object -Descending

foreach ($lineNum in $linesToFix) {
    $lines = Remove-BlankLineAt $lines $lineNum
    Write-Host "Processed line $lineNum"
}

# Save the file
$lines | Set-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php'

Write-Host "Fixed blank lines at all reported line numbers"
