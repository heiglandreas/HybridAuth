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

use \Zend\ServiceManager;
use \Zend\Session\Container as SessionContainer;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create an instance of the session
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2013-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */
class SessionFactory implements FactoryInterface
{
    /**
     * Create the service using the configuration from the modules config-file
     *
     * @param ServiceLocator $services The ServiceLocator
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     * @return Hybrid_Auth
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['OrgHeiglHybridAuth'];

        return new SessionContainer($config['session_name']);
    }
}
