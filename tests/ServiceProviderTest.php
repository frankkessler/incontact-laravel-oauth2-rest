<?php

use \Closure;
use \Mockery;
use \ArrayAccess;
use \ReflectionClass;
use \ReflectionMethod;
use \Mockery\MockInterface;
use \Frankkessler\Incontact\Providers\IncontactLaravelServiceProvider;
use \Illuminate\Contracts\Foundation\Application as ApplicationInterface;


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
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->config   = Mockery::mock();

        $this->app = Mockery::mock(ArrayAccess::class);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->app->shouldReceive('offsetGet')->zeroOrMoreTimes()->with('path.config')->andReturn('/some/config/path');
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->app->shouldReceive('offsetGet')->zeroOrMoreTimes()->with('config')->andReturn($this->config);

        /** @var ApplicationInterface $app */
        $app = $this->app;

        //$this->provider = new IncontactLaravelServiceProvider($app);
    }

    /**
     * Test register provider.
     */
    public function testRegister()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->config->shouldReceive('get')->withAnyArgs()->once()->andReturn([]);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->config->shouldReceive('set')->withAnyArgs()->once()->andReturnUndefined();
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        //$this->app->shouldReceive('bind')->withAnyArgs()->twice()->andReturnUndefined();

        //$this->provider->register();
    }

    /**
     * @param string $name
     *
     * @return ReflectionMethod
     */
    protected static function getMethod($name)
    {
        $class  = new ReflectionClass(IncontactLaravelServiceProvider::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}