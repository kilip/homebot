<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Homebot\Util\Transliterator;

if(!function_exists('slugify')){
    function slugify(string $string, string $separtor = '_'): string {
        return Transliterator::transliterate($string, $separator);
    }
}
