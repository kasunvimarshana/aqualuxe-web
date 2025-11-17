<?php
/**
 * AquaLuxe Theme PHP Syntax & Formatting Fixer
 * --------------------------------------------
 * - Fixes missing whitespace/newline after `<?php` before comments
 * - Ensures consistent docblock formatting
 * - Checks PHP syntax before overwriting files
 *
 * @version 1.0
 * @author 
 */

class PhpSyntaxFixer
{
    private string $rootPath;
    private array $fixedFiles = [];

    public function __construct(string $rootPath)
    {
        $this->rootPath = rtrim($rootPath, DIRECTORY_SEPARATOR);
    }

    /**
     * Run the fixer on all PHP files in the given directory.
     */
    public function run(): void
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->rootPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if (strtolower($file->getExtension()) === 'php') {
                $this->processFile($file->getRealPath());
            }
        }

        $this->report();
    }

    /**
     * Process and fix a single PHP file.
     */
    private function processFile(string $filePath): void
    {
        $originalContent = file_get_contents($filePath);
        $fixedContent = $this->applyFixes($originalContent);

        if ($fixedContent !== $originalContent) {
            if ($this->isSyntaxValid($fixedContent)) {
                file_put_contents($filePath, $fixedContent);
                $this->fixedFiles[] = $filePath;
            } else {
                echo "[SKIPPED - syntax error after fix] $filePath\n";
            }
        }
    }

    /**
     * Apply all registered fixes to the file content.
     */
    private function applyFixes(string $content): string
    {
        // Fix missing space/newline after <?php before comments
        $content = preg_replace(
            '/<\?php\s*(\/\*\*)/m',
            "<?php\n$1",
            $content
        );

        // Optional: Normalize opening tag format
        $content = preg_replace(
            '/<\?php\s*\n+/',
            "<?php\n",
            $content
        );

        // Optional: Ensure each docblock starts with /** on a new line
        $content = preg_replace(
            '/\n\s*\/\*\*/',
            "\n/**",
            $content
        );

        return $content;
    }

    /**
     * Check if given PHP code has valid syntax.
     */
    private function isSyntaxValid(string $phpCode): bool
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'phpfix');
        file_put_contents($tempFile, $phpCode);
        $output = null;
        $returnVar = null;
        exec("php -l " . escapeshellarg($tempFile), $output, $returnVar);
        unlink($tempFile);
        return $returnVar === 0;
    }

    /**
     * Print a report of changes.
     */
    private function report(): void
    {
        if (empty($this->fixedFiles)) {
            echo "✅ No changes needed. All PHP files are clean.\n";
        } else {
            echo "✅ Fixed " . count($this->fixedFiles) . " file(s):\n";
            foreach ($this->fixedFiles as $file) {
                echo " - $file\n";
            }
        }
    }
}

// -------------------
// Run the fixer
// -------------------
$themePath = __DIR__; // Change if needed
$fixer = new PhpSyntaxFixer($themePath);
$fixer->run();
