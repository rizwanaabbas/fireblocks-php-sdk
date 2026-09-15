<?php

declare(strict_types=1);

namespace Fireblocks\Sdk\Tests;

use Fireblocks\Sdk\Api\Vaults;
use PHPUnit\Framework\TestCase;

class VaultsPagedQueryTest extends TestCase
{
    public function test_build_paged_query_repeats_tag_ids(): void
    {
        $params = [
            'includeTagIds' => ['aaa', 'bbb'],
            'namePrefix' => 'ci',
        ];

        $query = Vaults::buildPagedQueryString($params);

        $this->assertStringContainsString('includeTagIds=aaa', $query);
        $this->assertStringContainsString('includeTagIds=bbb', $query);
        $this->assertStringContainsString('namePrefix=ci', $query);
        $this->assertSame($query, Vaults::previewPagedQuery($params));
    }
}
