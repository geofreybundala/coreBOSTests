<?php
/*************************************************************************************************
 * Copyright 2020 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Tests.
 * The MIT License (MIT)
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *************************************************************************************************/

use PHPUnit\Framework\TestCase;

include_once 'include/Webservices/Logout.php';
require_once 'include/Webservices/WebServiceErrorCode.php';

class LogoutTest extends TestCase {

	/**
	 * Method testlogoutValid
	 * @test
	 */
	public function testlogoutValid() {
		global $current_user;
		$SessionManagerStub = $this->createMock(SessionManager::class);
		$SessionManagerStub->method('isValid')->willReturn(true);
		$actual = vtws_logout('should be a session id', $current_user, $SessionManagerStub);
		$this->assertEquals(array('message' => 'successfull'), $actual);
	}

	/**
	 * Method testlogoutEmptySession
	 * @test
	 */
	public function testlogoutEmptySession() {
		global $current_user;
		$SessionManagerStub = $this->createMock(SessionManager::class);
		$SessionManagerStub->method('isValid')->willReturn(true);
		$SessionManagerStub->method('getError')->willReturn('getError called');
		$SessionManagerStub->expects($this->once())->method('getError');
		$actual = vtws_logout('', $current_user, $SessionManagerStub);
		$this->assertEquals('getError called', $actual);
	}

	/**
	 * Method testlogoutNotValid
	 * @test
	 */
	public function testlogoutNotValid() {
		global $current_user;
		$SessionManagerStub = $this->createMock(SessionManager::class);
		$SessionManagerStub->method('isValid')->willReturn(false);
		$SessionManagerStub->method('getError')->willReturn('getError called');
		$SessionManagerStub->expects($this->once())->method('getError');
		$actual = vtws_logout('should be a session id', $current_user, $SessionManagerStub);
		$this->assertEquals('getError called', $actual);
	}
}
?>