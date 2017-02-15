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
 * @since     28.10.13
 * @link      https://github.com/heiglandreas/
 */

namespace OrgHeiglHybridAuthTest;


use Interop\Container\ContainerInterface;
use OrgHeiglHybridAuth\DummyUserWrapper;
use OrgHeiglHybridAuth\Service\UserFactory;
use Mockery as M;
use OrgHeiglHybridAuth\SocialAuthUserWrapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreationOfUserTokenWithUnauthenticatedSession()
    {
        $factory = new UserFactory();
        $this->assertInstanceof(FactoryInterface::class, $factory);
        $servicemanager = M::mock(ContainerInterface::class);
        $session = M::mock('\Zend\Session\Container')
                 ->shouldReceive('offsetExists')
                 ->once()
                 ->with('authenticated')
                 ->andReturn(false)
                 ->mock();
        $servicemanager->shouldReceive('get')->with('OrgHeiglHybridAuthSession')->andReturn($session);

        $token = $factory($servicemanager, '');
        $this->assertInstanceof('\OrgHeiglHybridAuth\UserToken', $token);
        $this->assertFalse($token->isAuthenticated());
        $this->assertAttributeEquals(new DummyUserWrapper(), 'user', $token);
        $this->assertempty($token->getService());
    }


    public function testCreationOfUserTokenWithAuthenticatedSessionWithInValidAuthentication()
    {
        $factory = new UserFactory();
        $this->assertInstanceof(FactoryInterface::class, $factory);
        $servicemanager = M::mock(ContainerInterface::class);
        $session = M::mock('\Zend\Session\Container')
            ->shouldReceive('offsetExists')
            ->once()
            ->with('authenticated')
            ->andReturn(true)->mock();
        $session->shouldReceive('offsetGet')
            ->once()
            ->with('authenticated')
            ->andReturn(false)->mock();
        $servicemanager->shouldReceive('get')->with('OrgHeiglHybridAuthSession')->andReturn($session);

        $token = $factory($servicemanager, '');
        $this->assertInstanceof('\OrgHeiglHybridAuth\UserToken', $token);
        $this->assertFalse($token->isAuthenticated());
        $this->assertAttributeEquals(new DummyUserWrapper(), 'user', $token);
        $this->assertempty($token->getService());
    }

    public function testCreationOfUserTokenWithAuthenticatedSessionWithValidAuthentication()
    {
        $factory = new UserFactory();
        $this->assertInstanceof(FactoryInterface::class, $factory);
        $servicemanager = M::mock(ContainerInterface::class);
        $user = M::mock(SocialAuthUserWrapper::class);
        $session = M::mock('\Zend\Session\Container')
            ->shouldReceive('offsetExists')
            ->once()
            ->with('authenticated')
            ->andReturn(true)->mock();
        $session->shouldReceive('offsetGet')
            ->times(3)
            ->andReturn(true, $user, 'twitter')->mock();
        $servicemanager->shouldReceive('get')->with('OrgHeiglHybridAuthSession')->andReturn($session);

        $token = $factory($servicemanager, '');
        $this->assertInstanceof('\OrgHeiglHybridAuth\UserToken', $token);
        $this->assertTrue($token->isAuthenticated());
        $this->assertAttributeEquals($user, 'user', $token);
        $this->assertEquals('twitter', $token->getService());
    }

    public function testCreationOfUserTokenWithAuthenticatedSessionWithValidAuthenticationAndMissingSessionParts()
    {
        $this->markTestIncomplete('Proper handling of invalid arguments especialy with non-user-objects is missing');
        $factory = new UserFactory();
        $this->assertInstanceof(FactoryInterface::class, $factory);
        $servicemanager = M::mock(ContainerInterface::class);
        $session = M::mock('\Zend\Session\Container')
            ->shouldReceive('offsetExists')
            ->once()
            ->with('authenticated')
            ->andReturn(true)->mock();
        $session->shouldReceive('offsetGet')
            ->times(3)
            ->andReturn(true, null, null)->mock();
        $servicemanager->shouldReceive('get')->with('OrgHeiglHybridAuthSession')->andReturn($session);

        $token = $factory($servicemanager, '');
        $this->assertInstanceof('\OrgHeiglHybridAuth\UserToken', $token);
        $this->assertFalse($token->isAuthenticated());
       // $this->assertAttributeEquals('twitter', 'user', $token);
       // $this->assertEquals($user, $token->getService());
    }



}
 