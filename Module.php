<?php
/**
 * Copyright (c) 2011-2012 Andreas Heigl<andreas@heigl.org>
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
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  MailProxy
 * @package   OrgHeiglMailproxy
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2011-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     06.03.2012
 * @link      http://github.com/heiglandreas/mailproxyModule
 */

namespace OrgHeiglHybridAuth;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\Mvc\ModuleRouteListener;
use OrgHeiglHybridAuth\View\Helper\HybridAuth as HybridAuthViewManager;
    

/**
 * The Module-Provider
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2011-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     06.03.2012
 * @link      http://github.com/heiglandreas/HybridAuth
 */
class Module
{
//    public function init(ModuleManager $moduleManager)
//    {
//        $events = StaticEventManager::getInstance();
//        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
//        $events->attach('loadModule', 'loadModule', function(){error_log('bar');});
//      }
    
    public function initializeView($e)
    {
//        $app          = $e->getParam('application');
//        $locator      = $app->getLocator();
//        $renderer     = $locator->get('Zend\View\HelperLoader');
//        $renderer->registerPlugin('hybridauthinfo', 'OrgHeiglHybridAuth\View\Helper\HybridAuth');
        $servicemanager = $e->getApplication()->getServiceManager();
        $helperManager = $servicemanager->get('viewhelpermanager');
        $helperManager->setFactory('hybridauthinfo', function() use ($helperManager) {
            return new HybridauthViewManager($helperManager);
        });

    }

    public function onBootstrap($e)
    {
    //	$e->getApplication()->getServiceManager()->get('translator');
    	$eventManager        = $e->getApplication()->getEventManager();
    	$moduleRouteListener = new ModuleRouteListener();
    	$moduleRouteListener->attach($eventManager);

        $servicemanager = $e->getApplication()->getServiceManager();
        $helperManager  = $servicemanager->get('viewhelpermanager');
        $router         = $servicemanager->get('Application')->getMvcEvent();
        $helperManager->setFactory('hybridauthinfo', function() use ($helperManager, $router) {
            return new HybridauthViewManager($helperManager, $router);
        });

    }
    
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
    	return array(
    			'Zend\Loader\StandardAutoloader' => array(
    					'namespaces' => array(
    							__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
    					),
    			),
    	);
    }
}
