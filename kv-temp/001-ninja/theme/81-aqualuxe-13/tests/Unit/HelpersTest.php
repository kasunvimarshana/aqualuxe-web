<?php
use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function test_schema_attr_outputs_attribute(): void
    {
        $out = aqualuxe_schema_attr('WebPage');
        $this->assertStringContainsString('schema.org/WebPage', $out);
    }
}
