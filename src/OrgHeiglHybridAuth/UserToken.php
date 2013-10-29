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
 * @copyright ©2012-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */

namespace OrgHeiglHybridAuth;

use OrgHeiglHybridAuth\HybridAuthUserWrapper;
use OrgHeiglHybridAuth\DummyUserWrapper;
use OrgHeiglHybridAuth\UserTokenInterface;

/**
 * This class works as proxy to the HybridAuth-User-Object
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2012-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */
class UserToken implements UserTokenInterface
{
    /**
     * The HybridAuth-User-object
     *
     * @var HybridAuthUserWrapper $userProfile
     */
    protected $user = null;

    /**
     * The service the user has been authenticated with
     *
     * @var string $service
     */
    protected $service = '';

    /**
     * Set the user-object
     *
     * @param HybridAuthUserWrapper $userProfile The userprofile to use
     *
     * @return UserToken
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the service the user has been authenticated with
     *
     * @param string $service
     *
     * @return UserToken
     */
    public function setService($service)
    {
        $this->service = strtolower($service);

        return $this;
    }

    /**
     * Get the ID of the user
     *
     * @return string
     */
    public function getUID()
    {
        return $this->user->getUid();
    }

    /**
     * Get the name of the user
     *
     * @return string
     */
    public function getName()
    {
        return $this->user->getName();
    }

    /**
     * Get the eMail-Address of the user
     *
     * @return string
     */
    public function getMail()
    {
        return $this->user->getMail();
    }

    /**
     * Get the language of the user
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->user->getLanguage();
    }

    /**
     * Get the display-name of the user.
     */
    public function getDisplayName()
    {
        return $this->user->getDisplayName();
    }

    /**
     * Get the service
     *
     * @see UserTokenInterface::getService
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Check whether the user has been authenticated or not
     *
     * @see UserTokenInterface::isAuthenticated
     * @return boolean
     */
    public function isAuthenticated()
    {
        if (! $this->getService()) {
            return false;
        }

        if (__NAMESPACE__ . '\DummyUserWrapper' === get_class($this->user)) {
            return false;
        }

        return true;
    }
}
