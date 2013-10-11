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
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2011-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     05.12.2012
 * @link      http://github.com/heiglandreas/OrgHeiglHybridAuth
 */
namespace OrgHeiglHybridAuth\View\Helper;

use Zend\View\Helper\AbstractHtmlElement as HtmlElement;
use Zend\View\HelperPluginManager;
use Zend\Mvc\MvcEvent;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * A view helper that either generates a link to a login-widget or a logout-link
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2011-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     05.12.2012
 * @link      http://github.com/heiglandreas/OrgHeiglHybridAuth
 */
class HybridAuth extends HtmlElement implements ServiceLocatorAwareInterface
{
    /**
     * The ViewHelper-Servicemanager
     *
     * @var HelperPluginManager $viewHelperManager
     */
    protected $viewHelperManager = null;

    /**
     * The Router
     *
     * @var MvcEvent $router
     */
    protected $mvcEvent = null;

    /**
     * The serviceLocator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * create an instance of the viewhelper
     *
     * @param mixed $viewHelperManager
     */
    public function __construct(HelperPluginManager $viewHelperManager, MvcEvent $mvcEvent)
    {
        $this->viewHelperManager = $viewHelperManager;
        $this->mvcEvent          = $mvcEvent;
    }

    /**
     * create a link to either
     *
     * @return void
     */
    public function __invoke($provider = null)
    {
        $pluginManager = $this->getServiceLocator();
        $config = $pluginManager->getServiceLocator()->get('Config');

        $session = $this->viewHelperManager->getServiceLocator()->get('OrgHeiglHybridAuthSession');

        $urlHelper = $this->getViewHelper('url');
        $currentRoute = $this->getCurrentRoute();

        if ($session->offsetExists('authenticated') && true === $session->offsetGet('authenticated')) {
            // Display Logged in information
            $user = $session->offsetGet('user');
            // TODO: This has to be localized
            $user = sprintf($config['logoffString'], $user->getName());
            $link = $urlHelper('hybridauth/logout', array('redirect' => $currentRoute));
            $link = sprintf($config['link'], $user, $link);
            return sprintf($config['logoffcontainer'], $link);
        }

        $xhtml = array();
        $backendList = $this->getBackends($providers);

        if (1 == count($backendList)) {
            return sprintf(
                $config['item'],
                null,
                sprintf(
                    $config['link'],
                    (is_string(key($backendList)?key($backendList):current($backendList))),
                    $urlHelper('hybridauth/logout', array('redirect' => $currentRoute, 'provider' => current($backendList)))
                )
            );
        }
        foreach ($backendList as $name => $backend) {
            $link = $urlHelper('hybridauth/logout', array('redirect' => $currentRoute, 'provider' => $backend));
            $xhtml[] = sprintf(
                $config['item'],
                $config['itemAttribs'],
                sprintf(
                    $config['link'],
                    (is_string($name)?$name:$backend),
                    $link
                )
            );
        }


        return sprintf(
            $config['logincontainer'],
            $config['loginstring'],
            sprintf(
                $config['itemlist'],
                $config['listAttribs'],
                implode("\n",$xhtml)
            )
        );

    }

    /**
     * Get the backends
     *
     * @return array
     */
    public function getBackends($backends = null)
    {
        if (null === $backends) {
            $pluginManager = $this->getServiceLocator();
            $config = $pluginManager->getServiceLocator()->get('Config');
            $backends = $config['OrgHeiglHybridAuth']['backend'];
        }

        return (array) $backends;
    }

    /**
     * Get a certain viewHelper
     *
     * @param string $helper The name of the helper
     *
     * @return ViewHelper
     */
    protected function getViewHelper($helper)
    {
        return $this->viewHelperManager->get($helper);
    }

    /**
     * Get the current route for redirecting
     *
     * @return string
     */
    protected function getCurrentRoute()
    {
        $route = $this->mvcEvent->getRouteMatch()->getMatchedRouteName();
        return base64_encode($route);
    }

    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
