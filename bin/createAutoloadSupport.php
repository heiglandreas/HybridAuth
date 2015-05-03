#!/usr/bin/env php
<?php
/**
 * Copyright (c)2015-2015 heiglandreas
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
 * @copyright ©2015-2015 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     03.05.15
 * @link      https://github.com/heiglandreas/
 */
exec('pwd', $result);
$cwd = $result[0];
$VENDOR_FOLDER = realpath(getenv('VENDOR_FOLDER'));
if (! $VENDOR_FOLDER) {
    $currentFolder = dir(__FILE__);
    $vendorPos = strpos($currentFolder, '/vendor/');
    if (false !== $vendorPos) {
        $VENDOR_FOLDER = realpath(substr($currentFolder, 0, $vendorPos + 7));
    }
}
if (! $VENDOR_FOLDER) {
    $VENDOR_FOLDER = realpath(dir(__FILE__) . '../vendor');
}
if (! $VENDOR_FOLDER) {
    die ('No vendor-Folder found!');
}

$cmd[] = 'cd "' . dirname(__FILE__)  . '/../"';
$cmd[] = $VENDOR_FOLDER . '/zendframework/zendframework/bin/classmap_generator.php -o autoload_classmap.php ../../../hybridauth/hybridauth';
$cmd[] = $VENDOR_FOLDER . '/zendframework/zendframework/bin/classmap_generator.php -a -o autoload_classmap.php';
echo implode(' && ', $cmd);
exec(implode(' && ', $cmd));