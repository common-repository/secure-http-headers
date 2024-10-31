=== Secure HTTP Headers ===
Contributors: shasha310
Tags: headers, security, cookies, hardening
Requires at least: 5.3
Tested up to: 5.7
Stable tag: 1.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Secure HTTP headers - Essential, and easy.

== Description ==

Harden your web applications.

HTTP header fields are components of the header section of request and response messages. The headers define the operating parameters of an HTTP transaction.

Securing HTTP headers will improve the resilience of your web application against many common attacks including those that are on the OWASP top 10 list.

Securing headers can also improve your SEO rank and in addition to preventing websites from being marked as dangerous by browsers and antivirus applications. 

Protect sensitive user information and be compliant with privacy regulations. Defend users from stealing private data by protecting website cookies. Use the proper directive such as "secure", "httponly" and "samesite", all of those will be applied automatically by "Secure HTTP Headers" plugin. 

Secure HTTP Headers will automatically analyze any website and will build up secure headers directives, by the latest best practice.

In addition, Secure HTTP Headers offers fully configurable options, apply or skip any header directive as needed. 

Install and activate Secure HTTP Headers with full confidence, the deactivation of this plugin will return your website header directives to their original state.


== Main plugin functionality ==

1. HTTP Strict Transport Security - helps to protect websites against man-in-the-middle attacks and cookie hijacking

2. X-Frame-Options - helps to protect users against ClickJacking attacks

3. X-Content-Type-Options  - helps to prevent the browser from MIME-sniffing

4. Referrer-Policy - helps to control how much referrer information should be included with requests

5. Clear-Site-Data - helps to ensure that data is deleted from the browser if the user logs out

6. X-Download-Options - helps to control how IE 8 will handle downloaded HTML files

7. Access-Control-Allow-Origin - helps to ensure whether the response can be shared with requesting code from the given origin

8. Cross-Origin-Embedder-Policy - helps to prevent a document from loading any cross-origin resources that don't explicitly grant the document permission

9. Permissions-Policy - helps to allow and deny the use of browser features in its own frame, and in content within any iframe elements in the document

10. Cross-Origin-Opener-Policy - helps to protect websites against a set of cross-origin attacks dubbed XS-Leaks

11. Cross-Origin-Resource-Policy - helps to protect websites against speculative side-channel attacks, like Spectre, as well as Cross-Site Script Inclusion attacks

12. X-Permitted-Cross-Domain-Policies - helps to control how cross-domain requests from Flash and PDF documents are handled

13. Cookie Http-Only flag - helps to protect websites against Cross-Site Scripting, or XSS attacks

14. Cookie Secure flag - helps to ensure that cookie is sent over a secure connection

15. Cookie Samesite Lax flag - helps to protect websites against CSRF and XSSI attacks

16. Expect-CT - helps to prevent the use of misissued certificates for a website. Note: The Expect-CT will likely become obsolete in June 2021

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer
2. Activate the plugin
3. Choose a custom configuration or select recommended one.

== Frequently Asked Questions ==

= What will happen if I deactivate Secure HTTP Headers? =

Your initial configuration will restore with no change.

== What are the optional extras? ==

Magnisec is offering "Secure HTTP Headers enhanced"

A plugin that contains, in addition, an engine that watches and builds in any website changes a CSP - Content Security Policy that is best practice and recommended by all professional securities experts, that mitigate XSS -Cross site Scripting, one of the most common and destructive attacks. 

Price: 50$ /year for a domain.

More details and installation [here](https://magnisec.com)

== Changelog ==

= 1.0 =
* Initial Version.

== Screenshots ==

1. In the left sidebar menu, navigate to Settings > Secure HTTP Headers and choose your website's server
2. Click the button "Recommended configuration" or choose your custom configuration
3. If your server is NGINX, follow directions for securing your website
