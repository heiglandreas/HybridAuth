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

use OrgHeiglHybridAuth\UserToken;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

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
class HybridAuth extends AbstractHelper
{
    protected $config;

    protected $token;

    protected $urlHelper;

    public function __construct($config, UserToken $authToken, Url $urlHelper)
    {
        $this->config = $config;
        $this->token  = $authToken;
        $this->urlHelper = $urlHelper;
    }
    /**
     * create a link to either
     *
     * @param string $provider The provider to be used
     * @param string $route    The route to redirect to
     *
     * @return string
     */
    public function __invoke($provider = null, $route = '')
    {
        $route = base64_encode($route);
        $providers = (array) $this->config['backend'];
        $urlHelper = $this->urlHelper;

        if ($this->token->isAuthenticated()) {
            // Display Logged in information

            $user = sprintf($this->config['logoffstring'], $this->token->getDisplayName());
            $link = $urlHelper(
                'hybridauth/logout',
                ['redirect' => $route]
            );
            $link = sprintf($this->config['link'], $user, $link);
            return sprintf($this->config['logoffcontainer'], $link);
        }

        if (null !== $provider && in_array($provider, $providers)) {
            return $urlHelper(
                'hybridauth/login',
                array('redirect' => $route, 'provider' => $provider)
            );
        }

        if (1 == count($providers)) {
            return sprintf(
                $this->config['item'],
                sprintf(
                    $this->config['link'],
                    sprintf(
                        $this->config['loginstring'],
                        ' using ' . current($providers)
                    ),
                    $urlHelper(
                        'hybridauth/login',
                        [
                            'redirect' => $route,
                            'provider' => strtolower(current($providers)),
                        ]
                    )
                ),
                null
            );
        }

        $xhtml = array();
        foreach ($providers as $name => $backend) {
            $link = $urlHelper(
                'hybridauth/login',
                [
                    'redirect' => $route,
                    'provider' => $backend
                ]
            );
            $xhtml[] = sprintf(
                $this->config['item'],
                sprintf(
                    $this->config['link'],
                    (is_string($name)?$name:$backend),
                    $link
                ),
                $this->config['itemAttribs']
            );
        }

        return sprintf(
            $this->config['logincontainer'],
            sprintf(
                $this->config['loginstring'],
                ' using'
            ),
            sprintf(
                $this->config['itemlist'],
                implode("\n",$xhtml),
                $this->config['listAttribs']
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
            $backends = $this->config['backend'];
        }

        $backends = (array) $backends;

        return $backends;
    }
}
