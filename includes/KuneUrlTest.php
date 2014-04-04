<?php
class KuneUrlTest extends PHPUnit_Framework_TestCase
{
    public function testBasicUrl()
    {
        $url = new KuneUrl("http://kune.cc/#!test");

        $this->assertTrue($url->isValid());
        $this->assertEquals("test", $url->getHash());
        $this->assertNotEquals("est", $url->getHash());
        $this->assertEquals("http://kune.cc/", $url->getServer());
    }

    public function testSSLUrl()
    {
        $url = new KuneUrl("https://kune.cc/#!test");

        $this->assertTrue($url->isValid());
        $this->assertEquals("test", $url->getHash());
        $this->assertEquals("https://kune.cc/", $url->getServer());
    }

    public function testBasicNotUrl()
    {
        $url = new KuneUrl("#!test");
        $this->assertFalse($url->isValid());
    }

    public function testBasicNotHash()
    {
        $url = new KuneUrl("http://kune.cc/");
        $this->assertFalse($url->isValid());
        $url = new KuneUrl("http://kune.cc/#");
        $this->assertFalse($url->isValid());
        $url = new KuneUrl("http://kune.cc/#!");
        $this->assertFalse($url->isValid());
    }

    public function testLongUrls()
    {
        $url = new KuneUrl("http://kune.cc/?somevar=something&other=another#!test");

        $this->assertTrue($url->isValid());
        $this->assertEquals("test", $url->getHash());
        $this->assertEquals("http://kune.cc/", $url->getServer());
        $this->assertNotEquals("http://kune.cc/?somevar=something&other=another", $url->getServer());
    }
}
