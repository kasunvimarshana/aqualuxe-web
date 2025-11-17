# Contributing to AquaLuxe

Thank you for your interest in contributing to the AquaLuxe WordPress theme! This document provides guidelines and instructions for contributing to this project.

## Code of Conduct

By participating in this project, you agree to abide by our code of conduct:

- Be respectful and inclusive
- Be patient and welcoming
- Be thoughtful
- Be collaborative
- When disagreeing, try to understand why

## How to Contribute

### Reporting Bugs

If you find a bug in the theme, please submit an issue on our GitHub repository with the following information:

1. A clear, descriptive title
2. A detailed description of the issue
3. Steps to reproduce the bug
4. Expected behavior
5. Actual behavior
6. Screenshots (if applicable)
7. Your WordPress version, theme version, and relevant plugin versions
8. Any additional context that might help us resolve the issue

### Suggesting Enhancements

We welcome suggestions for improvements to the AquaLuxe theme. To suggest an enhancement:

1. Submit an issue on our GitHub repository
2. Use a clear, descriptive title
3. Provide a detailed description of the suggested enhancement
4. Explain why this enhancement would be useful
5. Include any relevant examples, mockups, or screenshots

### Pull Requests

We actively welcome pull requests:

1. Fork the repository
2. Create a new branch for your feature or bugfix
3. Make your changes
4. Ensure your code follows our coding standards
5. Test your changes thoroughly
6. Submit a pull request with a clear description of the changes

## Development Setup

To set up a local development environment:

1. Clone the repository:
   ```
   git clone https://github.com/aqualuxe/aqualuxe-theme.git
   ```

2. Install dependencies:
   ```
   npm install
   ```

3. Build assets:
   ```
   npm run build
   ```

4. For development with automatic rebuilding:
   ```
   npm run dev
   ```

## Coding Standards

### PHP

- Follow the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use PHP 7.4+ compatible code
- Document your code with PHPDoc comments
- Use meaningful variable and function names
- Keep functions focused on a single responsibility
- Use proper indentation (4 spaces)

### CSS/SCSS

- Follow the [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- Use Tailwind CSS utility classes when possible
- Add custom CSS only when necessary
- Use variables for colors, spacing, etc.
- Keep selectors as simple as possible
- Comment complex code sections

### JavaScript

- Follow the [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use ES6+ features
- Document your code with JSDoc comments
- Use meaningful variable and function names
- Write modular, reusable code
- Add appropriate error handling

## Testing

Before submitting a pull request, please test your changes:

1. Test on multiple browsers (Chrome, Firefox, Safari, Edge)
2. Test on multiple devices (desktop, tablet, mobile)
3. Test with different WordPress configurations
4. Ensure accessibility standards are maintained
5. Verify performance is not negatively impacted

## Documentation

If your changes require documentation updates:

1. Update the relevant documentation files in the `docs` directory
2. Update inline code documentation as needed
3. Update the README.md if necessary
4. Add notes to the CHANGELOG.md file

## Release Process

Our release process follows these steps:

1. Update version numbers in:
   - style.css
   - package.json
   - readme.txt
2. Update CHANGELOG.md with all changes
3. Build production assets
4. Create a new release on GitHub
5. Submit to the WordPress theme repository (if applicable)

## Getting Help

If you need help with the contribution process:

- Join our [community forum](https://aqualuxetheme.com/community)
- Contact us through our [website](https://aqualuxetheme.com/contact)
- Ask questions in the GitHub issue tracker

Thank you for contributing to AquaLuxe!