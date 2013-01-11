<?php
/**
 * Copyright (c)2012-2012 heiglandreas
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
 * @copyright ©2012-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     27.12.12
 * @link      https://github.com/heiglandreas/
 */
namespace OrgHeiglHybridAuth\Controller;

use Hybrid_Auth;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container as SessionContainer;
use OrgHeiglHybridAuth\UserProxyFactory;

/**
 * Login or out using a social service
 *
 * @category
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2012-2012 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     27.12.12
 * @link      https://github.com/heiglandreas/
 */
class IndexController extends AbstractActionController
{
    /**
     * Stores the HybridAuth-Instance
     *
     * @var Hybrid_Auth $authenticator
     */
    protected $authenticator = null;

    /**
     * Storage of the session-Container
     *
     * @var SessionContainer $session
     */
    protected $session = null;

    /**
     * Storage of the UserProxyFactory
     *
     * @var UserProxyFactory $userProxyFactory
     */
    protected $userProxyFactory = null;
    /**
     * Set the authenticator
     *
     * @param Hybrid_Auth $authenticator The Authenticator-Backend
     *
     * @return IndexController
     */
    public function setAuthenticator(Hybrid_Auth $authenticator)
    {
        $this->authenticator = $authenticator;
        return $this;
    }

    /**
     * Set the session container
     *
     * @param SessionContainer $container The session-container to use for storing the authentication
     *
     * @return IndexController
     */
    public function setSession(SessionContainer $container)
    {
        $this->session = $container;
        return $this;
    }

    /**
     * Set the userproxy
     *
     * @param UserProxyFactory $factory The ProxyFactory
     *
     * @return IndexController
     */
    public function setUserProxyFactory(UserProxyFactory $factory)
    {
        $this->userProxyFactory = $factory;
        return $this;
    }

    /**
     * login using twitter
     */
    public function loginAction()
    {
        $config = $this->getServiceLocator()->get('Config'); //OrgHeiglHybridAuth');
        $config = $config['OrgHeiglHybridAuth'];
        try {
            $backend = $this->authenticator->authenticate($config['backend']);
            $this->session->offsetSet('authenticated', $backend->isUserConnected());
            $this->session->offsetSet('user', $this->userProxy->factory($backend->getUserProfile()));
        } catch (Exception $e) {
            $this->session->offsetSet('authenticated', false);
        }
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        //
    }

    /**
     * Call the HybridAuth-Backend
     */
    public function backendAction()
    {
        \Hybrid_Endpoint::process();
    }

}
