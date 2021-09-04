<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @return array<string>
     *
     * @see https://packages.tools/testbench/basic/testcase.html#package-auto-discovery
     */
    public function ignorePackageDiscoveriesFrom(): array
    {
        return [];
    }
}
