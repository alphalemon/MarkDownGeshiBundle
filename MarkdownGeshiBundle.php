<?php
/*
 * This file is part of the BusinessCarouselBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\Block\MarkdownGeshiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkdownGeshiBundle extends Bundle
{
    public function getAlphaLemonBundleDescription()
    {
        return 'Markdown with geshi';
    }
}
