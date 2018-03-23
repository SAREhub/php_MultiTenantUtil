<?php

namespace SAREhub\MultiTenantUtil\Resource;

use PHPUnit\Framework\TestCase;

class ResourceInfoTest extends TestCase
{
    public function testGetFieldWhenExists()
    {
        $info = new ResourceInfo("test_id", ["field" => "test"]);
        $this->assertEquals("test", $info->getField("field"));
    }

    public function testGetFieldWhenNotExists()
    {
        $info = new ResourceInfo("test_id", []);
        $this->assertNull($info->getField("field"));
    }

    public function testHasFieldWhenExists()
    {
        $info = new ResourceInfo("test_id", ["field" => "test"]);
        $this->assertTrue($info->hasField("field"));
    }

    public function testHasFieldWhenNotExists()
    {
        $info = new ResourceInfo("test_id", []);
        $this->assertFalse($info->hasField("field"));
    }
}
