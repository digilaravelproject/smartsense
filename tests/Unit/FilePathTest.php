<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FilePathTest extends TestCase
{
    public function testGetStorageImagesHandlesStringPath()
    {
        // this test assumes the helper functions are available globally
        // and that the storage for the test has no actual file so we just
        // check that no exceptions are thrown and it returns a string
        $result = getStorageImages(path: 'some-random-file.jpg', type: 'product');
        // result should be a string, even if the file is not present we expect an
        // empty string or placeholder; the key thing is that no TypeError occurs
        $this->assertIsString($result);
        $this->assertTrue($result === '' || is_string($result));
    }
}
