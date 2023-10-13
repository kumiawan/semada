<?php

namespace dfdiag\Belajar\PHP\MVC\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('Home/index', [
            "Login Semada"
        ]);

        $this->expectOutputRegex('[Login Management]');
        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Register]');
    }


}
