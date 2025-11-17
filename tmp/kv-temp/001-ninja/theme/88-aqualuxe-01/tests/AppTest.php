<?php
use PHPUnit\Framework\TestCase;
use Aqualuxe\Core\App;
use Aqualuxe\Core\ServiceProviderInterface;

class DummyProvider implements ServiceProviderInterface {
	public bool $registered = false;
	public function register(): void { $this->registered = true; }
}

final class AppTest extends TestCase
{
	public function testRegistersAndBootsProviders(): void
	{
		$app = App::getInstance();
		$provider = new DummyProvider();
		$app->register($provider);
		$app->boot();
		$this->assertTrue($provider->registered);
	}
}
