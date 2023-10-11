<?php

use PHPUnit_Framework_TestCase;
use function App\Fonctions\CalculComplexiteMDP;

class fonctionTest extends TestCase {

    /**
     * @test
     */

    public function testFonctionComplexiteMdp(){
        $calcul = CalculComplexiteMDP("aubry");
        $this->assertEquals(26, $calcul);
    }

}
