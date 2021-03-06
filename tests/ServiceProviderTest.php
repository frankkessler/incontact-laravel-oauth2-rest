<?php

use \ArrayAccess;
use \Frankkessler\Incontact\Providers\IncontactLaravelServiceProvider;
use \Illuminate\Contracts\Foundation\Application as ApplicationInterface;
use \Mockery;
use \Mockery\MockInterface;
use \ReflectionClass;
use \ReflectionMethod;

class LaravelServiceProviderTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * @var MockInterface
     */
    private $app;

    /**
     * @var MockInterface
     */
    private $config;

    /**
     * @var IncontactLaravelServiceProvider
     */
    private $provider;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock();

        $this->app = Mockery::mock(ArrayAccess::class);

        /** @var ApplicationInterface $app */
        $app = $this->app;

        //$this->provider = new IncontactLaravelServiceProvider($app);
    }

    /**
     * Test register provider.
     */
    public function testRegister()
    {
        /* @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->app->shouldReceive('set')->withAnyArgs()->once()->andReturnUndefined();

        //$this->provider->register();
    }

    /**
     * Test register provider.
     */
    public function testBoot()
    {
        /* @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->app->shouldReceive('set')->withAnyArgs()->once()->andReturnUndefined();

        //$this->provider->boot();
    }

    /**
     * @param string $name
     *
     * @return ReflectionMethod
     */
    protected static function getMethod($name)
    {
        $class = new ReflectionClass(IncontactLaravelServiceProvider::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
