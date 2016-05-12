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
        $config = $config['OrgHeiglHybridAuth'];

        $token = $this->viewHelperManager->getServiceLocator()->get('OrgHeiglHybridAuthToken');

        $urlHelper = $this->getViewHelper('url');
        $currentRoute = $this->getCurrentRoute();
        $providers    = $config['backend'];

        if ($token->isAuthenticated()) {
            // Display Logged in information
            // TODO: This has to be localized
            $user = sprintf($config['logoffstring'], $token->getName());
            $link = $urlHelper('hybridauth/logout', array('redirect' => $currentRoute));
            $link = sprintf($config['link'], $user, $link);
            return sprintf($config['logoffcontainer'], $link);
        }

        $backendList = $this->getBackends($providers);

        if (null !== $provider && in_array($provider, $backendList)) {
            return $urlHelper(
                'hybridauth/login',
                array('redirect' => $currentRoute, 'provider' => $provider)
            );
        }

        if (1 == count($backendList)) {
            return sprintf(
                $config['item'],
                sprintf(
                    $config['link'],
                    sprintf(
                        $config['loginstring'],
                        ' using ' . current($backendList)
                    ),
                    $urlHelper('hybridauth/login', array('redirect' => $currentRoute, 'provider' => current($backendList)))
                ),
                null
            );
        }

        $xhtml = array();
        foreach ($backendList as $name => $backend) {
            $link = $urlHelper('hybridauth/login', array('redirect' => $currentRoute, 'provider' => $backend));
            $xhtml[] = sprintf(
                $config['item'],
                sprintf(
                    $config['link'],
                    (is_string($name)?$name:$backend),
                    $link
                ),
                $config['itemAttribs']
            );
        }

        return sprintf(
            $config['logincontainer'],
            sprintf(
                $config['loginstring'],
                ' using'
            ),
            sprintf(
                $config['itemlist'],
                implode("\n",$xhtml),
                $config['listAttribs']
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

        $backends = (array) $backends;

//        foreach ($backends as $item => $value) {
//            if (is_string($item)) {
//                continue;
//            }
//            $backends[strtolower($value)] = $value;
//            unset($backends[$item]);
//        }

        return $backends;
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
