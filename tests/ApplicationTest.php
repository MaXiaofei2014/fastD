<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use ServiceProvider\FooServiceProvider;

class ApplicationTest extends TestCase
{
    public function testApplicationInitialize()
    {
        $app = $this->createApplication();

        $this->assertEquals(__DIR__, $app->getAppPath());
        $this->assertEquals('Fast-D', $app->getName());
        $this->assertEquals('PRC', $app['time']->getTimeZone()->getName());
        $this->assertTrue($app->isBooted());
    }

    public function testHandleRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->createRequest('GET', '/'));
        $this->assertEquals(json_encode(['foo' => 'bar']), $response->getBody());
    }

    public function testHandleDynamicRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->createRequest('GET', '/foo/bar'));
        $this->assertEquals(json_encode(['foo' => 'bar']), $response->getBody());
        $response = $app->handleRequest($this->createRequest('GET', '/foo/foobar'));
        $this->assertEquals(json_encode(['foo' => 'foobar']), $response->getBody());
    }

    public function testHandleMiddlewareRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->createRequest('POST', '/foo/bar'));
        $this->assertEquals(json_encode(['foo' => 'middleware']), $response->getBody());
        $response = $app->handleRequest($this->createRequest('POST', '/foo/not'));
        $this->assertEquals(json_encode(['foo' => 'bar']), $response->getBody());
    }

    public function testServiceProvider()
    {
        $app = $this->createApplication();

        $app->register(new FooServiceProvider());
        $this->assertEquals('foo', $app['foo']->name);
    }

    public function testConfiguration()
    {
        $app = $this->createApplication();

        $this->assertEquals('Fast-D', $app->get('config')->get('name'));
    }

    public function testLogger()
    {
        $app = $this->createApplication();

        $response = $app->handleRequest($this->createRequest('GET', '/'));

        $app->handleResponse($response);

        $this->assertTrue(file_exists(app()->getAppPath() . '/storage/info.log'));

        $app->run($this->createRequest('GET', '/not'));

        $this->assertTrue(file_exists(app()->getAppPath() . '/storage/error.log'));
    }
}