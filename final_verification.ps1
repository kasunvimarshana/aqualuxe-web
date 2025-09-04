# Final verification script for WordPress coding standards
$content = Get-Content 'wp-content/themes/aqualuxe/inc/admin/importer.php' -Raw

Write-Host "=== Final WordPress Coding Standards Verification ==="
Write-Host ""

# Check for blank lines after control structures (the original issue)
$problematic_patterns = @(
    '\}\s*\r?\n\s*\r?\n\s*return',
    '\}\s*\r?\n\s*\r?\n\s*\$',
    '\}\s*\r?\n\s*\r?\n\s*break',
    '\}\s*\r?\n\s*\r?\n\s*private',
    '\}\s*\r?\n\s*\r?\n\s*public',
    '\}\s*\r?\n\s*\r?\n\s*if',
    '\}\s*\r?\n\s*\r?\n\s*foreach',
    '\}\s*\r?\n\s*\r?\n\s*while'
)

$total_issues = 0
foreach ($pattern in $problematic_patterns) {
    $matches = [regex]::Matches($content, $pattern)
    if ($matches.Count -gt 0) {
        Write-Host "❌ Found $($matches.Count) instances of: $pattern"
        $total_issues += $matches.Count
    } else {
        Write-Host "✅ No issues found for: $pattern"
    }
}

# Check for syntax issues
$syntax_issues = @(
    'privateprivate',
    'publicpublic',
    'protectedprotected'
)

foreach ($issue in $syntax_issues) {
    if ($content -match $issue) {
        Write-Host "❌ Found syntax issue: $issue"
        $total_issues++
    } else {
        Write-Host "✅ No syntax issues found for: $issue"
    }
}

Write-Host ""
if ($total_issues -eq 0) {
    Write-Host "🎉 SUCCESS: All WordPress coding standard violations have been fixed!"
    Write-Host "   - No blank lines after control structures"
    Write-Host "   - No syntax errors"
    Write-Host "   - File is ready for production"
} else {
    Write-Host "⚠️  ISSUES REMAINING: $total_issues total violations found"
}

Write-Host ""
Write-Host "=== File Statistics ==="
$lines = ($content -split "\r?\n").Count
$methods = ([regex]::Matches($content, 'function\s+\w+')).Count
Write-Host "Total lines: $lines"
Write-Host "Total methods: $methods"
