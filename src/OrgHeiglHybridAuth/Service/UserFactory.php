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
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2013-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */
namespace OrgHeiglHybridAuth\Service;

use OrgHeiglHybridAuth\DummyUserWrapper;
use OrgHeiglHybridAuth\UserToken;
use Zend\ServiceManager;
use Hybridauth\Hybridauth;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create an instance of the HybridAuth
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2013-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */
class UserFactory implements FactoryInterface
{
    /**
     * Create the service using the configuration from the modules config-file
     *
     * @param ServiceLocator $services The ServiceLocator
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     * @return Hybrid_Auth
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $session = $services->get('OrgHeiglHybridAuthSession');
        $user = new DummyUserWrapper();
        $service = '';
        if ($session->offsetExists('authenticated') && true === $session->offsetGet('authenticated')) {
            // Display Logged in information
            $user = $session->offsetGet('user');
            $service = $session->offsetGet('backend');

        }

        $userToken = new UserToken();
        $userToken->setService($service)
                  ->setUser($user);

        return $userToken;

    }

    /**
     * Get the base URI for the current controller
     *
     * @return string
     */
    protected function getBackendUrl(ServiceLocatorInterface $sl)
    {
        $router = $sl->get('router');
        $route = $router->assemble(array(), array('name' => 'hybridauth/backend'));

        $request = $sl->get('request');
        $basePath = $request->getBasePath();
        $uri = new \Zend\Uri\Uri($request->getUri());
        $uri->setPath($basePath);
        $uri->setQuery(array());
        $uri->setFragment('');
        return $uri->getScheme() . '://' . $uri->getHost() . preg_replace('/[\/]+/', '/',  $uri->getPath() . '/' . $route);
    }
}
