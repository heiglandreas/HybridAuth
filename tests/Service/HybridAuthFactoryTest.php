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
 * @link      https://github.com/heiglandreas/
 */

namespace OrgHeiglHybridAuthTest;

use Interop\Container\ContainerInterface;
use \PHPUnit_Framework_TestCase;
use \Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use \OrgHeiglHybridAuth\Service\HybridAuthFactory;
use Zend\ServiceManager\Factory\FactoryInterface;
use Mockery as M;

class HybridAuthFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testSessionCreation()
    {
        $factory = new HybridAuthFactory();
        $this->assertInstanceof(FactoryInterface::class, $factory);

        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['REQUEST_URI'] = 'http://localhost';
        $_SERVER['HTTP_HOST']   = 'localhost';

        $this->markTestIncomplete('Testing inomplete due to routing issues');
    }
}
