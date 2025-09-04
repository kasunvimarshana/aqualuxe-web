# Check for remaining blank line issues
$content = Get-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php' -Raw

$problematic_patterns = @(
    '\}\s*\r?\n\s*\r?\n\s*return',
    '\}\s*\r?\n\s*\r?\n\s*\$',
    '\}\s*\r?\n\s*\r?\n\s*break',
    '\}\s*\r?\n\s*\r?\n\s*private',
    '\}\s*\r?\n\s*\r?\n\s*public',
    '\}\s*\r?\n\s*\r?\n\s*if',
    '\}\s*\r?\n\s*\r?\n\s*foreach',
    '\}\s*\r?\n\s*\r?\n\s*while',
    '\}\s*\r?\n\s*\r?\n\s*try',
    '\}\s*\r?\n\s*\r?\n\s*catch'
)

$total_issues = 0
foreach ($pattern in $problematic_patterns) {
    $matches = [regex]::Matches($content, $pattern)
    if ($matches.Count -gt 0) {
        Write-Host "Found $($matches.Count) instances of pattern: $pattern"
        $total_issues += $matches.Count
        
        # Show context for each match
        foreach ($match in $matches) {
            $start = [Math]::Max(0, $match.Index - 50)
            $length = [Math]::Min(100, $content.Length - $start)
            $context = $content.Substring($start, $length) -replace '\r?\n', ' '
            Write-Host "  Context: ...$context..."
        }
    }
}

if ($total_issues -eq 0) {
    Write-Host "SUCCESS: No blank lines after control structures found!"
} else {
    Write-Host "ISSUES: Found $total_issues total problematic patterns"
}

# Count total lines
$lines = ($content -split "\r?\n").Count
Write-Host "Total lines in file: $lines"
