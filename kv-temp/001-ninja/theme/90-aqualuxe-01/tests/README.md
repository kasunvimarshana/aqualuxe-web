# AquaLuxe Theme Tests

This directory contains the testing suite for the AquaLuxe theme.

## Frameworks

- **PHPUnit**: For unit testing our PHP functions and classes.
- **Jest/Cypress**: For JavaScript and end-to-end testing (to be configured).

## Running Tests

### PHPUnit

1.  Ensure you have PHPUnit installed in your development environment.
2.  Configure the `phpunit.xml` file in the theme root to point to your WordPress test database.
3.  From the theme root, run: `phpunit`

---

## Sample Test

This is a placeholder for a sample PHPUnit test.

```php
<?php
/**
 * Class SampleTest
 *
 * @package Aqualuxe
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}
}
```
