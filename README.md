# Online-Shopping-web-application
PHP scripting
In this project, I have used eBay Commerce Network API for Shopping. I have developed a trivial web application that allows customers to buy products.The eBay Commerce Network API ("ECN API") is a flexible way to access and recreate practically everything you see on Shopping.com. I have used the Search by keyword method and the Requesting category tree information -> Include all descendants in category tree method from the eBay Commerce Network Publisher API Use Cases.

The search form has a menu to select a category, a text window to specify search keywords, and a submit button. The menu contains all sub-categories of the category "computers". Each product contains a link productOffersURL to the shopping.com web page that gives a detailed description of the product and a list of best offers from various sellers. So each product has a range minPrice - maxPrice of the prices offered by these sellers. I have ignored the list of offers and assumed that when we buy this product we pay the minPrice.For each chosen item, I have stored the Id, the name, the minPrice, the first image, and the productOffersURL .
