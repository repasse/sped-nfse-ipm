<?php

namespace NFePHP\NFSeIPM;

/**
 * Simple interface to RPS Class
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeIPM
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-ipm for the canonical source repository
 */

interface RpsInterface
{
    public function render();
    
    public function teste($flag = false);
    
    public function sign($flag = false);
}
