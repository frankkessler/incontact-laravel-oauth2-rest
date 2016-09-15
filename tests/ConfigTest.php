<?php


class ConfigTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function testGet()
    {
        $config = \Frankkessler\Incontact\IncontactConfig::get();

        $this->assertEquals('password', $config['incontact.oauth.auth_method']);

        \Frankkessler\Incontact\IncontactConfig::reset();
    }

    public function testGetDefault()
    {
        $value = \Frankkessler\Incontact\IncontactConfig::get('nonexistent_key', 'default');

        $this->assertEquals('default', $value);

        \Frankkessler\Incontact\IncontactConfig::reset();
    }

    public function testSet()
    {
        \Frankkessler\Incontact\IncontactConfig::set('nonexistent_key', 'default');

        $value = \Frankkessler\Incontact\IncontactConfig::get('nonexistent_key');

        $this->assertEquals('default', $value);

        \Frankkessler\Incontact\IncontactConfig::reset();
    }

    public function testSetAll()
    {
        \Frankkessler\Incontact\IncontactConfig::setAll([
            'nonexistent_key'  => 'default',
            'nonexistent_key1' => 'default1',
        ]);

        $value = \Frankkessler\Incontact\IncontactConfig::get('nonexistent_key1');

        $this->assertEquals('default1', $value);

        \Frankkessler\Incontact\IncontactConfig::reset();
    }

    public function testSetInitialConfig()
    {
        \Frankkessler\Incontact\IncontactConfig::setInitialConfig([
            'nonexistent_key'  => 'default',
            'nonexistent_key1' => 'default1',
        ]);

        $value = \Frankkessler\Incontact\IncontactConfig::get('nonexistent_key1');

        $this->assertEquals('default1', $value);

        \Frankkessler\Incontact\IncontactConfig::reset();
    }
}
