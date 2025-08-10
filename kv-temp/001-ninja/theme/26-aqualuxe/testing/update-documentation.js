/**
 * AquaLuxe Theme - Documentation Update Script
 * 
 * This script scans the theme files to extract hooks, filters, and functions
 * to automatically update the documentation.
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Theme directories
const themeDir = path.join(__dirname, '..', 'aqualuxe');
const docsDir = path.join(themeDir, 'docs');

// Ensure docs directory exists
if (!fs.existsSync(docsDir)) {
  fs.mkdirSync(docsDir, { recursive: true });
}

// Function to extract hooks and filters from PHP files
function extractHooksAndFilters(directory) {
  const hooks = [];
  const filters = [];
  
  // Find all PHP files in the theme
  const phpFiles = execSync(`find ${directory} -type f -name "*.php"`, { encoding: 'utf8' })
    .trim()
    .split('\n')
    .filter(file => file);
  
  phpFiles.forEach(file => {
    const content = fs.readFileSync(file, 'utf8');
    
    // Extract hooks (do_action)
    const hookMatches = content.match(/do_action\(\s*['"]([^'"]+)['"]/g) || [];
    hookMatches.forEach(match => {
      const hookName = match.match(/do_action\(\s*['"]([^'"]+)['"]/)[1];
      const hookInfo = {
        name: hookName,
        file: path.relative(themeDir, file),
        description: extractDocComment(content, match) || `Hook for ${hookName}`
      };
      
      if (!hooks.some(h => h.name === hookName && h.file === hookInfo.file)) {
        hooks.push(hookInfo);
      }
    });
    
    // Extract filters (apply_filters)
    const filterMatches = content.match(/apply_filters\(\s*['"]([^'"]+)['"]/g) || [];
    filterMatches.forEach(match => {
      const filterName = match.match(/apply_filters\(\s*['"]([^'"]+)['"]/)[1];
      const filterInfo = {
        name: filterName,
        file: path.relative(themeDir, file),
        description: extractDocComment(content, match) || `Filter for ${filterName}`
      };
      
      if (!filters.some(f => f.name === filterName && f.file === filterInfo.file)) {
        filters.push(filterInfo);
      }
    });
  });
  
  return { hooks, filters };
}

// Function to extract functions from PHP files
function extractFunctions(directory) {
  const functions = [];
  
  // Find all PHP files in the theme
  const phpFiles = execSync(`find ${directory} -type f -name "*.php"`, { encoding: 'utf8' })
    .trim()
    .split('\n')
    .filter(file => file);
  
  phpFiles.forEach(file => {
    const content = fs.readFileSync(file, 'utf8');
    
    // Extract function definitions
    const functionMatches = content.match(/function\s+([a-zA-Z0-9_]+)\s*\([^)]*\)/g) || [];
    functionMatches.forEach(match => {
      const functionName = match.match(/function\s+([a-zA-Z0-9_]+)/)[1];
      
      // Skip WordPress core functions
      if (functionName.startsWith('wp_') || functionName.startsWith('_wp_')) {
        return;
      }
      
      const functionInfo = {
        name: functionName,
        file: path.relative(themeDir, file),
        description: extractDocComment(content, match) || `Function ${functionName}`,
        parameters: extractParameters(match)
      };
      
      if (!functions.some(f => f.name === functionName && f.file === functionInfo.file)) {
        functions.push(functionInfo);
      }
    });
  });
  
  return functions;
}

// Function to extract doc comments
function extractDocComment(content, match) {
  const matchIndex = content.indexOf(match);
  if (matchIndex === -1) return null;
  
  const beforeMatch = content.substring(0, matchIndex);
  const commentEndIndex = beforeMatch.lastIndexOf('*/');
  if (commentEndIndex === -1) return null;
  
  const commentStartIndex = beforeMatch.lastIndexOf('/**', commentEndIndex);
  if (commentStartIndex === -1) return null;
  
  const comment = beforeMatch.substring(commentStartIndex, commentEndIndex);
  
  // Extract description from comment
  const descriptionMatch = comment.match(/\*\s+([^@][^\n]+)/);
  return descriptionMatch ? descriptionMatch[1].trim() : null;
}

// Function to extract parameters from function definition
function extractParameters(functionDef) {
  const paramsMatch = functionDef.match(/\(([^)]*)\)/);
  if (!paramsMatch || !paramsMatch[1].trim()) return [];
  
  return paramsMatch[1].split(',').map(param => param.trim());
}

// Generate hooks and filters documentation
function generateHooksAndFiltersDoc(hooks, filters) {
  let content = '# AquaLuxe Theme Hooks and Filters\n\n';
  content += 'This document lists all available hooks and filters in the AquaLuxe theme.\n\n';
  
  // Add hooks section
  content += '## Hooks\n\n';
  hooks.sort((a, b) => a.name.localeCompare(b.name));
  hooks.forEach(hook => {
    content += `### ${hook.name}\n\n`;
    content += `${hook.description}\n\n`;
    content += `**File:** \`${hook.file}\`\n\n`;
    content += '**Example:**\n\n';
    content += '```php\n';
    content += `add_action('${hook.name}', 'your_function_name', 10, 1);\n`;
    content += 'function your_function_name() {\n';
    content += '    // Your code here\n';
    content += '}\n';
    content += '```\n\n';
  });
  
  // Add filters section
  content += '## Filters\n\n';
  filters.sort((a, b) => a.name.localeCompare(b.name));
  filters.forEach(filter => {
    content += `### ${filter.name}\n\n`;
    content += `${filter.description}\n\n`;
    content += `**File:** \`${filter.file}\`\n\n`;
    content += '**Example:**\n\n';
    content += '```php\n';
    content += `add_filter('${filter.name}', 'your_filter_function', 10, 1);\n`;
    content += 'function your_filter_function($value) {\n';
    content += '    // Modify $value here\n';
    content += '    return $value;\n';
    content += '}\n';
    content += '```\n\n';
  });
  
  return content;
}

// Generate functions documentation
function generateFunctionsDoc(functions) {
  let content = '# AquaLuxe Theme Functions Reference\n\n';
  content += 'This document lists all custom functions available in the AquaLuxe theme.\n\n';
  
  // Group functions by file
  const functionsByFile = {};
  functions.forEach(func => {
    if (!functionsByFile[func.file]) {
      functionsByFile[func.file] = [];
    }
    functionsByFile[func.file].push(func);
  });
  
  // Add functions by file
  Object.keys(functionsByFile).sort().forEach(file => {
    content += `## ${file}\n\n`;
    
    functionsByFile[file].sort((a, b) => a.name.localeCompare(b.name)).forEach(func => {
      content += `### ${func.name}\n\n`;
      content += `${func.description}\n\n`;
      
      if (func.parameters.length > 0) {
        content += '**Parameters:**\n\n';
        func.parameters.forEach(param => {
          content += `- \`${param}\`\n`;
        });
        content += '\n';
      }
      
      content += '**Example:**\n\n';
      content += '```php\n';
      content += `${func.name}(${func.parameters.join(', ')});\n`;
      content += '```\n\n';
    });
  });
  
  return content;
}

// Main function
function updateDocumentation() {
  console.log('Updating AquaLuxe theme documentation...');
  
  // Extract hooks, filters, and functions
  console.log('Extracting hooks and filters...');
  const { hooks, filters } = extractHooksAndFilters(themeDir);
  console.log(`Found ${hooks.length} hooks and ${filters.length} filters.`);
  
  console.log('Extracting functions...');
  const functions = extractFunctions(themeDir);
  console.log(`Found ${functions.length} functions.`);
  
  // Generate documentation files
  console.log('Generating hooks and filters documentation...');
  const hooksAndFiltersDoc = generateHooksAndFiltersDoc(hooks, filters);
  fs.writeFileSync(path.join(docsDir, 'hooks-and-filters.md'), hooksAndFiltersDoc);
  
  console.log('Generating functions documentation...');
  const functionsDoc = generateFunctionsDoc(functions);
  fs.writeFileSync(path.join(docsDir, 'functions-reference.md'), functionsDoc);
  
  console.log('Documentation updated successfully!');
}

updateDocumentation();