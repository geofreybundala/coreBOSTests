<?php
/*************************************************************************************************
 * Copyright 2016 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Tests.
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

class CommonUtilsTest extends TestCase {

	/****
	 * TEST Users
	 ****/
	public $testusers = array(
		'usrtestdmy' => 5,
		'usrtestmdy' => 6,
		'usrtestymd' => 7,
		'usrtesttz' => 10,
		'usrnocreate' => 11,
		'usrtestmcurrency' => 12
	);

	/**
	 * Method isAdminProvider
	 * params
	 */
	public function isAdminProvider() {
		return array(
			[1, true],
			[5, false],
			[6, false],
			[7, false],
			[10, false],
			[11, false],
			[12, false],
			[0, false],
			[-12, false],
			['', false],
		);
	}

	/**
	 * Method testisAdmin
	 * @test
	 * @dataProvider isAdminProvider
	 */
	public function testisAdmin($userid, $expected) {
		if (!empty($userid) && $userid>0) {
			$user = new Users();
			$user->retrieveCurrentUserInfoFromFile($userid);
		} else {
			$user = $userid;
		}
		$this->assertEquals($expected, is_admin($user), 'isAdmin '.$userid);
	}

	/**
	 * Method testisAdminID
	 * @test
	 * @dataProvider isAdminProvider
	 */
	public function testisAdminID($userid, $expected) {
		$this->assertEquals($expected, is_adminID($userid), 'is_adminID '.$userid);
	}

	/**
	 * Method testgetCurrencyName
	 * @test
	 */
	public function testgetCurrencyName() {
		$actual = getCurrencyName(1, false);
		$this->assertEquals('Euro', $actual, 'currency name 1 no symbol');
		$actual = getCurrencyName(1, true);
		$this->assertEquals('Euro : &euro;', $actual, 'currency name 1 symbol');
		$actual = getCurrencyName(2, false);
		$this->assertEquals('USA, Dollars', $actual, 'currency name 2 no symbol');
		$actual = getCurrencyName(2, true);
		$this->assertEquals('USA, Dollars : $', $actual, 'currency name 2 symbol');
	}

	/**
	 * Method testgetCurrencyName
	 * @test
	 */
	public function testgetCurrencySymbolandCRate() {
		$actual = getCurrencySymbolandCRate(1);
		$expected = array(
			'rate' => '1.000000',
			'symbol' => '&euro;',
			'position' => '1.0$',
		);
		$this->assertEquals($expected, $actual, 'currency symrate 1');
		$actual = getCurrencySymbolandCRate(2);
		$expected = array(
			'rate' => '1.100000',
			'symbol' => '$',
			'position' => '$1.0',
		);
		$this->assertEquals($expected, $actual, 'currency symrate 2');
	}

	/**
	 * Method testgetBasic_Advance_SearchURL
	 * @test
	 */
	public function testgetBasic_Advance_SearchURL() {
		$actualRequest = $_REQUEST;
		$_REQUEST = array(
		  'module' => 'Home',
		  'action' => 'HomeAjax',
		  'file' => 'HomeWidgetBlockList',
		  'widgetInfoList' => '[{"widgetId":14,"widgetType":"Default"},{"widgetId":15,"widgetType":"Tag Cloud"}]',
		  'query' => 'true',
		  'searchtype' => 'advance',
		  'advft_criteria' => '[{"groupid":1,"columnname":"vtiger_faq:status:faqstatus:Faq_Status:V","comparator":"n","value":"Obsolete","columncondition":null}]',
		  'advft_criteria_groups' => '{"1":{"groupcondition":null}}',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=advance';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 1');
		$_REQUEST = array(
		  'search_field' => 'firstname',
		  'searchtype' => 'BasicSearch',
		  'search_text' => 'lina',
		  'parenttab' => 'ptab',
		  'query' => 'true',
		  'file' => 'index',
		  'module' => 'Contacts',
		  'action' => 'ContactsAjax',
		  'ajax' => 'true',
		  'search' => 'true',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=BasicSearch&search_field=firstname&search_text=lina&type=';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 2');
		$_REQUEST = array(
		  'search_field' => 'account_id',
		  'searchtype' => 'BasicSearch',
		  'search_text' => '&',
		  'parenttab' => 'ptab',
		  'query' => 'true',
		  'file' => 'index',
		  'module' => 'Contacts',
		  'action' => 'ContactsAjax',
		  'ajax' => 'true',
		  'search' => 'true',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=BasicSearch&search_field=account_id&search_text=&amp;&type=';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 3');
		$_REQUEST = array(
		  'advft_criteria' => '[{"groupid":"1","columnname":"vtiger_contactdetails:firstname:firstname:Contacts_First_Name:V","comparator":"c","value":"lina","columncondition":"and"},{"groupid":"1","columnname":"vtiger_contactdetails:accountid:account_id:Contacts_Account_Name:V","comparator":"c","value":"&","columncondition":""}]',
		  'advft_criteria_groups' => '[null,{"groupcondition":""}]',
		  'searchtype' => 'advance',
		  'query' => 'true',
		  'file' => 'index',
		  'module' => 'Contacts',
		  'action' => 'ContactsAjax',
		  'ajax' => 'true',
		  'search' => 'true',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=advance';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 4');
		$_REQUEST = array(
		  'advft_criteria' => '[{"groupid":"1","columnname":"vtiger_accountscf:cf_722:cf_722:Accounts_Date:D","comparator":"e","value":"$","columncondition":""}]',
		  'advft_criteria_groups' => '[null,{"groupcondition":""}]',
		  'searchtype' => 'advance',
		  'query' => 'true',
		  'file' => 'index',
		  'module' => 'Accounts',
		  'action' => 'AccountsAjax',
		  'ajax' => 'true',
		  'search' => 'true',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=advance';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 5');
		$_REQUEST = array(
		  'search_field' => 'account_id',
		  'searchtype' => 'BasicSearch',
		  'search_text' => '& li',
		  'parenttab' => 'ptab',
		  'query' => 'true',
		  'file' => 'index',
		  'module' => 'Contacts',
		  'action' => 'ContactsAjax',
		  'ajax' => 'true',
		  'search' => 'true',
		);
		$actual = getBasic_Advance_SearchURL();
		$expected = '&query=true&searchtype=BasicSearch&search_field=account_id&search_text=&amp; li&type=';
		$this->assertEquals($expected, $actual, 'getBasic_Advance_SearchURL 6');
		$_REQUEST = $actualRequest;
	}

	/**
	 * Method testisInsideApplication
	 * @test
	 */
	public function testisInsideApplication() {
		global $root_directory;
		$this->assertTrue(isInsideApplication('modules'), "isInsideApplication modules");
		$this->assertTrue(isInsideApplication('modules/cbupdater'), "isInsideApplication modules/cbupdater");
		$this->assertTrue(isInsideApplication('modules/cbupdater/cbupdater.php'), "isInsideApplication modules/cbupdater/cbupdater.php");
		$this->assertFalse(isInsideApplication('/etc'), "isInsideApplication /etc");
		$this->assertFalse(isInsideApplication('..'), "isInsideApplication ..");
		$this->assertFalse(isInsideApplication('&#046;&#046;'), "isInsideApplication encoded ..");
		$this->assertFalse(isInsideApplication('modules\cbupdater\cbupdater.php'), "isInsideApplication modules\cbupdater\cbupdater.php");
		$this->assertFalse(isInsideApplication('../modules/cbupdater/cbupdater.php'), "isInsideApplication ../modules/cbupdater/cbupdater.php");
		$this->assertFalse(isInsideApplication('modules/../../../../etc'), "isInsideApplication modules/../../../../etc");
		$this->assertFalse(isInsideApplication('\etc'), "isInsideApplication \etc");
		$this->assertFalse(isInsideApplication('modules\\cbupdater\\cbupdater.php'), "isInsideApplication modules\\cbupdater\\cbupdater.php");
		$this->assertFalse(isInsideApplication('modules\\\\cbupdater\\\\cbupdater.php'), 'isInsideApplication modules\\\\cbupdater\\\\cbupdater.php');
		$this->assertTrue(isInsideApplication($root_directory.'index.php'), 'isInsideApplication $root_directory');
		$this->assertFalse(isInsideApplication('\\etc'), 'isInsideApplication \\etc');
	}

	/**
	 * Method testisFileAccessible
	 * @test
	 */
	public function testisFileAccessible() {
		$this->assertTrue(true, 'isFileAccessible tested in testisInsideApplication');
	}

	/**
	 * Method getbr2nlProvider
	 * params
	 */
	public function getbr2nlProvider() {
		return array(
			array('line1','line1','br2nl one line'),
			array('line1<br>line2','line1<br>line2','br2nl two lines <br>'),
			array('line1<br/>line2','line1<br/>line2','br2nl two lines <br/>'),
			array('line1
line2','line1\nline2','br2nl two lines nl'),
			array('line1line2','line1\rline2','br2nl two lines cr'),
			array('line2
line2','line2\r\nline2','br2nl two lines crnl'),
			array('line1
line2','line1\n\rline2','br2nl two lines nlcr'),
			array("line1\nline2",'line1\nline2','br2nl two lines nl'),
			array("line1\rline2",'line1\rline2','br2nl two lines cr'),
			array("line1\r\nline2",'line1\r\nline2','br2nl two lines crnl'),
			array("line1\n\rline2",'line1\n\rline2','br2nl two lines nlcr'),
			array("line1'line2",'line1 line2','br2nl two lines singlequote'),
			array('line1"line2','line1 line2','br2nl two lines doublequote'),
		);
	}

	/**
	 * Method testbr2nl
	 * @test
	 * @dataProvider getbr2nlProvider
	 */
	public function testbr2nl($input, $expected, $msg) {
		$this->assertEquals($expected, br2nl($input), $msg);
	}

	/**
	 * Method getbr2nlvtProvider
	 * params
	 */
	public function getbr2nlvtProvider() {
		return array(
			array('line1','line1','br2nl one line'),
			array('line1<br>line2','line1<br>line2','br2nl two lines <br>'),
			array('line1<br/>line2','line1<br/>line2','br2nl two lines <br/>'),
			array('line1
line2','line1
line2','br2nl two lines nl'),
			array('line1line2','line1line2','br2nl two lines cr'),
			array('line2
line2','line2 line2','br2nl two lines crnl'),
			array('line1

line2','line1

line2','br2nl two lines nlcr'),
			array("line1\nline2","line1\nline2",'br2nl two lines nl'),
			array("line1\rline2","line1\rline2",'br2nl two lines cr'),
			array("line1\r\nline2",'line1 line2','br2nl two lines crnl'),
			array("line1\n\rline2","line1\n\rline2",'br2nl two lines nlcr'),
			array("line1'line2","line1'line2",'br2nl two lines singlequote'),
			array('line1"line2','line1"line2','br2nl two lines doublequote'),
		);
	}

	/**
	 * Method testbr2nl_vt
	 * @test
	 * @dataProvider getbr2nlvtProvider
	 */
	public function testbr2nl_vt($input, $expected, $msg) {
		$this->assertEquals($expected, br2nl_vt($input), $msg);
	}

	/**
	 * Method testgetUserslist1
	 * @test
	 */
	public function testgetUserslist1() {
		global $current_user, $module;
		$module = 'Utilities';
		$hold_user = $current_user;
		$user = new Users();
		$user->retrieveCurrentUserInfoFromFile(1);
		$current_user = $user;
		$actual = getUserslist(true);
		$expected = '<option value=1 selected> Administrator</option><option value=11 >nocreate cbTest</option><option value=5 >cbTest testdmy</option><option value=8 >cbTest testes</option><option value=12 >cbTest testmcurrency</option><option value=6 >cbTest testmdy</option><option value=10 >cbTest testtz</option><option value=13 >cbTest testtz-3</option><option value=7 >cbTest testymd</option>';
		$this->assertEquals($expected, $actual, 'getUserslist admin true');
		$actual = getUserslist(false);
		$expected = '<option value=1> Administrator</option><option value=11>nocreate cbTest</option><option value=5>cbTest testdmy</option><option value=8>cbTest testes</option><option value=12>cbTest testmcurrency</option><option value=6>cbTest testmdy</option><option value=10>cbTest testtz</option><option value=13>cbTest testtz-3</option><option value=7>cbTest testymd</option>';
		$this->assertEquals($expected, $actual, 'getUserslist admin false');
		$actual = getUserslist(true, 5);
		$expected = '<option value=1 > Administrator</option><option value=11 >nocreate cbTest</option><option value=5 selected>cbTest testdmy</option><option value=8 >cbTest testes</option><option value=12 >cbTest testmcurrency</option><option value=6 >cbTest testmdy</option><option value=10 >cbTest testtz</option><option value=13 >cbTest testtz-3</option><option value=7 >cbTest testymd</option>';
		$this->assertEquals($expected, $actual, 'getUserslist admin false');
		$current_user = $hold_user;
	}

	/**
	 * Method testgetUserslist5
	 * @test
	 */
//	function testgetUserslist2() {
		// Cannot be executed together with testgetUserslist1 due to cache
// 		global $current_user, $module;
// 		$module = 'Utilities';
// 		$hold_user = $current_user;
// 		$user = new Users();
// 		$user->retrieveCurrentUserInfoFromFile($this->testusers['usrtestdmy']);
// 		$current_user = $user;
// 		$actual = getUserslist(true);
// 		$expected = '<option value=11 >nocreate cbTest</option><option value=5 selected>cbTest testdmy</option>';
// 		$this->assertEquals($expected, $actual, 'getUserslist testdmy true');
// 		$actual = getUserslist(false);
// 		$expected = '<option value=11>nocreate cbTest</option><option value=5>cbTest testdmy</option>';
// 		$this->assertEquals($expected, $actual, 'getUserslist testdmy false');
// 		$current_user = $hold_user;
//	}

	/**
	 * Method getMergedDescriptionProvider
	 * params
	 */
	public function getMergedDescriptionProvider() {
		$lang = return_module_language('en_us', 'Reports');
		$mes = date('m')-1;
		return array(
			array('Description $leads-firstname$',4260,'Leads','Description Timothy','Lead name alone'),
			array('Description $users-user_name$',5,'Users','Description testdmy','User name alone'),
			array('Description $leads-firstname$ $users-user_name$',4260,'Leads','Description Timothy $users-user_name$','Lead name + user name'),
			array('Description $leads-firstname$ $users-user_name$',5,'Users','Description $leads-firstname$ testdmy','User name + lead name'),
			array('Description $leads-firstname$ $users-user_name$',0,'Leads','Description $leads-firstname$ $users-user_name$','Empty ID'),
			array('Description $leads-firstname$ $users-user_name$',5,'','Description $leads-firstname$ $users-user_name$','Empty Entity'),
			array('$leads-firstname$  Firstname

$leads-lastname$  Last Name

$leads-email$

Email',4260,'Leads','Timothy  Firstname

Mulqueen  Last Name

timothy_mulqueen@mulqueen.org

Email','Multiple vars and lines'),
			array('Dear 

Thank you for your confidence in our ability to serve you. 
We are glad to be given the chance to serve you.I look ',5,'Users','Dear 

Thank you for your confidence in our ability to serve you. 
We are glad to be given the chance to serve you.I look ','Just text'),
			array('<table align="center" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; text-decoration: none; background-color: rgb(122, 122, 254);" width="700">
				<tr>
					<td align="center" rowspan="4">$logo$</td>
					<td align="center">&nbsp;</td>
				</tr>
				<td style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(22, 72, 134); font-weight: bolder; line-height: 15px;">Dear $contact_name$,</td>
				<tr>
					<td><br />
					User ID : <font color="#990000"><strong> $login_name$</strong></font></td>
				</tr>
				<tr>
					<td>Password: <font color="#990000"><strong> $password$</strong></font></td>
				</tr>
				<tr>
					<td align="center"><strong>$URL$</strong></td>
				</tr>
			</table>',1086,'Contacts','<table align="center" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; font-weight: normal; text-decoration: none; background-color: rgb(122, 122, 254);" width="700">
				<tr>
					<td align="center" rowspan="4">$logo$</td>
					<td align="center">&nbsp;</td>
				</tr>
				<td style="font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: rgb(22, 72, 134); font-weight: bolder; line-height: 15px;">Dear $contact_name$,</td>
				<tr>
					<td><br />
					User ID : <font color="#990000"><strong> $login_name$</strong></font></td>
				</tr>
				<tr>
					<td>Password: <font color="#990000"><strong> $password$</strong></font></td>
				</tr>
				<tr>
					<td align="center"><strong>$URL$</strong></td>
				</tr>
			</table>','HTML and inexistent variables'),
			array('Contact name: $contacts-lastname$
Contact Image: $contacts-imagename$
Contact Image Field: $contacts-imagename_fullpath$',1086,'Contacts','Contact name: Hirpara
Contact Image: 
Contact Image Field: $contacts-imagename_fullpath$','Contact Image'),
			array('Contact name: $contacts-lastname$
Current Date: $custom-currentdate$',1086,'Contacts','Contact name: Hirpara
Current Date: '.$lang['MONTH_STRINGS'][$mes].date(" j, Y"),'General variables'),
			array('Contact name: $contacts-lastname$
Current Date: $custom-currentdate$
Contact WF name: $firstname $lastname
Account Name: $(account_id : (Accounts) accountname)
Site URL: $URL$',1086,'Contacts','Contact name: Hirpara
Current Date: '.$lang['MONTH_STRINGS'][$mes].date(' j, Y').'
Contact WF name: Felix Hirpara
Account Name: Chemex Labs Ltd
Site URL: $URL$','WF vars variables'),
			array('Description $(general : (__VtigerMeta__) scanQRCode->firstname)', 4260, 'Leads','/^Description <img src="cid:qrcode[a-zA-Z0-9]+" \/>$/', 'Lead QRCode name'),
			array('
Description $(general : (__VtigerMeta__) scanQRCode->firstname)
', 4260, 'Leads','/^
Description <img src="cid:qrcode[a-zA-Z0-9]+" \/>
$/', 'Lead QRCode name multiline'),
			array('first line
Description $(general : (__VtigerMeta__) scanQRCode->firstname)
WF name: $firstname $lastname
$leads-firstname$ $users-user_name$
$leads-lastname$ last line
', 4260, 'Leads','/^first line
Description <img src="cid:qrcode[a-zA-Z0-9]+" \/>
WF name: Timothy Mulqueen
Timothy \$users-user_name\$
Mulqueen last line
$/', 'Lead QRCode name multiline mixed with legacy and workflow field references'),
		);
	}

	/**
	 * Method testgetMergedDescription
	 * @test
	 * @dataProvider getMergedDescriptionProvider
	 */
	public function testgetMergedDescription($description, $id, $parent_type, $expected, $msg) {
		if (strpos($description, 'scanQRCode->')) {
			$this->assertMatchesRegularExpression($expected, getMergedDescription($description, $id, $parent_type), $msg);
		} else {
			$this->assertEquals($expected, getMergedDescription($description, $id, $parent_type), $msg);
		}
	}

	/**
	 * Method getMergedDescriptionForURLProvider
	 * params
	 */
	public function getMergedDescriptionForURLProvider() {
		return array(
			array(
				'http://myurl.tld/index.php?&shippingmethod=$assets-shippingmethod$&datesold=$assets-datesold$&assetname=$assets-assetname$&accountname=$accounts-accountname$',
				4068,
				'Assets',
				'http://myurl.tld/index.php?shippingmethod=&datesold=2015-11-16&assetname=Central+Hrdwr+%26+Elec+Corp+%3A%3A+Professional+USB+Factory+Logo+Printed+Colorful+Bracelet+USB+Flash+Drive&accountname=Central+Hrdwr+%26+Elec+Corp',
				'Assets and Account with ampersand'
			),
			array(
				'http://myurl.tld/index.php',
				4068,
				'Assets',
				'http://myurl.tld/index.php',
				'Assets with URL'
			),
			array(
				'http://myurl.tld',
				4068,
				'Assets',
				'http://myurl.tld',
				'Assets with URL no path'
			),
			array(
				'https://myurl.tld',
				4068,
				'Assets',
				'https://myurl.tld',
				'Assets with URL https'
			),
			array(
				'file://myurl.tld',
				4068,
				'Assets',
				'file://myurl.tld',
				'Assets with URL file'
			),
			array(
				'//myurl.tld',
				4068,
				'Assets',
				'//myurl.tld',
				'Assets with URL no scheme'
			),
			array(
				'http://myurl.tld/index.php?accountname=$accounts-accountname$',
				74,
				'Accounts',
				'http://myurl.tld/index.php?accountname=Chemex+Labs+Ltd',
				'Accounts with URL and name'
			),
			array(
				'http://myurl.tld/index.php?accountname=$accounts-accountname$&hasequal=has=equal',
				74,
				'Accounts',
				'http://myurl.tld/index.php?accountname=Chemex+Labs+Ltd&hasequal=has%3Dequal',
				'Accounts with URL, parameter with equal sign and name'
			),
			array('Description $leads-firstname$', 4260, 'Leads','Description Timothy', 'Lead name alone'),
		);
	}

	/**
	 * Method testgetMergedDescriptionForURL
	 * @test
	 * @dataProvider getMergedDescriptionForURLProvider
	 */
	public function testgetMergedDescriptionForURL($url, $id, $parent_type, $expected, $msg) {
		$this->assertEquals($expected, getMergedDescriptionForURL($url, $id, $parent_type), $msg);
	}

	/**
	 * Method testgetSingleFieldValue
	 * @test
	 */
	public function testgetSingleFieldValue() {
		$this->assertEquals(74, getSingleFieldValue('vtiger_account', 'accountid', 'accountid', 74), 'ID field itself');
		$this->assertEquals('Chemex Labs Ltd', getSingleFieldValue('vtiger_account', 'accountname', 'accountid', 74), 'Name field');
		$this->assertEquals('2015-11-16', getSingleFieldValue('vtiger_assets', 'datesold', 'assetsid', 4068), 'asset field');
		$this->assertEquals('GlobalVariable', getSingleFieldValue('vtiger_audit_trial', 'module', 'auditid', 12), 'audit trail field');
	}

	/**
	 * Method testgetReturnPathProvider
	 * params
	 */
	public function getReturnPathProvider() {
		return array(
			array('tsolucio.com','','info@tsolucio.com','normal'),
			array('ispconfig.tsolucio.com','','info@tsolucio.com','superdomain'),
			array('isp.ispconfig.tsolucio.com','','info@tsolucio.com','super superdomain'),
			array('localhost','','','localhost'),
			array('192.168.0.190','','info@[192.168.0.190]','ip'),
			array('tsolucio.cloud','','info@tsolucio.cloud','tld idn'),
			array('tld','','info@tld','only one'),
			array('http://tld','','info@tld','http'),
			array('tld:port','','info@tld','port'),
			/////////////////
			array('tsolucio.com','noreply@tsolucio.com','info@tsolucio.com','normal from'),
			array('ispconfig.tsolucio.com','noreply@tsolucio.com','noreply@tsolucio.com','superdomain from'),
			array('isp.ispconfig.tsolucio.com','noreply@tsolucio.com','noreply@tsolucio.com','super superdomain from'),
			array('localhost','noreply@tsolucio.com','noreply@tsolucio.com','localhost from'),
			array('192.168.0.190','noreply@tsolucio.com','info@[192.168.0.190]','ip from'),
			array('tsolucio.cloud','noreply@tsolucio.com','info@tsolucio.cloud','tld idn from'),
			array('tld','noreply@tsolucio.com','info@tld','only one from'),
			array('http://tld','noreply@tsolucio.com','info@tld','http from'),
			array('tld:port','noreply@tsolucio.com','info@tld','port from'),
			/////////////////
			array('tsolucio.com','ggg@tld','info@tsolucio.com','normal from tld'),
			array('ispconfig.tsolucio.com','ggg@tld','info@tsolucio.com','superdomain from tld'),
			array('isp.ispconfig.tsolucio.com','ggg@tld','info@tsolucio.com','super superdomain tld'),
			array('localhost','ggg@tld','ggg@tld','localhost from tld'),
			array('192.168.0.190','ggg@tld','info@[192.168.0.190]','ip from tld'),
			array('tsolucio.cloud','ggg@tld','info@tsolucio.cloud','tld idn from tld'),
			array('tld','ggg@tld','info@tld','only one from tld'),
			array('http://tld','ggg@tld','info@tld','http from tld'),
			array('tld:port','ggg@tld','info@tld','port from tld'),
		);
	}

	/**
	 * Method testgetReturnPath
	 * @test
	 * @dataProvider getReturnPathProvider
	 */
// 	function testgetReturnPath($host, $from_email, $expected, $msg) {
// 		$this->assertEquals($expected, getReturnPath($host, $from_email), $msg);
// 	}

	/**
	 * Method getrecurringObjValueProvider
	 * params
	 */
	public function getrecurringObjValueProvider() {
		return array(
		array(null,null,null,null,null,null,
			null,null,null,null,null,null,null,null,null,
			null,null,null,null,'no data'),
		array('--None--',null,null,null,null,null,
			null,null,null,null,null,null,null,null,null,
			null,null,null,null,'no data --None--'),
		////////////////////
		array('Daily','2017-01-01','2017-01-10',null,'15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,2,array('2017-01-01','2017-01-03','2017-01-05','2017-01-07','2017-01-09'),'every 2 days'),
		array('Daily','2017-01-01','2017-01-10',null,'15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,3,array('2017-01-01','2017-01-04','2017-01-07','2017-01-10'),'every 3 days'),
		array('Yearly','2017-01-01','2021-01-10',null,'15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,2,array('2017-01-01','2019-01-01','2021-01-01'),'every 2 years'),
		array('Yearly','2017-01-01','2021-01-10',null,'15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,3,array('2017-01-01','2020-01-01'),'every 3 years'),
		////////////////////
		array('Daily','2017-01-01',null,'2017-01-10','15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,2,array('2017-01-01','2017-01-03','2017-01-05','2017-01-07','2017-01-09'),'every 2 days'),
		array('Daily','2017-01-01',null,'2017-01-10','15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,3,array('2017-01-01','2017-01-04','2017-01-07','2017-01-10'),'every 3 days'),
		array('Yearly','2017-01-01',null,'2021-01-10','15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,2,array('2017-01-01','2019-01-01','2021-01-01'),'every 2 years'),
		array('Yearly','2017-01-01',null,'2021-01-10','15:00','16:00',
			null,null,null,null,null,null,null,null,null,
			null,null,3,array('2017-01-01','2020-01-01'),'every 3 years'),
		////////////////////
		array('Weekly','2017-01-01','2017-01-20',null,'15:00','16:00',
			null,1,null,1,null,1,null,null,null,
			null,null,1,array('2017-01-01','2017-01-02','2017-01-04','2017-01-06','2017-01-09','2017-01-11','2017-01-13'),'every M-X-V days'),
		array('Weekly','2017-01-01','2017-01-20',null,'15:00','16:00',
			null,1,null,1,null,1,null,null,null,
			null,null,2,array('2017-01-01','2017-01-09','2017-01-11','2017-01-13'),'every 2 weeks M-X-V days'),
		array('Weekly','2017-01-01','2017-01-20',null,'15:00','16:00',
			null,1,null,1,null,1,null,null,null,
			null,null,3,array('2017-01-01'),'every 3 weeks M-X-V days'),
		array('Weekly','2017-01-01','2017-01-30',null,'15:00','16:00',
			null,1,null,1,null,1,null,null,null,
			null,null,3,array('2017-01-01','2017-01-16','2017-01-18','2017-01-20'),'every 3 weeks M-X-V days'),
		////////////////////
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'date',null,
			null,null,1,array('2017-01-01','2017-02-01','2017-03-01','2017-04-01'),'once a month'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'date',null,
			null,null,2,array('2017-01-01','2017-03-01'),'once every two months'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'date',4,
			null,null,1,array('2017-01-01','2017-01-04','2017-02-04','2017-03-04','2017-04-04'),'once a month on the fourth'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'date',4,
			null,null,2,array('2017-01-01','2017-01-04','2017-03-04'),'once every two months on the fourth'),
		////////////////////
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'day',null,
			'second',3,1,array('2017-01-01','2017-01-11','2017-02-08','2017-03-08','2017-04-12'),'second X month'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'day',null,
			'second',3,2,array('2017-01-01','2017-01-11','2017-03-08'),'second X every two months'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'day',null,
			'second',2,1,array('2017-01-01','2017-01-10','2017-02-14','2017-03-14','2017-04-11'),'second T once a month'),
		array('Monthly','2017-01-01','2017-04-20',null,'15:00','16:00',
			null,null,null,null,null,null,null,'day',null,
			'second',2,2,array('2017-01-01','2017-01-10','2017-03-14'),'second X once every two months'),
		);
	}

	/**
	 * Method testgetrecurringObjValue
	 * @test
	 * @dataProvider getrecurringObjValueProvider
	 */
	public function testgetrecurringObjValue(
		$recurringtype, $date_start, $calendar_repeat_limit_date, $due_date, $time_start, $time_end,
		$sun_flag, $mon_flag, $tue_flag, $wed_flag, $thu_flag, $fri_flag, $sat_flag, $repeatMonth,
		$repeatMonth_date, $repeatmonth_daytype, $repeatMonth_day, $repeat_frequency, $expected, $msg
	) {
		$_REQUEST['recurringtype'] = $recurringtype;
		$_REQUEST['date_start'] = $date_start;
		$_REQUEST['calendar_repeat_limit_date'] = $calendar_repeat_limit_date;
		$_REQUEST['due_date'] = $due_date;
		$_REQUEST['time_start'] = $time_start;
		$_REQUEST['time_end'] = $time_end;
		$_REQUEST['sun_flag'] = $sun_flag;
		$_REQUEST['mon_flag'] = $mon_flag;
		$_REQUEST['tue_flag'] = $tue_flag;
		$_REQUEST['wed_flag'] = $wed_flag;
		$_REQUEST['thu_flag'] = $thu_flag;
		$_REQUEST['fri_flag'] = $fri_flag;
		$_REQUEST['sat_flag'] = $sat_flag;
		$_REQUEST['repeatMonth'] = $repeatMonth;
		$_REQUEST['repeatMonth_date'] = $repeatMonth_date;
		$_REQUEST['repeatMonth_daytype'] = $repeatmonth_daytype;
		$_REQUEST['repeatMonth_day'] = $repeatMonth_day;
		$_REQUEST['repeat_frequency'] = $repeat_frequency;
		$actual = getrecurringObjValue();
		if (!is_null($expected)) {
			$this->assertEquals($expected, $actual->recurringdates, $msg);
		} else {
			$this->assertEquals($expected, $actual, $msg);
		}
	}

	/**
	 * Method testgetSalesEntityType
	 * @test
	 */
	public function testgetSalesEntityType() {
		$this->assertEquals('Accounts', getSalesEntityType('74'), 'Accounts setype');
		$this->assertEquals('CobroPago', getSalesEntityType('14335'), 'Payment setype');
		$this->assertEquals('Contacts', getSalesEntityType('1090'), 'Contacts setype');
		$this->assertEquals('', getSalesEntityType('-1'), 'invalid setype');
	}

	/**
	 * Method testUserCount
	 * @test
	 */
	public function testUserCount() {
		$expected = array(
			'user' => 10,
			'admin' => 1,
			'nonadmin' => 9,
		);
		$this->assertEquals($expected, UserCount(), 'User count');
	}

	/**
	 * Method testpicklistHasDependency
	 * @test
	 */
	public function testpicklistHasDependency() {
		$this->assertTrue(picklistHasDependency('cf_729', 'Accounts'), 'picklist dependency on Accounts CF');
		$this->assertFalse(picklistHasDependency('industry', 'Accounts'), 'picklist dependency on Accounts industry');
	}

	/**
	 * Method testgetView
	 * @test
	 */
	public function testgetView() {
		$this->assertEquals(getView(''), 'create_view', 'get view empty');
		$this->assertEquals(getView('edit'), 'edit_view', 'get view edit');
		$this->assertEquals(getView('junk'), 'create_view', 'get view anything else');
	}

	/**
	 * Method testgetBlockId
	 * @test
	 */
	public function testgetBlockId() {
		$this->assertEquals(getBlockId(6, 'LBL_ACCOUNT_INFORMATION'), '9', 'getblockid accounts');
		$this->assertEquals(getBlockId(4, 'LBL_CONTACT_INFORMATION'), '4', 'getblockid contacts');
		$this->assertEquals(getBlockId(4, 'non-existent'), '', 'getblockid non-existent');
	}

	/**
	 * Method testgetEntityName
	 * @test
	 */
	public function testgetEntityName() {
		$this->assertEquals(getEntityName('Accounts', 74), array('74'=>'Chemex Labs Ltd'), 'getEntityName accounts');
		$this->assertEquals(getEntityName('Accounts', array(74,75)), array('74'=>'Chemex Labs Ltd','75'=>'Atrium Marketing Inc'), 'getEntityName accounts array');
		$this->assertEquals(getEntityName('Contacts', 1084), array('1084'=>'Lina Schwiebert'), 'getEntityName contacts');
		$this->assertEquals(getEntityName('Contacts', 1847), array('1847'=>'&aacute;&ccedil;&egrave;&ntilde;t&ouml;s &lt;Tindal&gt;'), 'getEntityName contacts special characters');
		$this->assertEquals(getEntityName('Users', 5), array('5'=>'cbTest testdmy'), 'getEntityName Users');
		$this->assertEquals(getEntityName('NoExists', 5), array(), 'getEntityName non-existant');
	}

	/**
	 * Method testgetContactName
	 * @test
	 */
	public function testgetContactName() {
		$this->assertEquals(getContactName(1084), 'Schwiebert Lina', 'getContactName contacts');
		$this->assertEquals(getContactName(1847), '&lt;Tindal&gt; &aacute;&ccedil;&egrave;&ntilde;t&ouml;s', 'getContactName contacts special characters');
		$this->assertEquals(getContactName(74), ' ', 'getContactName accounts');
		$this->assertEquals(getContactName(0), '', 'getContactName non-existant 0');
		$this->assertEquals(getContactName(''), '', 'getContactName non-existant empty string');
	}

	/**
	 * Method testgetEntityFieldNames
	 * @test
	 */
	public function testgetEntityFieldNames() {
		$expected = array(
			'tablename' => 'vtiger_account',
			'modulename' => 'Accounts',
			'fieldname' => 'accountname',
			'entityidfield' => 'accountid',
		);
		$this->assertEquals($expected, getEntityFieldNames('Accounts'), 'getEntityFieldNames accounts');
		$expected = array(
			'tablename' => 'vtiger_contactdetails',
			'modulename' => 'Contacts',
			'fieldname' => array('firstname', 'lastname'),
			'entityidfield' => 'contactid',
		);
		$this->assertEquals($expected, getEntityFieldNames('Contacts'), 'getEntityFieldNames contacts');
		$expected = array(
			'tablename' => 'vtiger_notes',
			'modulename' => 'Documents',
			'fieldname' => 'title',
			'entityidfield' => 'notesid',
		);
		$this->assertEquals($expected, getEntityFieldNames('Documents'), 'getEntityFieldNames documents');
		$expected = array(
			'tablename' => 'vtiger_troubletickets',
			'modulename' => 'HelpDesk',
			'fieldname' => 'title',
			'entityidfield' => 'ticketid',
		);
		$this->assertEquals($expected, getEntityFieldNames('HelpDesk'), 'getEntityFieldNames HelpDesk');
		$expected = array(
			'tablename' => 'vtiger_faq',
			'modulename' => 'Faq',
			'fieldname' => 'question',
			'entityidfield' => 'id',
		);
		$this->assertEquals($expected, getEntityFieldNames('Faq'), 'getEntityFieldNames Faq');
		$expected = array(
			'tablename' => '',
			'modulename' => '',
			'fieldname' => '',
			'entityidfield' => '',
		);
		$this->assertEquals($expected, getEntityFieldNames('Unknown'), 'getEntityFieldNames Unknown');
	}

	/**
	 * Method testgetEntityField
	 * @test
	 */
	public function testgetEntityField() {
		$expected = array(
			'tablename' => 'vtiger_account',
			'fieldname' => 'accountname',
			'entityid' => 'accountid',
		);
		$this->assertEquals(getEntityField('Accounts'), $expected, 'getEntityField accounts');
		$expected = array(
			'tablename' => 'vtiger_contactdetails',
			'fieldname' => "concat(firstname,' ',lastname)",
			'entityid' => 'contactid',
		);
		$this->assertEquals(getEntityField('Contacts'), $expected, 'getEntityField contacts');
		$expected = array(
			'tablename' => 'vtiger_notes',
			'fieldname' => 'title',
			'entityid' => 'notesid',
		);
		$this->assertEquals(getEntityField('Documents'), $expected, 'getEntityField non-existent');
		$expected = array(
			'tablename' => '',
			'fieldname' => '',
			'entityid' => '',
		);
		$this->assertEquals(getEntityField('non-existent'), $expected, 'getEntityField non-existent');
		$this->assertEquals(getEntityField(''), $expected, 'getEntityField empty');
		///////////
		$expected = array(
			'tablename' => 'vtiger_account',
			'fieldname' => 'vtiger_account.accountname',
			'entityid' => 'accountid',
		);
		$this->assertEquals(getEntityField('Accounts', true), $expected, 'getEntityField accounts');
		$expected = array(
			'tablename' => 'vtiger_contactdetails',
			'fieldname' => "concat(vtiger_contactdetails.firstname,' ',vtiger_contactdetails.lastname)",
			'entityid' => 'contactid',
		);
		$this->assertEquals(getEntityField('Contacts', true), $expected, 'getEntityField contacts');
		$expected = array(
			'tablename' => 'vtiger_notes',
			'fieldname' => 'vtiger_notes.title',
			'entityid' => 'notesid',
		);
		$this->assertEquals(getEntityField('Documents', true), $expected, 'getEntityField non-existent');
		$expected = array(
			'tablename' => '',
			'fieldname' => '.',
			'entityid' => '',
		);
		$this->assertEquals(getEntityField('non-existent', true), $expected, 'getEntityField non-existent');
	}

	/**
	 * Method testgetEmailTemplateVariables
	 * @test
	 */
	public function testgetEmailTemplateVariables() {
		$base[1] = array(
		  0 => array(
			0 => 'Current Date',
			1 => '$custom-currentdate$',
		  ),
		  1 => array(
			0 => 'Current Time',
			1 => '$custom-currenttime$',
		  ),
		  2 => array(
			0 => 'Image Field',
			1 => '${module}-{imagefield}_fullpath$',
		  ),
		);
		$expected = $base;
		$expected[0] = array(
			0 => array(
				0 => 'Organizations: OrganizationsID',
				1 => '$accounts-accountid$',
			),
			1 => array(
				0 => 'Organizations: Organization Name',
				1 => '$accounts-accountname$',
			),
			2 => array(
				0 => 'Organizations: Organization No',
				1 => '$accounts-account_no$',
			),
			3 => array(
				0 => 'Organizations: Phone',
				1 => '$accounts-phone$',
			),
			4 => array(
				0 => 'Organizations: Website',
				1 => '$accounts-website$',
			),
			5 => array(
				0 => 'Organizations: Fax',
				1 => '$accounts-fax$',
			),
			6 => array(
				0 => 'Organizations: Ticker Symbol',
				1 => '$accounts-tickersymbol$',
			),
			7 => array(
				0 => 'Organizations: Other Phone',
				1 => '$accounts-otherphone$',
			),
			8 => array(
				0 => 'Organizations: Member Of',
				1 => '$accounts-parentid$',
			),
			9 => array(
				0 => 'Organizations: Email',
				1 => '$accounts-email1$',
			),
			10 => array(
				0 => 'Organizations: Employees',
				1 => '$accounts-employees$',
			),
			11 => array(
				0 => 'Organizations: Other Email',
				1 => '$accounts-email2$',
			),
			12 => array(
				0 => 'Organizations: Ownership',
				1 => '$accounts-ownership$',
			),
			13 => array(
				0 => 'Organizations: Rating',
				1 => '$accounts-rating$',
			),
			14 => array(
				0 => 'Organizations: Industry',
				1 => '$accounts-industry$',
			),
			15 => array(
				0 => 'Organizations: SIC Code',
				1 => '$accounts-siccode$',
			),
			16 => array(
				0 => 'Organizations: Type',
				1 => '$accounts-account_type$',
			),
			17 => array(
				0 => 'Organizations: Annual Revenue',
				1 => '$accounts-annualrevenue$',
			),
			18 => array(
				0 => 'Organizations: Email Opt Out',
				1 => '$accounts-emailoptout$',
			),
			19 => array(
				0 => 'Organizations: Notify Owner',
				1 => '$accounts-notify_owner$',
			),
			20 => array(
				0 => 'Organizations: Assigned To',
				1 => '$accounts-smownerid$',
			),
			21 => array(
				0 => 'Organizations: Created Time',
				1 => '$accounts-createdtime$',
			),
			22 => array(
				0 => 'Organizations: Modified Time',
				1 => '$accounts-modifiedtime$',
			),
			23 => array(
				0 => 'Organizations: Last Modified By',
				1 => '$accounts-modifiedby$',
			),
			24 => array(
				0 => 'Organizations: Billing Address',
				1 => '$accounts-bill_street$',
			),
			25 => array(
				0 => 'Organizations: Shipping Address',
				1 => '$accounts-ship_street$',
			),
			26 => array(
				0 => 'Organizations: Billing City',
				1 => '$accounts-bill_city$',
			),
			27 => array(
				0 => 'Organizations: Shipping City',
				1 => '$accounts-ship_city$',
			),
			28 => array(
				0 => 'Organizations: Billing State',
				1 => '$accounts-bill_state$',
			),
			29 => array(
				0 => 'Organizations: Shipping State',
				1 => '$accounts-ship_state$',
			),
			30 => array(
				0 => 'Organizations: Billing Postal Code',
				1 => '$accounts-bill_code$',
			),
			31 => array(
				0 => 'Organizations: Shipping Postal Code',
				1 => '$accounts-ship_code$',
			),
			32 => array(
				0 => 'Organizations: Billing Country',
				1 => '$accounts-bill_country$',
			),
			33 => array(
				0 => 'Organizations: Shipping Country',
				1 => '$accounts-ship_country$',
			),
			34 => array(
				0 => 'Organizations: Billing PO Box',
				1 => '$accounts-bill_pobox$',
			),
			35 => array(
				0 => 'Organizations: Shipping PO Box',
				1 => '$accounts-ship_pobox$',
			),
			36 => array(
				0 => 'Organizations: Description',
				1 => '$accounts-description$',
			),
			37 => array(
				0 => 'Organizations: Text',
				1 => '$accounts-cf_718$',
			),
			38 => array(
				0 => 'Organizations: Number',
				1 => '$accounts-cf_719$',
			),
			39 => array(
				0 => 'Organizations: Percent',
				1 => '$accounts-cf_720$',
			),
			40 => array(
				0 => 'Organizations: Currency',
				1 => '$accounts-cf_721$',
			),
			41 => array(
				0 => 'Organizations: Date',
				1 => '$accounts-cf_722$',
			),
			42 => array(
				0 => 'Organizations: Emailcf',
				1 => '$accounts-cf_723$',
			),
			43 => array(
				0 => 'Organizations: Phonecf',
				1 => '$accounts-cf_724$',
			),
			44 => array(
				0 => 'Organizations: URL',
				1 => '$accounts-cf_725$',
			),
			45 => array(
				0 => 'Organizations: Checkbox',
				1 => '$accounts-cf_726$',
			),
			46 => array(
				0 => 'Organizations: skypecf',
				1 => '$accounts-cf_727$',
			),
			47 => array(
				0 => 'Organizations: Time',
				1 => '$accounts-cf_728$',
			),
			48 => array(
				0 => 'Organizations: PLMain',
				1 => '$accounts-cf_729$',
			),
			49 => array(
				0 => 'Organizations: PLDep1',
				1 => '$accounts-cf_730$',
			),
			50 => array(
				0 => 'Organizations: PLDep2',
				1 => '$accounts-cf_731$',
			),
			51 => array(
				0 => 'Organizations: Planets',
				1 => '$accounts-cf_732$',
			),
			52 => array(
				0 => 'Organizations: Is Converted From Lead',
				1 => '$accounts-isconvertedfromlead$',
			),
			53 => array(
				0 => 'Organizations: Converted From Lead',
				1 => '$accounts-convertedfromlead$',
			),
			54 => array(
				0 => 'Organizations: Created By',
				1 => '$accounts-smcreatorid$',
			),
		);
		$this->assertEquals($expected, getEmailTemplateVariables(array('Accounts')), 'getEmailTemplateVariables accounts');
		$expected = $base;
		$expected[0] = array(
			0 => array(
				0 => 'Contacts: ContactsID',
				1 => '$contacts-contactid$',
			),
			1 => array(
				0 => 'Contacts: Salutation',
				1 => '$contacts-salutation$',
			),
			2 => array(
				0 => 'Contacts: First Name',
				1 => '$contacts-firstname$',
			),
			3 => array(
				0 => 'Contacts: Contact No.',
				1 => '$contacts-contact_no$',
			),
			4 => array(
				0 => 'Contacts: Office Phone',
				1 => '$contacts-phone$',
			),
			5 => array(
				0 => 'Contacts: Last Name',
				1 => '$contacts-lastname$',
			),
			6 => array(
				0 => 'Contacts: Mobile',
				1 => '$contacts-mobile$',
			),
			7 => array(
				0 => 'Contacts: Organization Name',
				1 => '$contacts-accountid$',
			),
			8 => array(
				0 => 'Contacts: Home Phone',
				1 => '$contacts-homephone$',
			),
			9 => array(
				0 => 'Contacts: Lead Source',
				1 => '$contacts-leadsource$',
			),
			10 => array(
				0 => 'Contacts: Other Phone',
				1 => '$contacts-otherphone$',
			),
			11 => array(
				0 => 'Contacts: Title',
				1 => '$contacts-title$',
			),
			12 => array(
				0 => 'Contacts: Fax',
				1 => '$contacts-fax$',
			),
			13 => array(
				0 => 'Contacts: Department',
				1 => '$contacts-department$',
			),
			14 => array(
				0 => 'Contacts: Birthdate',
				1 => '$contacts-birthday$',
			),
			15 => array(
				0 => 'Contacts: Email',
				1 => '$contacts-email$',
			),
			16 => array(
				0 => 'Contacts: Reports To',
				1 => '$contacts-reportsto$',
			),
			17 => array(
				0 => 'Contacts: Assistant',
				1 => '$contacts-assistant$',
			),
			18 => array(
				0 => 'Contacts: Secondary Email',
				1 => '$contacts-secondaryemail$',
			),
			19 => array(
				0 => 'Contacts: Assistant Phone',
				1 => '$contacts-assistantphone$',
			),
			20 => array(
				0 => 'Contacts: Do Not Call',
				1 => '$contacts-donotcall$',
			),
			21 => array(
				0 => 'Contacts: Email Opt Out',
				1 => '$contacts-emailoptout$',
			),
			22 => array(
				0 => 'Contacts: Assigned To',
				1 => '$contacts-smownerid$',
			),
			23 => array(
				0 => 'Contacts: Reference',
				1 => '$contacts-reference$',
			),
			24 => array(
				0 => 'Contacts: Notify Owner',
				1 => '$contacts-notify_owner$',
			),
			25 => array(
				0 => 'Contacts: Created Time',
				1 => '$contacts-createdtime$',
			),
			26 => array(
				0 => 'Contacts: Modified Time',
				1 => '$contacts-modifiedtime$',
			),
			27 => array(
				0 => 'Contacts: Last Modified By',
				1 => '$contacts-modifiedby$',
			),
			28 => array(
				0 => 'Contacts: Portal User',
				1 => '$contacts-portal$',
			),
			29 => array(
				0 => 'Contacts: Support Start Date',
				1 => '$contacts-support_start_date$',
			),
			30 => array(
				0 => 'Contacts: Support End Date',
				1 => '$contacts-support_end_date$',
			),
			31 => array(
				0 => 'Contacts: Mailing Street',
				1 => '$contacts-mailingstreet$',
			),
			32 => array(
				0 => 'Contacts: Other Street',
				1 => '$contacts-otherstreet$',
			),
			33 => array(
				0 => 'Contacts: Mailing City',
				1 => '$contacts-mailingcity$',
			),
			34 => array(
				0 => 'Contacts: Other City',
				1 => '$contacts-othercity$',
			),
			35 => array(
				0 => 'Contacts: Mailing State',
				1 => '$contacts-mailingstate$',
			),
			36 => array(
				0 => 'Contacts: Other State',
				1 => '$contacts-otherstate$',
			),
			37 => array(
				0 => 'Contacts: Mailing Postal Code',
				1 => '$contacts-mailingzip$',
			),
			38 => array(
				0 => 'Contacts: Other Postal Code',
				1 => '$contacts-otherzip$',
			),
			39 => array(
				0 => 'Contacts: Mailing Country',
				1 => '$contacts-mailingcountry$',
			),
			40 => array(
				0 => 'Contacts: Other Country',
				1 => '$contacts-othercountry$',
			),
			41 => array(
				0 => 'Contacts: Mailing PO Box',
				1 => '$contacts-mailingpobox$',
			),
			42 => array(
				0 => 'Contacts: Other PO Box',
				1 => '$contacts-otherpobox$',
			),
			43 => array(
				0 => 'Contacts: Contact Image',
				1 => '$contacts-imagename$',
			),
			44 => array(
				0 => 'Contacts: Description',
				1 => '$contacts-description$',
			),
			45 => array(
				0 => 'Contacts: Is Converted From Lead',
				1 => '$contacts-isconvertedfromlead$',
			),
			46 => array(
				0 => 'Contacts: Converted From Lead',
				1 => '$contacts-convertedfromlead$',
			),
			47 => array(
				0 => 'Contacts: Created By',
				1 => '$contacts-smcreatorid$',
			),
			48 => array(
				0 => 'Contacts: Template Language',
				1 => '$contacts-template_language$',
			),
			49 => array(
				0 => 'Contacts: Password Encoding',
				1 => '$contacts-portalpasswordtype$',
			),
			50 => array(
				0 => 'Contacts: Portal Login User',
				1 => '$contacts-portalloginuser$',
			),
		);
		$this->assertEquals($expected, getEmailTemplateVariables(array('Contacts')), 'getEmailTemplateVariables contacts');
		$expected = $base;
		$expected[0] = array(
			0 => array(
			  0 => 'Assets: AssetsID',
			  1 => '$assets-assetsid$',
			),
			1 => array(
			  0 => 'Assets: Asset No',
			  1 => '$assets-asset_no$',
			),
			2 => array(
			  0 => 'Assets: Product Name',
			  1 => '$assets-product$',
			),
			3 => array(
			  0 => 'Assets: Serial Number',
			  1 => '$assets-serialnumber$',
			),
			4 => array(
			  0 => 'Assets: Date Sold',
			  1 => '$assets-datesold$',
			),
			5 => array(
			  0 => 'Assets: Date in Service',
			  1 => '$assets-dateinservice$',
			),
			6 => array(
			  0 => 'Assets: Status',
			  1 => '$assets-assetstatus$',
			),
			7 => array(
			  0 => 'Assets: Tag Number',
			  1 => '$assets-tagnumber$',
			),
			8 => array(
			  0 => 'Assets: Invoice Name',
			  1 => '$assets-invoiceid$',
			),
			9 => array(
			  0 => 'Assets: Shipping Method',
			  1 => '$assets-shippingmethod$',
			),
			10 => array(
			  0 => 'Assets: Shipping Tracking Number',
			  1 => '$assets-shippingtrackingnumber$',
			),
			11 => array(
			  0 => 'Assets: Assigned To',
			  1 => '$assets-smownerid$',
			),
			12 => array(
			  0 => 'Assets: Asset Name',
			  1 => '$assets-assetname$',
			),
			13 => array(
			  0 => 'Assets: Customer Name',
			  1 => '$assets-account$',
			),
			14 => array(
			  0 => 'Assets: Created Time',
			  1 => '$assets-createdtime$',
			),
			15 => array(
			  0 => 'Assets: Modified Time',
			  1 => '$assets-modifiedtime$',
			),
			16 => array(
			  0 => 'Assets: Last Modified By',
			  1 => '$assets-modifiedby$',
			),
			17 => array(
			  0 => 'Assets: Notes',
			  1 => '$assets-description$',
			),
			18 => array(
			  0 => 'Assets: Created By',
			  1 => '$assets-smcreatorid$',
			),
		);
		$this->assertEquals($expected, getEmailTemplateVariables(array('Assets')), 'getEmailTemplateVariables assets');
	}

	/**
	 * Method getFieldidProvider
	 * params
	 */
	public function getFieldidProvider() {
		$accname = array(
			'tabid' => '6',
			'fieldid' => '1',
			'fieldname' => 'accountname',
			'fieldlabel' => 'Account Name',
			'columnname' => 'accountname',
			'tablename' => 'vtiger_account',
			'uitype' => '2',
			'typeofdata' => 'V~M',
			'presence' => '0',
			'defaultvalue' => '',
			'generatedtype' => '1',
		);
		$astname = array(
			'tabid' => '43',
			'fieldid' => '613',
			'fieldname' => 'assetname',
			'fieldlabel' => 'Asset Name',
			'columnname' => 'assetname',
			'tablename' => 'vtiger_assets',
			'uitype' => '1',
			'typeofdata' => 'V~M',
			'presence' => '0',
			'defaultvalue' => '',
			'generatedtype' => '1',
		);
		$hdname = array(
			'tabid' => '13',
			'fieldid' => '170',
			'fieldname' => 'ticket_title',
			'fieldlabel' => 'Title',
			'columnname' => 'title',
			'tablename' => 'vtiger_troubletickets',
			'uitype' => '21',
			'typeofdata' => 'V~M',
			'presence' => '0',
			'defaultvalue' => '',
			'generatedtype' => '1',
		);
		$pbxname = array(
			'tabid' => '36',
			'fieldid' => '526',
			'fieldname' => 'callto',
			'fieldlabel' => 'Call To',
			'columnname' => 'callto',
			'tablename' => 'vtiger_pbxmanager',
			'uitype' => '2',
			'typeofdata' => 'V~M',
			'presence' => '0',
			'defaultvalue' => '',
			'generatedtype' => '1',
		);
		$eooname = false;
		return array(
			array(6, 'accountname', 1, $accname),
			array(43, 'assetname', 613, $astname),
			array(13, 'ticket_title', 170, $hdname),
			array(36, 'callto', 526, $pbxname),
			array(76, 'eooname', false, $eooname),
		);
	}

	/**
	 * Method testgetFieldid
	 * @test
	 * @dataProvider getFieldidProvider
	 */
	public function testgetFieldid($tabid, $fieldname, $expected, $expectedc) {
		$this->assertEquals($expected, getFieldid($tabid, $fieldname), "getFieldid $tabid, $fieldname");
		$fieldinfo = VTCacheUtils::lookupFieldInfo($tabid, $fieldname);
		$this->assertEquals($expectedc, $fieldinfo, "lookupFieldInfo cache $tabid, $fieldname");
		if ($fieldname=='accountname') {
			global $adb;
			$adb->query("update vtiger_field set presence='1' where fieldid=1");
			VTCacheUtils::$_fieldinfo_cache = array();
			$fieldinfo = VTCacheUtils::lookupFieldInfo($tabid, $fieldname);
			$this->assertFalse(getFieldid(6, 'accountname', true), "getFieldid $tabid, $fieldname ONLYACTIVE");
			$adb->query("update vtiger_field set presence='0' where fieldid=1");
			VTCacheUtils::$_fieldinfo_cache = array();
			$fieldinfo = VTCacheUtils::lookupFieldInfo($tabid, $fieldname);
		}
	}

	/**
	 * Method getUItypeProvider
	 * params
	 */
	public function getUItypeProvider() {
		return array(
			array('Accounts', 'accountname', 2),
			array('Assets', 'assetname', 1),
			array('HelpDesk', 'title', 21),
			array('PBXManager', 'callto', 2),
			array('EtiquetasOO', 'eooname', false),
		);
	}

	/**
	 * Method testgetUItype
	 * @test
	 * @dataProvider getUItypeProvider
	 */
	public function testgetUItype($module, $columnname, $expected) {
		$this->assertEquals($expected, getUItype($module, $columnname), "getUItype $module, $columnname");
	}

	/**
	 * Method getUItypeByFieldNameProvider
	 * params
	 */
	public function getUItypeByFieldNameProvider() {
		return array(
			array('Accounts', 'accountname', 2),
			array('Assets', 'assetname', 1),
			array('HelpDesk', 'ticket_title', 21),
			array('PBXManager', 'callto', 2),
			array('EtiquetasOO', 'eooname', false),
		);
	}

	/**
	 * Method testgetUItypeByFieldName
	 * @test
	 * @dataProvider getUItypeByFieldNameProvider
	 */
	public function testgetUItypeByFieldName($module, $fieldname, $expected) {
		$this->assertEquals($expected, getUItypeByFieldName($module, $fieldname), "getUItypeByFieldName $module, $fieldname");
	}

	/**
	 * Method readableBytesProvider
	 * params
	 */
	public function readableBytesProvider() {
		return array(
			array(1, '', '1B'),
			array(1, 'B', '1B'),
			array(1024, 'KB', '1KB'),
			array(1048576.0, 'MB', '1MB'),
			array(1073741824.0, 'GB', '1GB'),
			array(1099511627776.0, 'TB', '1TB'),
			array(1.1258999068426e15, 'PB', '1PB'),
			array(1.1529215046068e18, 'EB', '1EB'),
			array(1.1805916207174e21, 'ZB', '1ZB'),
			array(1.2089258196146e24, 'YB', '1YB'),
			array(20, '', '20B'),
			array(20, 'B', '20B'),
			array(20480, 'KB', '20KB'),
			array(20971520, 'MB', '20MB'),
			array(21474836480, 'GB', '20GB'),
			array(21990232555520, 'TB', '20TB'),
			array(2.2517998136852e16, 'PB', '20PB'),
			array(2.3058430092137e19, 'EB', '20EB'),
			array(2.3611832414348e22, 'ZB', '20ZB'),
			array(2.4178516392293e25, 'YB', '20YB'),
			array(100, '', '100B'),
			array(100, 'B', '100B'),
			array(102400.0, 'KB', '100KB'),
			array(104857600.0, 'MB', '100MB'),
			array(107374182400.0, 'GB', '100GB'),
			array(1.099511627776e14, 'TB', '100TB'),
			array(1.1258999068426e17, 'PB', '100PB'),
			array(1.1529215046068e20, 'EB', '100EB'),
			array(1.1805916207174e23, 'ZB', '100ZB'),
			array(1.2089258196146e26, 'YB', '100YB'),
		);
	}

	/**
	 * Method testreadableBytes
	 * @test
	 * @dataProvider readableBytesProvider
	 */
	public function testreadableBytes($bytes, $format, $e) {
		$this->assertEquals($e, readableBytes($bytes, $format), "readableBytes $bytes , $format");
	}

	/**
	 * Method numberBytesProvider
	 * params
	 */
	public function numberBytesProvider() {
		return array(
			array('1', '1'),
			array('1b', '1'),
			array('1k', '1024'),
			array('1m', '1048576'),
			array('2m', '2097152'),
			array('8m', '8388608'),
			array('1g', '1073741824'),
			array('1t', '1099511627776'),
			array('1p', '1.1258999068426E+15'),
			array('1e', '1.1529215046068E+18'),
			array('1z', '1.1805916207174E+21'),
			array('1y', '1.2089258196146E+24'),
			array('20', '20'),
			array('20b', '20'),
			array('20k', '20480'),
			array('20m', '20971520'),
			array('20g', '21474836480'),
			array('20t', '21990232555520'),
			array('20p', '2.2517998136852E+16'),
			array('20e', '2.3058430092137E+19'),
			array('20z', '2.3611832414348E+22'),
			array('20y', '2.4178516392293E+25'),
			array('100', '100'),
			array('100b', '100'),
			array('100k', '102400'),
			array('100m', '104857600'),
			array('100g', '107374182400'),
			array('100t', '1.099511627776E+14'),
			array('100p', '1.1258999068426E+17'),
			array('100e', '1.1529215046068E+20'),
			array('100z', '1.1805916207174E+23'),
			array('100y', '1.2089258196146E+26'),
		);
	}

	/**
	 * Method testnumberBytes
	 * @test
	 * @dataProvider numberBytesProvider
	 */
	public function testnumberBytes($size, $e) {
		$this->assertEquals($e, (string)numberBytes($size), "numberBytes $size");
	}

	/**
	 * Method getSearchModulesCommonProvider
	 * params
	 */
	public function getSearchModulesCommonProvider() {
		$e1 = array(
			'Accounts' => 'Accounts',
			'Leads' => 'Leads',
			'Contacts' => 'Contacts',
			'Potentials' => 'Potentials',
			'Campaigns' => 'Campaigns',
			'HelpDesk' => 'HelpDesk',
			'Products' => 'Products',
			'Documents' => 'Documents',
			'Emails' => 'Emails',
			'Faq' => 'Faq',
			'Vendors' => 'Vendors',
			'PriceBooks' => 'PriceBooks',
			'Quotes' => 'Quotes',
			'PurchaseOrder' => 'PurchaseOrder',
			'SalesOrder' => 'SalesOrder',
			'Invoice' => 'Invoice',
			'ServiceContracts' => 'ServiceContracts',
			'Services' => 'Services',
			'CobroPago' => 'CobroPago',
			'Assets' => 'Assets',
			'ModComments' => 'ModComments',
			'ProjectMilestone' => 'ProjectMilestone',
			'ProjectTask' => 'ProjectTask',
			'Project' => 'Project',
			'InventoryDetails' => 'InventoryDetails',
			'cbTermConditions' => 'cbTermConditions',
			'cbCalendar' => 'cbCalendar',
			'cbSurvey' => 'cbSurvey',
			'cbSurveyQuestion' => 'cbSurveyQuestion',
			'cbSurveyDone' => 'cbSurveyDone',
			'cbSurveyAnswer' => 'cbSurveyAnswer',
			'cbCompany' => 'cbCompany',
			'cbQuestion' => 'cbQuestion',
			'ProductComponent' => 'ProductComponent',
			'Messages' => 'Messages',
			'cbPulse' => 'cbPulse',
			'MsgTemplate' => 'MsgTemplate',
			'cbCredentials' => 'cbCredentials',
		);
		return array(
			array(array(), $e1),
			array(array('Accounts'), array('Accounts' => 'Accounts')),
			array(array('Accounts','cbupdater','EtiquetasOO'), array('Accounts' => 'Accounts')),
		);
	}

	/**
	 * Method testgetSearchModulesCommon
	 * @test
	 * @dataProvider getSearchModulesCommonProvider
	 */
	public function testgetSearchModulesCommon($filter, $expected) {
		$this->assertEquals($expected, getSearchModulesCommon($filter));
	}

	/**
	 * Method suppressAllButFirstProvider
	 * params
	 */
	public function suppressAllButFirstProvider() {
		return array(
			array('', '', ''),
			array('st', 'first one will stay only', 'first one will ay only'),
			array('sta', 'first one will stay only stay', 'first one will stay only y'),
			array('sta', 'first one will stastay only stay', 'first one will stay only y'),
			array('s', 'first one will stastay only stay', 'first one will tatay only tay'),
			array('s', 'firsst one will stastay only stay', 'first one will tatay only tay'),
			array('%s', '%satstart', '%satstart'),
			array('%s', '%satstart%s', '%satstart'),
			array('%s', 'aa%satstart%s', 'aa%satstart'),
			array('%s', 'aa%satstart%saa', 'aa%satstartaa'),
			array('%s', 'aa%satstart%saa%sbb', 'aa%satstartaabb'),
		);
	}

	/**
	 * Method testsuppressAllButFirst
	 * @test
	 * @dataProvider suppressAllButFirstProvider
	 */
	public function testsuppressAllButFirst($occurence, $from, $expected) {
		$this->assertEquals($expected, suppressAllButFirst($occurence, $from));
	}

	/**
	 * Method testfetchCurrency
	 * @test
	 */
	public function testfetchCurrency() {
		global $adb;
		$this->assertEquals(1, fetchCurrency(1));
		$adb->query('update vtiger_users set currency_id=2 where id=1');
		$this->assertEquals(1, fetchCurrency(1), 'cached value does not see the previous update');
		$adb->query('update vtiger_users set currency_id=1 where id=1'); // leave it as it was
		$this->assertEquals(2, fetchCurrency(12));
	}

	/**
	 * Method testgetDenormalizedModules
	 * @test
	 */
	public function testgetDenormalizedModules() {
		$this->assertEquals(['vtiger_cbcredentials'], getDenormalizedModules());
		$this->assertEquals(['vtiger_cbcredentials'], getDenormalizedModules('cbCredentials'));
		$this->assertEquals([], getDenormalizedModules('DoesNotExist'));
		$this->assertEquals([], getDenormalizedModules('Accounts'));
	}

	/**
	 * Method getSqlForNameInDisplayFormatProvider
	 * params
	 */
	public function getSqlForNameInDisplayFormatProvider() {
		return array(
			array('', '', '', 'CONCAT()'),
			array(array('first_name'=>'vtiger_users.first_name', 'last_name'=>'vtiger_users.last_name'), 'Users', '', 'CONCAT()'), // because fields are not in entityname
			array(array('first_name'=>'vtiger_users.first_name', 'last_name'=>'vtiger_users.last_name'), 'Contacts', '', "CONCAT(,'',)"), // garbage in > garbage out
			array(array('firstname'=>'vtiger_users.first_name', 'lastname'=>'vtiger_users.last_name'), 'Contacts', '', "CONCAT(vtiger_users.first_name,'',vtiger_users.last_name)"), // this is WRONG!!
			array(array('firstname'=>'we can put', 'lastname'=>'anything we want here'), 'Contacts', '', "CONCAT(we can put,'',anything we want here)"), // this is WRONG!!
			array(array('firstname'=>'vtiger_contacts.firstname', 'lastname'=>'vtiger_contacts.lastname'), 'Contacts', '', "CONCAT(vtiger_contacts.firstname,'',vtiger_contacts.lastname)"),
			array(array('firstname'=>'vtiger_contacts.firstname', 'lastname'=>'vtiger_contacts.lastname'), 'Contacts', 'glue', "CONCAT(vtiger_contacts.firstname,'glue',vtiger_contacts.lastname)"),
			array(array('ename'=>'vtiger_users.ename'), 'Users', '', 'CONCAT(vtiger_users.ename)'),
			array(array('ename'=>'vtiger_users.ename'), 'Users', 'glue', 'CONCAT(vtiger_users.ename)'),
			array(array('ename'=>'again, anything we want'), 'Users', '', 'CONCAT(again, anything we want)'),
		);
	}

	/**
	 * Method testgetSqlForNameInDisplayFormat
	 * @test
	 * @dataProvider getSqlForNameInDisplayFormatProvider
	 */
	public function testgetSqlForNameInDisplayFormat($input, $module, $glue, $expected) {
		$this->assertEquals($expected, getSqlForNameInDisplayFormat($input, $module, $glue));
	}
}
