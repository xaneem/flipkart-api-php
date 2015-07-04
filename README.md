PHP Wrapper for Flipkart API
============================
A simple PHP wrapper for the official Flipkart API. Helps you integrate the API for any custom use.

Flipkart has a [Product Feeds API](http://www.flipkart.com/affiliate/apifaq) (still in beta). There isn't any official wrappers for PHP (yet?) - so I decided to go ahead and build one.

To see this in action, check out the [live demo](http://www.clusterdev.com/flipkart-api-demo/).

The API seems complicated to use, but I've managed to build a simple demo where you can select a category, and products in the category that are in stock are displayed in a table. 

New: Deal of the Day (DOTD) offers and Top Offers are also included in demo.php (Not available in the live demo yet)

For the code to work, you'll need to generate an access token through your [affiliate account](http://www.flipkart.com/affiliate/).

Note that it is recommended to save the useful data retrieved from the API to a database first, and then update this database at certain intervals. This way, you won't exceed the API limits, and your site will load faster.

Feel free to use it, fork it, and send pull requests for any improvements!

###Alternative
If you're trying to scrape the site instead of using the API, there's something else that I'm working on which you should take a look at: [Flipbot](https://github.com/xaneem/flipbot)

It is a simple tool that built in JavaScript that opens pages in a headless browser and reads the data. Note that it is still in development and might not fit your purpose.

###License
MIT License
