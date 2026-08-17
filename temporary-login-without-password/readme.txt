=== Temporary Login Without Password ===
Contributors: storeapps, icegram, niravmehta, sandhyam, mansi-shah
Tags: temporary admin access, passwordless login, guest login, developer access, expiring login
Requires at least: 3.0.1
Tested up to: 7.0
Stable tag: 1.9.8
Requires PHP: 5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Give developers or support staff temporary admin access to your WordPress site — no username, no password, and it expires automatically.

== Description ==

Temporary Login Without Password lets you create a self-expiring login link and share it with a developer, support agent, or guest editor — instead of handing over your username and password.

The person just clicks the link and they're logged in. No account setup, no shared credentials, no password to remember or revoke later. You choose the role and the expiry, and access shuts off automatically when the time is up.

**How it helps**

Every time you share your real admin login with an outside developer, you're trusting them with permanent access — and you have to remember to change the password after. TLWP removes that risk entirely. Generate a link, set it to expire in an hour, a day, or a custom date, and you're done.

= Benefits =

* Create unlimited temporary logins
* Assign any WordPress role to a temporary account
* No username or password needed — login is just a click
* Set expiry by time (hour, day, week, month) or a custom date
* Redirect the user to a specific page after login
* Set a language for the temporary user
* See last login time and number of times accessed
* Track what each temporary user did with detailed activity logs

= Who this is for =

* **Site owners** who need to give a developer quick access without sharing real credentials
* **Agencies** onboarding client sites for support or maintenance
* **Bloggers** giving guest writers or editors short-term review access
* **Developers** who want a clean, auditable way to request site access from clients

= For Developers =

If you need admin access to a client's WordPress site to resolve an issue, send them this:

> Hi {%customer_name%},
>
> To allow me to investigate your site, please install the free plugin Temporary Login Without Password, and share the temporary admin link it generates. Once I have access, I'll check the site and try to resolve the issue.
>
> Note: Set the expiry to one month, and send me the generated link as a reply to this email.

= TLWP Pro =

Free covers the essentials. Pro adds:

* **Limit link usage** — cap how many times a login link can be used
* **Instant admin alerts** — get notified every time a temporary login is accessed
* **Full activity log** — see exactly what actions each temporary user performed

[Upgrade to TLWP Pro](https://www.icegram.com/?buy-now=445245&qty=1&coupon=tlwp-pro-20&with-cart=1)

= Our other plugins =

* [Icegram Express](https://wordpress.org/plugins/email-subscribers/) — newsletter plugin for leads, broadcasts, and automated post notifications
* [Icegram Mailer](https://wordpress.org/plugins/icegram-mailer/) — reliable email delivery for WordPress & WooCommerce
* [Icegram Engage](https://wordpress.org/plugins/icegram/) — popups, welcome bar, and opt-ins
* [Post / Page Duplicate](https://wordpress.org/plugins/duplicate-post-page-copy-clone-wp/) — one-click content duplicator
* [Switch User Login](https://wordpress.org/plugins/switch-user-login-by-icegram/) — switch between WordPress accounts from the admin bar

== Installation ==

**Option 1: Search and install**

1. Go to WordPress Dashboard → Plugins → Add New
2. Search for "Temporary Login Without Password"
3. Click Install Now, then Activate

**Option 2: Manual upload**

1. Download the plugin zip from wordpress.org
2. Go to Plugins → Add New → Upload Plugin
3. Upload the zip file and click Install Now
4. Activate the plugin

== Frequently Asked Questions ==

= Do I need a username and password to use a temporary login? =

No. TLWP generates a unique login link. The person you share it with clicks the link and is logged in automatically — there's no username or password involved at any point, so there's nothing for them to forget or for you to reset later.

= Can I disable a temporary user manually before their access expires? =

Yes. Go to Temporary Login in your dashboard and click Deactivate next to the user. This revokes access immediately, but keeps their activity log so you can review what they did. You can reactivate the same account later if needed.

= Can I delete a temporary user completely? =

Yes. Deleting a temporary user removes their login access and account. If you want a record of what they accessed before removing them, review the activity log first, since deleting the user also removes their history.

= Can a temporary user log in using a username and password instead of the link? =

No. Temporary accounts can only be accessed through the generated link. This is intentional — it's what keeps the access self-expiring and prevents the credentials from being reused or shared outside the link.

= Can I convert a temporary user into a permanent user? =

Not directly within the plugin at this time. If you need someone to have permanent access, create a regular WordPress user account for them separately and deactivate the temporary one.

= Can I set a different language for a temporary user? =

Yes. When creating the temporary login, you can set a preferred language for that account. This is useful when a support person or developer doesn't speak your site's default language — they'll see the admin dashboard in their own language.

= Can I set the login expiry in minutes instead of hours or days? =

Not from the settings screen directly, but you can set it in minutes using a WordPress filter. See the [community thread](https://wordpress.org/support/topic/additional-expiry-option/) for the exact filter and code snippet.

= If I give a temporary login administrator access, can they delete other users? =

No. Temporary users are restricted from deleting other users, including other admins, regardless of the role you assign them. This keeps a temporary admin account from being used to lock you out of your own site.

= How do I redirect a temporary user to a specific page after they log in? =

You can select a redirect page while creating the temporary login, or use the redirect_to query parameter in the link itself. See [this thread](https://wordpress.org/support/topic/redirect-user-after-login-2/) for examples.

= Can I use a fake or placeholder email address to create a temporary login? =

Yes, technically any email works. But we recommend using the real email address of the person you're granting access to — it makes it easier to track their activity and confirms the link reaches the right person.

= Does the plugin delete all its data when I uninstall it? =

Yes, but only if you choose to. There's a setting to delete all temporary login data on plugin removal. If you leave it unchecked, your data stays intact in case you reactivate the plugin later.

= Is there a limit to how many temporary logins I can create? =

No. You can create as many temporary logins as you need, with no cap on the free or pro version.

= Does this create a security risk on my site? =

No — it's designed to reduce risk compared to sharing your real login. Every temporary account expires automatically, can't reset a password, can't delete other users, and every action is logged. You stay in control of exactly what access is granted and for how long.

= Does TLWP work with WooCommerce sites? =

Yes. TLWP works on any WordPress site, including WooCommerce stores. It's commonly used to give store developers or support staff temporary access without sharing checkout, payment, or customer-facing credentials.

= Does it work on WordPress Multisite? =

Yes. On Multisite, a Super Admin can create a temporary Super Admin account, and access can be scoped the same way as on a single site.

== Screenshots ==

1. Create a new temporary login — set the role, expiry, and language in one screen.
2. See every active and expired login at a glance, with last-access time.
3. Configure default expiry, redirect, and cleanup behavior from settings.
4. Track exactly what a temporary user did with detailed activity logs.

== Upgrade Notice ==

= 1.9.8 =

* Update: Compatibility with WP 7.0

== Changelog ==

**1.9.8 [2026-08-17]**

* Update: Compatibility with WP 7.0

**1.9.7 [2025-12-22]**

* Fix: Multiple temporary logins were getting generated in some cases
* Update: Language translation file

**1.9.6 [2025-11-17]**

* Fix: Fixed PHP deprecation warning messages 

**1.9.5 [2025-09-11]**

* New: Added activity logs feature - import users by csv in bulk

**1.9.4 [2025-08-04]**

* Fix: "PHP Warning: Cannot modify header information - headers already sent" [PRO]

**1.9.3 [2025-06-26]**

* Fix: Small UI fix

**1.9.2 [2025-06-25]**

* New: Added activity logs feature - Track actions performed by temporary users

**1.9.1 [2025-04-24]**

* New: Create a temporary default user on first time activation
* Update: Tested up to WordPress 6.8

**1.9.0 [2025-02-25]**

* New: Initial release of Temporary Login Without Password pro plugin

**1.8.5 [2024-11-22]**

* Fix: PHP Notice: Function _load_textdomain_just_in_time was called incorrectly

**1.8.4 [2024-11-18]**

* Update: Compatibility test with WordPress 6.7

**1.8.3 [2023-12-06]**

* Fix: PHP Warning "Deprecated: trim(): Passing null to parameter #1 ($string) of type string is deprecated"
* Update: Compatibility test with WordPress 6.4.1

**1.8.2 [2023-05-19]**

* Fix: CSS conflict with some admin header menu classes
* Update: Compatibility test with WordPress 6.2.1

**1.8.1.1 [2022-11-19]**

* Update: Change in release version

**1.8.1 [2022-11-19]**

* Update: Minor improvements

**1.8.0 [2022-10-21]**

* Update: WordPress 6.0.3 compatibility check
* Update: Minor improvements
* Update: POT file
* Fix: PHP Fatal error: Uncaught Error: Call to undefined function get_current_screen() in some cases

**1.7.6 [2022-09-14]**

* Update: WordPress 6.0.2 compatibility check

**1.7.5 [2022-08-16]**

* Fix: Fatal error: Uncaught Error: Class 'IG_Tracker_V_1_2_5' not found

**1.7.4 [2022-08-08]**

* Update: WordPress 6.0.1 compatibility check

**1.7.3 [2022-07-05]**

* Update: WordPress 6.0.0 compatibility check
* Fix: Prevent extra css from loading on non plugin page

**1.7.2 [2022-02-15]**

* Update: WordPress 5.9 compatibility check

**1.7.1 [2021-11-11]**

* Fix: Added capability check in Settings

**1.7.0 [2021-10-21]**

* Fix: Improved security related to nonce in Settings (Thanks to WPScan team)

**1.6.15 [2021-09-03]**

* Update: Improved security - better protection against brute force attacks and loopholes in other plugins (thanks to Zack Katz for suggestions)

**1.6.14 [2021-07-27]**

* Fix: User creation failed with non latin First Name or Last Name (Thanks [@gmspb](https://profiles.wordpress.org/gmspb/) for [reporting](https://wordpress.org/support/topic/name-in-latin-only/))
* Update: WordPress 5.8 compatibility check

**1.6.13 [2021-05-21]**

* New: Added option delete temporary login plugin data on plugin delete
* Update: Create username with lowercase letters
* Update: Improve settings screen UI
* Update: .POT files
* Fix: Internationalization issues. (Thanks [@yordansoares](https://profiles.wordpress.org/yordansoares/) for [reporting](https://wordpress.org/support/topic/internationalization-issues/))
* Fix: Blank page appear after settings saved.

**1.6.12 [2021-05-10]**

* Update: Confirm before delete temporary user

**1.6.11 [2021-04-08]**

* Update: PHP 8 compatibility check
* Fix: Deprecated: Required parameter $data follows optional parameter `$user_id` in `temporary-login-without-password/includes/class-wp-temporary-login-without-password-common.php on line 130`

**1.6.10 [2021-03-19]**

* New: Set default redirect after login option
* Update: Improve UI
* Update: .POT file

**1.6.9 [2021-03-10]**

* New: Added feature to redirect temporary user to a specific page after login
* Update: WordPress 5.7 compatibility

**1.6.8 [2021-03-05]**

* Update: Improve UI

**1.6.7 [2020-12-30]**

* Update: WordPress 5.6 compatibility check

**1.6.6 [2020-10-08]**

* Fix: Datepicker issue.

**1.6.5 [2020-09-28]**

* Update: WordPress 5.5.1 compatibility check

**1.6.4 [2020-08-17]**

* Update: Compatibility check with WordPress 5.5
* Fix: Deactivation checkbox is disabled for Administrator

**1.6.3 [2020-06-29]**

* Fix: Upgrade failed to notice while upgrading plugins through temporary login
* Update: Removed all unnecessary admin notices from the Temporary Login screen

**1.6.2 [2020-05-30]**

* New: Notify about Temporary Access in the admin bar

**1.6.1 [2020-04-11]**

* Update: Compatibility test with WordPress 5.4

**1.6.0 [2020-02-20]**

* New: Now, able to create a temporary login link that will expire on a specific time after access.
* Fix: Conflict of Sweetalert JS

**1.5.23 [2020-01-14]**

* Fix: Incorrect temporary Login URL shows up while using iThemes Security plugin with Custom URL Login enabled

**1.5.22 [2019-12-23]**

* Update: Compatibility test with WordPress 5.3.2

**1.5.21 [2019-11-04]**

* Update: Make it compatible with iThemes Security (formerly Better WP Security) plugin with Custom URL Login enabled

**1.5.20 [2019-09-09]**

* Update: Add Temporary Super Admin to all sites
* Fix: JS & CSS loading issue for WordPress Multi sites
* New: Added Italian translation. (Thanks [@alessioangeloro](https://profiles.wordpress.org/alessioangeloro/) for providing Italian translation)

**1.5.19.1 [2019-08-19]**

* Fix: Redirection issue with WordPress subdirectory installation.

**1.5.19 [2019-08-16]**

* Fix: "404 Not Found" issue after clicking on a temporary login link for WordPress subdirectory installation.

**1.5.18 [2019-07-29]**

* Fix: Browser console error

**1.5.17 [2019-06-17]**

* New: added "locale" support for a temporary user
* New: Show number of times the temporary user logged in via temporary login link
* Fix: CSS issue with Divi theme (Thanks [@lordsnake](https://wordpress.org/support/users/lordsnake/) for reporting)

**1.5.16 [2019-05-20]**

* Update: Compatibility test with WordPress 5.2
* Fix: Typo in the success message.

**1.5.15.2 [2019-04-17]**

* Update: Release Note.

**1.5.15.1 [2019-04-17]**

* Update: Minor update.

**1.5.15 [2019-03-08]**

* Fix: Redirection after login gives "404 error" error on sub-site of WordPress multi-site installation. (Thanks [@csigncsign](https://wordpress.org/support/users/csigncsign/) for reporting this.)

**1.5.14 [2019-01-14]**

* Update: Added System info page and allow the temporary user to access the system info page

**1.5.13 [2018-11-19]**

* Update: Now, temporary user can be redirected to any page without using redirect_to query params.

**1.5.12 [2018-10-24]**

* Fix: Temporary user redirected to the admin page even if the 'redirect_to' query param present once a user logged in with a temporary login link.

**1.5.11 [2018-10-08]**

* Update: Added filter for expiry_option. Now, one can add any custom expiry_option for the temporary login. [See example](https://wordpress.org/support/topic/additional-expiry-option/)

**1.5.10 [2018-09-12]**

* Fix: 'redirect_to' parameter in request didn't use for user redirection. Now, a temporary user will be redirected to the URL available in the 'redirect_to' parameter.

**1.5.9 [2018-07-25]**

* Update: Added settings to set default expiration time. Now, admin don't have to select expiry time from dropdown whenever they
create a new temporary login.

**1.5.8 [2018-04-25]**

* Fix: Format temporary login link email for Apple Mail. (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))

**1.5.7 [2018-04-11]**

* Update: Added settings link on Plugins page
* Update: URL parameters sanitized as keys (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))
* Update: Paste temporary login link directly into email
* Fix: Lock and delete icon not clickable (Thanks to [@danielgm](https://wordpress.org/support/users/danielgm/))

**1.5.6 [2018-03-14]**

* Fix: Datepicker doesn't show up when editing temporary login and select "custom date" value from expiry time dropdown

**1.5.5 [2018-03-05]**

* Update: Set default expiry time as a "Week" for new temporary login instead of an "Hour".
* Fix: PHP Warning: in_array() expects parameter 2 to be array, string given in temporary-login-without-password/includes/class-wp-temporary-login-without-password-common.php
* Fix: Existing temporary user's role is not available into roles dropdown while editing.

**1.5.4 [2018-02-20]**

* Fix: Invalid argument supplied for foreach() PHP Warning in class-wp-temporary-login-without-password-deactivator.php

**1.5.3 [2018-02-06]**

* Update: Now, admin can change the role and expiry of temporary login

**1.5.2 [2018-01-29]**

* Update: Now, admin can select roles from which they want to create a Temporary Login.
* Fix: Temporary User with 'administrator' role shows as a 'Super Admin' for WordPress single-site installation.

**1.5.1 [2018-01-19]**

* Fix: Parse error: syntax error, unexpected â€˜[â€˜ (PHP < 5.4)

**1.5 [2018-01-08]**

* Update: Now, Temporary Login can be created for WordPress Multisite. Super Admin can create a temporary super admin for multi-site
* Update: Restrict Temporary user to delete other users.

**1.4.6 [2017-11-18]**

* Update: Now, admin can set the default role for temporary user from the settings panel

**1.4.5 [2017-11-13]**

* Update: Restrict temporary user to deactivate/delete Temporary Login Without Password plugin

**1.4.4 [2017-10-23]**

* Fix: Trying to load scripts from unauthorized sources error.

**1.4.3 [2017-08-04]**

* Fix: Localization issue

**1.4.2 [2017-06-28]**

* Fix: Uncaught Error: Call to undefined function wc_enqueue_js().

**1.4.1 [2017-06-23]**

* Update: Now, create a temporary login with a custom expiry date.

**1.4 [2016-09-07]**

* Added: Support for "Theme My Login" plugin. Now, a temporary user will be redirected to a page that is defined in the Theme My Login plugin.

**1.3 [2016-09-01]**

* Fix: Temporary users can log in with an email address. Now onwards, the temporary user is not able to login using username/email and password
* Fix: Temporary user was able to reset the password. Now onwards, they won't be able to reset the password.
* Update: Now, the role of a temporary user is a downgrade to "none" on deactivation of plugin and change to default on the reactivation of plugin

**1.2 [2016-09-01]**

* Fix: Temporary user can log in with username and password.

**1.1 [2016-08-05]**

* Fix: Temporary user redirected to login page instead of admin dashboard after successful login.

**1.0 [2016-08-04]**

* Initial Release