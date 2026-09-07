const puppeteer = require('puppeteer');

const url = process.argv[2] || 'https://www.google.com'; // Access the first command-line argument or provide a default value
const downloadPath = process.argv[3] || 'google.pdf';

(async () => {
  const browser = await puppeteer.launch({
    /* executablePath: '/usr/bin/google-chrome', */
    headless: "new",
    args: ['--no-sandbox']
  });
  const page = await browser.newPage();
  await page.goto(url);
  await page.pdf({ path: downloadPath, format: 'A4', printBackground: true });
  await browser.close();
})();