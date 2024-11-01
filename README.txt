=== Vantevo Analytics ===
Contributors: Vantevo
Tags: Vantevo, Analytics, GDRP, Alternative Google Analytics, Statistics, CCPA, PECR
Requires at least: 4.5
Tested up to: 6.1
Stable tag: 1.1.5
License: GPLv2 or later

Is the alternative platform to Google Analytics respectful of privacy

== Description ==

**The analytics of your website, but simpler**

**Vantevo Analytics** is the alternative platform to Google Analytics respectful of privacy, because it does not need cookies not compliant with GDPR, CCPA and PECR. Easy to use, light and can be integrated into any website and backend.

For more information visit the website : [https://vantevo.io](https://vantevo.io)


=== Settings ===

= 404 - Page Not Found =
 
This function will automatically send a 404 type event in which it will save the page url. With this function you will be able to keep track of all 404 pages in a very easy way.
 
= Outbound links =
 
With this feature you can monitor all outbound links from your site. For example, whenever a user clicks on an outbound link, the Vantevo script will send an event of type `Outbound Link` where it will save the `url`.
 
 = Monitoring the files to download =
 
It allows you to automatically monitor all the files to download from your website. This consists of a comma-separated list of extensions, such as: zip, mp4, avi, mp3. Each time a user clicks on a link, the script checks if the file extension is present in the list entered in the parameter and sends a file download event with the url value.

The collected information is saved on the Events page with the event name File Download.

= Manual Pageview =
 
If this function is enabled, the Vantevo script will not automatically send the site analytics but you will need to add the code on each page to send the analytics.
The manual pageview function also allows you to specify custom locations for editing URLs with identifiers.
 
= Domain =
 
This parameter allows you to install the script on a different site than the site saved on Vantevo Analytics. All requests will be saved on the domain entered in your dashboard, it could also be used to merge the statistics of 2 / more sites. This setting is useful for managing subdomains.

= Development Mode =
 
You can use this parameter if you are in development mode. If you are in the development mode in localhost, without this tag, you will see the message “Ignore hits on localhost” in the browser console. Instead, if you add the tag dev, the script simulates a send and it writes down the data of the request in the browser console.

= Hash Mode =
 
This parameter allows monitoring based on URL’s hash changes.

= Scroll Tracking =

Send an event whenever a user performs page scrolling for a specific location.The name of the event saved on Vantevo will be Scroll Tracking.

= Source script =

Instead of using our cdn to download the vantevo script , you can put the link to your cnd or you could download the script locally on your server/hosting.

= Excludes one or more pages from the statistics =
 
Through this function you can exclude a page, a category or a group from the statistics, just enter the path of the page you want to exclude.


=== Ecommerce monitoring ===

Data collection and analysis are critical for an e-commerce site to optimize navigation and purchasing processes to ensure an optimal user experience. 

In the ecommerce section of Vantevo you can monitor specific actions affecting your ecommerce website and the sources of traffic that lead to sales. This can help you optimize your website and marketing campaigns to increase your profitability.

With our advanced ecommerce system, you can keep track of all the most important events:

- Which marketing channels and traffic sources are the most effective in targeting users who make purchases
- What is the share of mobile traffic compared to desktop traffic coming to the site
- What is the average duration of sales
- Managing abandoned products in the shopping cart
- Brand management
- Product variant management: colors , sizes or other
- Custom event management
- Coupon management
- Wishlist management
- Management of payment types

...and many other events

To work ecommerce monitoring you need to have the Woocommerce plugin installed and activated.

== Installation ==

1. Upload the folder `vantevo-analytics` to the directory `/wp-content/plugins/`
2. Activate the Vantevo Analytics plugin via WordPress "Plugin" menu
3. Configure the plug-in by going to the tab `Settings -> Vantevo Analytics` that appears in the admin menu.

== Changelog ==

= 1.1.5 =
* fix bug - scroll tracking
* Various fixes to improve performance

= 1.1.4 =
* Add ecommerce monitoring
* Add source script
* Add scroll tracking
* Various fixes to improve performance

= 1.1.3 =
* fix bug excludes pages
* Various fixes to improve performance

= 1.1.2 =
* Various fixes to improve performance

= 1.1.1 =
* Various fixes to improve performance

= 1.1.0 =
* Default language - English
* Add multi language - Italian
* Add new param - Development mode
* Add new param - Hash mode
* Add new param - Domain
* Add new param - Monitoring the files to download
* Various fixes to improve performance

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.1.4 =
* Add ecommerce monitoring
* Add source script
* Add scroll tracking
* Various fixes to improve performance

= 1.1.3 =
* fix bug excludes pages
* Various fixes to improve performance

= 1.1.2 =
* Various fixes to improve performance

= 1.1.1 =
* Various fixes to improve performance

= 1.1.0 =
* Default language - English
* Add multi language - Italian
* Add new param - Development mode
* Add new param - Hash mode
* Add new param - Domain
* Add new param - Monitoring the files to download
* Various fixes to improve performance

= 1.0 =
* Initial release