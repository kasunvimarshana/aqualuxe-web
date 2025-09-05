<?php
use PHPUnit\Framework\TestCase;

final class FragmentCacheTest extends TestCase
{
    public function testFragmentCacheRunsCallback(): void
    {
        $i = 0;
        $out = \AquaLuxe\fragment_cache('test_key_'.rand(), 10, function() use (&$i){ $i++; return 'x'; });
        $this->assertSame('x', $out);
        $this->assertSame(1, $i);
    }
}
