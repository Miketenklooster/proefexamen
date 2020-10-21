<?php
namespace App\Tests\Controller;

use App\Controller\imageResize;
use PHPUnit\Framework\TestCase;

class imageResizeTest extends TestCase
{
    /** @test */
    public function imageSizeCheckTest()
    {
        print("\nTesting imageResizeAction() \n");
        $imageSize = new imageResize();
        $result    = $imageSize->imageSizeCheck(3000, 2000);

        // assert that the imageSizeCheck checked the numbers correctly!
        print(" Expecting: [708,335]  Got: " . json_encode($result));
        $this->assertEquals([708, 335], $result);
    }
}
