<?php

namespace Faker\Test\Provider\zh_TW;

class TextTest extends \PHPUnit_Framework_TestCase
{
    private $textClass;

    public function setUp()
    {
        $this->textClass = new \ReflectionClass('Faker\Provider\zh_TW\Text');
    }

    /** @test */
    function testItShouldExplodeTheStringToArray()
    {
        $this->assertSame(
            array('中', '文', '測', '試', '真', '有', '趣'),
            $this->getMethod('explode')->invokeArgs(null, array('中文測試真有趣'))
        );

        $this->assertSame(
            array('標', '點', '，', '符', '號', '！'),
            $this->getMethod('explode')->invokeArgs(null, array('標點，符號！'))
        );
    }

    protected function getMethod($name)
    {
        $method = $this->textClass->getMethod($name);

        $method->setAccessible(true);

        return $method;
    }

    /** @test */
    function testItShouldReturnTheStringLength()
    {
        $this->assertContains(
            $this->getMethod('strlen')->invokeArgs(null, array('中文測試真有趣')),
            array(7, 21)
        );
    }

    /** @test */
    function testItShouldReturnTheCharacterIsValidStartOrNot()
    {
        $this->assertTrue($this->getMethod('validStart')->invokeArgs(null, array('中')));

        $this->assertTrue($this->getMethod('validStart')->invokeArgs(null, array('2')));

        $this->assertTrue($this->getMethod('validStart')->invokeArgs(null, array('Hello')));

        $this->assertFalse($this->getMethod('validStart')->invokeArgs(null, array('。')));

        $this->assertFalse($this->getMethod('validStart')->invokeArgs(null, array('！')));
    }

    /** @test */
    function testItShouldAppendEndPunctToTheEndOfString()
    {
        $this->assertSame(
            '中文測試真有趣。',
            $this->getMethod('appendEnd')->invokeArgs(null, array('中文測試真有趣'))
        );

        $this->assertSame(
            '中文測試真有趣。',
            $this->getMethod('appendEnd')->invokeArgs(null, array('中文測試真有趣，'))
        );

        $this->assertSame(
            '中文測試真有趣！',
            $this->getMethod('appendEnd')->invokeArgs(null, array('中文測試真有趣！'))
        );
    }
}
