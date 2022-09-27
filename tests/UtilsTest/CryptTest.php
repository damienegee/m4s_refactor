<?php
namespace App\Tests\UtilsTest;

use App\Utils\Crypt;
use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase {

    public function testEncrypt() {
        $someString = "test";
        $result = $this->encrypt($someString);
        echo $result;
        $this->assertNotEquals($someString, $result);
    }

    public function testDecrypt() {
        $someString = "test";
        $result = $this->encrypt($someString);
        echo $result;
        $this->assertEquals($someString, $this->decrypt($result));
    }

    private function encrypt($value){
        $crypt = new Crypt();
        return $crypt->encrypt($value);
    }

    private function decrypt($value) {
        $crypt = new Crypt();
        return $crypt->decrypt($value);
    }
}