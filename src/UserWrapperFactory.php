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

use SocialConnect\Common\Entity\User;

/**
 * This class works as factory to get an Object implementing the UserInterface
 *
 * @category  HybridAuth
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2012-2013 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     11.01.13
 * @link      https://github.com/heiglandreas/HybridAuth
 */
class UserWrapperFactory
{
   /**
    * Create the user-Proxy according to the given User-Object
    *
    * @return UserInterface
    * @throws \UnexpectedValueException
    */
    public function factory($userObject)
    {
        switch (get_class($userObject))
        {
            case User::class:
                return new SocialAuthUserWrapper($userObject);
                break;
            case 'Hybridauth\\Entity\\Profile':
            case 'Hybridauth\\Entity\\Twitter\\Profile':
                $userProxy = new HybridAuthUserWrapper();
                $userProxy->setUser($userObject);
                return $userProxy;
                break;
            default:
                return new DummyUserWrapper();
        }

        throw new \UnexpectedValueException(sprintf(
            'The given Object could not be found. Found "%s" instead',
            get_Class($userObject)
        ));
    }
}
