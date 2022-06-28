=== Time Tracker ===
Contributors: germanpearls
Donate link: https://www.paypal.com/paypalme/germanpearls
Tags: time tracker, time management, project management, freelancer tools, billing, to-do, to do, to do list, list, task, cf7 extension, contact form 7
Requires at least: 5.3
Tested up to: 5.9.3
Requires PHP: 7.0
Stable tag: 2.2.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Short Description: Time Tracker enables you to manage your projects, to do lists, recurring tasks, and billable time. Track your clients, projects, tasks, time, and billing information on private pages of your own website.  Don't worry about data privacy as you'll maintain your data within your own WordPress database.

== Description ==
Time Tracker enables you to manage your projects, to do lists, recurring tasks, and billable time. Track your clients, projects, tasks, time, and billing information on private pages of your own website.  Don't worry about data privacy as you'll maintain your data within your own WordPress database.

**Please Note**: This plugin is a Contact Form 7 add-on and requires the Contact Form 7 plugin to be installed first. (Tested with Contact Form 7 up to version 5.5.6)

Time Tracker is a freelancer's time management tool. It keeps track of:

* Clients including contact information, separate bill-to information, and how the client found you
* Projects, which can have several related tasks
* Recurring weekly or monthly tasks, these will automatically add new tasks to your to do list
* To do list with open items, due dates, time budget, and task status
* Time worked including work notes and time billed

Time Tracker helps to:

* manage your open to do list, prioritizing items by due date
* track time spent on each task, as compared to budgeted time
* track which time has been billed
* monitor time billed vs time worked
* keep a log of work notes related to each task
* manage third party (or white label) work by organizing work by "bill to"
* monitor weekly and monthly time to compare against goals

== Installation ==
 
**Please Note: This plugin requires the Contact Form 7 to function properly.**

1. Install and activate the Contact Form 7 plugin, if it's not already installed
2. Install and activate the Time Tracker plugin
3. Go to the Time Tracker Options menu page and add your business specific information, then save all changes
4. To begin using your new task management system, open a browser and navigate to your website /time-tracker. (NOTE: You will need to be logged in as an admin for the time tracker pages to be accessible.)
 
== Frequently Asked Questions ==
 
= Where is my information stored? =
 
All of the information you enter on a Time Tracker screen is stored in your WordPress database with your website host.
 
= How can I backup my Time Tracker information? =
 
To manually backup your Time Tracker client, project, task, time, etc. data, go to the Time Tracker Tools page in your WordPress admin area and click the backup button.
This will create a backup of your information and place it on your server. (The file will be dated and located in your user's directory in a folder named 'tt_logs'.)
Note: If you use a plugin or service to backup your WordPress database regularly, this will also backup your Time Tracker information.

= Will this work if I perform white label services or bill to third parties? =
 
Yes, Time Tracker keeps track of time by both client (end user) and bill to company, helping you to bill appropriately.

= I perform work under different business names, would Time Tracker work for me? =
 
Yes, by using the bill to field for your different businesses, Time Tracker can help you track time for your different companies.

= Can I sort work into different categories? =
 
Yes, Time Tracker lets you define your own work categories in the options screen.

= Does this take a lot of work to set up? =
 
No, to set up Time Tracker simply install it and setup your options like work categories and bill to names. The activation process creates everything else necessary including screens, menus, work summary tables, etc.

= What if I make a mistake when I enter a task, can I correct it? =
 
Yes, the screens of Time Tracker display your information in easy to read tables where you can easily edit information. All items can also be deleted from the user interface.

= Can I use Time Tracker on a multi-site installation? =
 
Time Tracker hasn't yet been tested on a multi-site application.

= WordPress is installed in a subfolder / subdirectory, will Time Tracker still work? =
 
Recent updates have improved the capability of this plugin to work in a subfolder/subdirectory installation. We welcome you to test it in your
installation and provide detailed feedback if you find features that don't work so we can work to improve this capability.

== Screenshots ==
 
1. The homepage of Time Tracker with quicklinks to important pages. Note: All front facing pages are private by default.
2. Entering time for a given client and task, with ample room for work notes.
3. Open to do list ordered by target due date. Includes time worked and progress bars for tasks with time projections.
4. Easily view time that hasn't been invoiced yet. Time is sorted by billable party with quick links to each section.
5. Admin Section - Create your own work categories, client referral names, and billable parties.
6. Admin Options - Backup your time data or delete all your data at will.

 
== Changelog ==

= next =
* New Feature: New styling to show / hide different features
* New Feature: Display monthly summary on time entry page - for all data or filtered data
* New Feature: Homepage now displays summary by month history for all years
* Improvement: Retain filter criteria in form when filtering time entries
* Improvement: Misc styling improvements

= 2.2.3 =
* Fix: Resolve styling issue

= 2.2.2 =
* Improvement: Clean up front end styling

= 2.2.1 =
* Fix: Fix critical error in plugin update

= 2.2.0 =
* New Feature: Add ability to download pending time as a csv file
* New Feature: Added capability to delete clients, projects, recurring tasks, tasks, and time entries
* Improvement: Clean up formatting of forms to make them more compact
* Improvement: Cleaned up filter time log form to take up less space
* Improvement: Update script redirects to improve handling wordpress installed in subfolder
* Improvement: Work toward adding filter capability for each item type
* Improvement: Allow for page and form updates via plugin updates
* Fix: Broken home button in admin menu
* Fix: Php error on pagination null value
* Fix: Made time log filter by date more robust

= 2.1.0 = 
* Improvement: Added summary table to top of time log page

= 2.0.0 =
* Tested up to WordPress 5.8
* Improvement: Clarify required fields in forms
* Improvement: Add capability to add tool tips
* Improvement: Begin adding tool tips to guide users
* Improvement: Add capability to handle revisions
* Improvement: Begin adding page content through shortcodes to help with updates and revisions
* Fix: Home button in admin menu

= 1.5.0 =
* Improvement: Added default categories to help new users get started
* Improvement: Added alert notifications to help new users getting started -> Client needs to be added first, then task, before time can be added
* Improvement: Clean up coding, removed old coding
* Improvement: Updated to allow for WordPress installation on sub-directory
* Fix: Resolved 404 errors when WordPress not in root directory

= 1.4.0 =
* New Feature: 'All Tasks' and 'All Time Entries' are now paginated results
* New Feature: Added recurring task icon to task lists
* New Feature: Added progress bar to time worked cells
* Improvement: Improvements to responsiveness to make time tracking on-the-go easier
* Improvement: Clean up front end display of various dates
* Improvement: Sort client names alphabetically
* Fix: Resolved problem preventing recurring tasks from getting entered automatically
* Fix: Change to clean up front end display and data output

= 1.3.0 =
* Improvement: Continued styling improvements throughout
* Improvement: Cleaner way to create tables for new activations
* Fix: Resolved error displaying on user setting form

= 1.2.2 =
* Improvement: More consistent styling throughout
* Improvement: Improved method for verifying dependent plugin (CF7) is loaded
* Fix: Recurring tasks not respecting end date

= 1.2.1 =
* New Feature: Added page and table listing all recurring tasks and allowing user to edit some details
* Improvement: Clarified required fields in forms on front end
* Improvement: Updated table designs to enter some default values for fields in which null is not acceptable
* Improvement: Created display table class for coding ease and consistency
* Improvement: Improved method for verifying pages are private and alerting user if not
* Fix: Plugin option not initialized at activation
* Fix: Added missing due date field in new project form

= 1.1.1 =
* Fix: Correct error resulting from plugin option not added during activation

= 1.1.0 =
* Misc Bug Fixes
* Improvement: When recording time, after selecting client, the tasks will now appear in reverse chronological order, with newest tasks first
* Improvement: Recurring task icons in tables to identify recurring tasks (included new field in task table)
* New Feature: Filter time entries by project
* New Feature: Button in project table to view all time entries for projections
* New Feature: Button in client table to view all time entries for a client

= 1.0.0 =
* Plugin release
 
== Upgrade Notice ==
 
= 1.3.0 =
New update includes bug fixes and improved styling.

= 1.2.2 =
New update includes fixes and improved styling.

= 1.2.1 =
New update includes fixes, improved features, and new features.

= 1.1.1 =
Upgrade to correct errors.

= 1.1 =
New features to streamline searches, misc improvements, and small bug fixes.

= 1.0 =
This is the first publicly available version of the plugin.