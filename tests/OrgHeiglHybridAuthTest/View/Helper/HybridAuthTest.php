<?php
/**
 * Copyright (c)2013-2013 heiglandreas
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIBILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category 
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2013-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     12.10.13
 * @link      https://github.com/heiglandreas/
 */

namespace OrgHeiglHybridAuthTest\View\Helper;

use Mockery as M;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class HybridAuthTest extends \PHPUnit_Framework_TestCase
{
    protected $locator = null;

    public function setup()
    {
        $serviceLocator = $this->getServiceManager();
        $serviceLocator->setAllowOverride(true);
        // replacing connection service with our fake one
        $serviceLocator->setService('config', '');

        $this->locator = $serviceLocator;
    }

    public function testSEttingServiceLocators()
    {
        $pluginManager = M::mock('Zend\View\HelperPluginManager');
        $mvcEvent = M::mock('Zend\Mvc\MvcEvent');
        $serviceLocator = $this->locator;

        $viewHelper = new \OrgHeiglHybridAuth\View\Helper\HybridAuth($pluginManager, $mvcEvent);

        $this->assertAttributeEquals($pluginManager, 'viewHelperManager', $viewHelper);
        $this->assertAttributeEquals($mvcEvent, 'mvcEvent', $viewHelper);
        $this->assertAttributeEquals(null, 'serviceLocator', $viewHelper);
        $this->assertNull($viewHelper->setServiceLocator($serviceLocator));
        $this->assertAttributeEquals($serviceLocator, 'serviceLocator', $viewHelper);
        $this->assertSame($serviceLocator, $viewHelper->getServiceLocator());
        $this->assertInstanceof('Zend\ServiceManager\ServiceLocatorInterface', $viewHelper->getServiceLocator());
    }

    /**
     * @dataProvider gettingBackendsProvider
     */
    public function testGettingBackends($backend, $expected)
    {
        $pluginManager = M::mock('Zend\View\HelperPluginManager');
        $mvcEvent = M::mock('Zend\Mvc\MvcEvent');
        $this->locator->setService('config', array('OrgHeiglHybridAuth' => array('backend' => $backend)));

        $viewHelper = new \OrgHeiglHybridAuth\View\Helper\HybridAuth($pluginManager, $mvcEvent);
        $viewHelper->setServiceLocator($this->locator);

      //  $this->assertEquals($expected, $viewHelper->getBackends());
        $this->assertEquals($expected, $viewHelper->getBackends($backend));

    }

    public function gettingBackendsProvider()
    {
        return array(
            array('backend', array('backend')),
            array(array('backend'), array('backend')),
            array(array('foo', 'bar'), array('foo', 'bar')),
        );
    }

    protected function getServiceManager()
    {
        $serviceManager = new ServiceManager(
            new ServiceManagerConfig(
                array()
            )
        );

        return $serviceManager;
    }
}
