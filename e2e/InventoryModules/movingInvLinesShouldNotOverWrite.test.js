const puppeteer = require('puppeteer')
const getUrlFromConfigFile = require('../../utils.js').getUrlFromConfigFile

test('Moving inventorylines should not overwrite each other', async () => {
	jest.setTimeout(30000);
	const url = getUrlFromConfigFile();
	if (!!url) {
		// Uncomment this section to actually see the test happening
		const browser = await puppeteer.launch({
			// headless: false,
			// slowMo: 80,
			// args: ['--window-size=1920,1080'],
			defaultViewport: null
		})

		// Comment this line when you want to see what's happening
		// const browser = await puppeteer.launch({headless: true})
		const page = await browser.newPage()
		await page.goto(url)
		await page.type('input#username', 'admin')
		await page.type('input#password', 'admin')
		await page.click('input#Login')
		await page.goto(
			url + '/index.php?module=SalesOrder&action=DetailView&record=10720'
		)
		await page.click('input[name=Edit]')
		await page.waitForSelector('#row4');
		await page.click('tr#row2 > td > img')
		await page.click('tr#row3 > td > img')
		await page.click('tr#row1 > td > a')
		const firstRowServiceName = await page.$eval('#productName1', el => el.value)
		browser.close()
		expect(firstRowServiceName).toBe('Manufacturing Japan Movt Stainless Steel Back For Men Bracelet Brand Watch Wrist Wtach Quartz')
	} else {
		throw 'URL could not be determined from config file'
	}
})