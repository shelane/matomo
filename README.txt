Module: Piwik - Web analytics
Author: Alexander Hass <www.hass.de>


Description
===========
Adds the Piwik tracking system to your website.

Requirements
============

* Piwik installation
* Piwik website account


Installation
============
* Copy the 'piwik' module directory in to your Drupal
sites/all/modules directory as usual.


Usage
=====
In the settings page enter your Piwik website ID.

You will also need to define what user roles should be tracked.
Simply tick the roles you would like to monitor.

You can also track the username and/or user ID who visits each page.
This data will be visible in Piwik as segmentation data.
If you enable the profile.module you can also add more detailed
information about each user to the segmentation tracking.

All pages will now have the required JavaScript added to the
HTML footer can confirm this by viewing the page source from
your browser.


Advanced Settings
=================
You can include additional JavaScript snippets in the advanced
textarea. These can be found on various blog posts, or on the
official Piwik pages. Support is not provided for any customisations
you include.

To speed up page loading you may also cache the piwik.js
file locally. You need to make sure the site file system is in public
download mode.
