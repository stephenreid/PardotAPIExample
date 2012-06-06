PARDOT API EXAMPLE
==================
This Repo has some sample PHP scripts of using the Pardot API.

PardotConnector.class.php does all of the talking with the Pardot Api, including authentication
and basic prospect actions.

Prospect.class.php is a container for Prospects that sets all prospect data through a data array.

If you are not thinking object oriented (you should be, PHP 5 has been here for a while) then 
the examples in ProceduralEx.php might help you.

About Pardot
------------
Pardot is a SAAS Marketing Automation Platform.
We help you find the buyers interested in actually buying your products.
We help shorten sales cycle by helping your marketing department generate useful content
and then guage prospect engaugement through the accessing of your content incuding
Emails (Targeted Emailing, 1 to 1), Forms, Landing Pages, Social Posts, White Papers, Web page views


## When To Use the API
Our API is mostly designed as a way to interface with data gathering or CRM style products.
We are also seeing many uses and possibilities for use in CMS's such as custom page scoring,
custom campaign management, lister insertion, and more.

Also, check out our crm custom iframes if you are building a crm integration
http://www.pardot.com/help/faqs/crm/integration-options-for-other-crms

## When to NOT Use the API
We do have occassional requests and questions when clients are trying to do server side requests
based on a client side request to their website. Using the API for this is often not optimal for a few reasons
 1) No Visitor Tracking
    Because the client is cookied with a different IP address from your server we can't link the data
 2) Many Background Requests
    We try to make most of our requests take as little time as possible, but to use the API on a web request,
    you'd have to login to the api, find the prospect you want to update, and save the prospect. This adds a few
    seconds at best.

### Instead 
Look at Form Handlers. These allow you to pass prospect data through a get or post request. It can be done
man in the middle style by changing your Form actions, or more generically with a hidden Iframe.
http://www.pardot.com/help/faqs/forms/form-handlers#Advanced+Topic+%E2%80%94+Posting+data+to+hidden+iframes

We also offer the opportunity to cookie prospects visits with the tracking code that we give clients for campaigns
check out http://www.pardot.com/help/faqs/emails/integrating-third-party-email-solution



Disclaimer
----------
I (stephenreid) make no guarantees as to the accuracy of these examples.
Most, if not all have never been tested and will require some debugging coding, etc on your part
 * I / Pardot make no guarantees as to the accuracy of this document. 
 * As you are free to use this, it also comes with no additional support.
 * If you do have questions for support, please email them the actual query (url + parameters)
 * that you are trying to make and why the results are not working for you
 * WE CAN NOT MAKE ESTIMATES ON BUILDING ON THIS CODE



