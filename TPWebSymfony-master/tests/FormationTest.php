<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author oreoc
 */
class FormationTest extends TestCase{
    public function testgetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2022-02-27"));
        $this->assertEquals("27/02/2022", $formation->getPublishedAtString());
    }
}
