<?php
/**
 * Copyright (c) 2012-2013 Andreas Heigl<andreas@heigl.org>
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
 * @copyright 2011-2012 php.ug
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     06.03.2012
 * @link      http://github.com/heiglandreas/php.ug
 */
namespace OrgHeiglHybridAuth;

return array(
    'router' => array(
        'routes' => array(
            'hybridauth' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/authenticate',
                    'defaults' => array(
                        '__NAMESPACE__' => 'OrgHeiglHybridAuth\Controller',
                        'controller' => 'IndexController',
                        'action'     => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login/:provider[/:redirect]',
                            'defaults' => array(
                                'action'   => 'login',
                                'redirect' => 'home'
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/logout[/:redirect]',
                            'defaults' => array(
                                'action' => 'logout',
                                'redirect' => 'home'
                            ),
                        ),
                    ),
                    'backend' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/backend',
                            'defaults' => array(
                                'action' => 'backend',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'OrgHeiglHybridAuth\Controller\IndexController' => 'OrgHeiglHybridAuth\Service\IndexControllerFactory',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'OrgHeiglHybridAuthSession' => 'OrgHeiglHybridAuth\Service\SessionFactory',
            'OrgHeiglHybridAuthBackend' => 'OrgHeiglHybridAuth\Service\HybridAuthFactory',
            'OrgHeiglHybridAuthCurrentUser' => 'OrgHeiglHybridAuth\Service\UserFactory',
        ),
        'invokables' => array(
            'OrgHeiglHybridAuth\UserWrapperFactory' => 'OrgHeiglHybridAuth\UserWrapperFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'hybridauthinfo' => 'OrgHeiglHybridAuth\View\Helper\HybridAuth',
        ),
    ),
    'OrgHeiglHybridAuth' => array(
        'hybrid_auth' => array(
            'base_url' => 'http://example.com/authenticate/backend',
            'providers' => array(
                'Twitter' => array('enabled' => false, 'keys' => array('key' => '', 'secret' => '')),
            ),
            'debug_mode' => false,
            'debug_file' => __DIR__ . '/hybrid_auth.log',
        ),
        'backend'         => 'twitter',
//        'backend'         => array('twitter'),
//        'backend'         => array('twitter', 'facebook', '...'),
        'link'            => '<a class="hybridauth" href="%2$s">%1$s</a>', // Will be either inserted as first parameter into item or simply returned as complete entry
        'item'            => '<li%2$s>%1$s</li>',
        'itemlist'        => '<ul%2$s>%1$s</ul>',
        'logincontainer'  => '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">%1$s<b class="caret"></b></a>%2$s</li>',
        'logoffcontainer' => '<li>%1$s</li>',
        'logoffstring'    => 'Logout %1$s',
        'loginstring'     => 'Login%1$s',
        'listAttribs'     => null, // Will be inserted as 2nd parameter into item
        'itemAttribs'     => null, // Will be inserted as 2nd parameter into itemlist

    )
);
