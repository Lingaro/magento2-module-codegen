<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Util\Magento\Config\Dom;

use function preg_match;

/**
 * Matching of XPath expressions to path patterns
 */
class NodePathMatcher
{
    /**
     * Whether a subject XPath matches to a given path pattern
     *
     * @param string $pathPattern Example: '/some/static/path' or '/some/regexp/path(/item)+'
     * @param string $xpathSubject Example: '/some[@attr="value"]/static/ns:path'
     */
    public function match(string $pathPattern, string $xpathSubject): bool
    {
        $pathSubject = $this->simplifyXpath($xpathSubject);
        $pathPattern = '#^' . $pathPattern . '$#';
        return (bool) preg_match($pathPattern, $pathSubject);
    }

    /**
     * Strip off predicates and namespaces from the XPath
     */
    protected function simplifyXpath(string $xpath): string
    {
        $result = $xpath;
        $result = preg_replace('/\[@[^\]]+?\]/', '', $result);
        return preg_replace('/\/[^:]+?\:/', '/', $result);
    }
}
