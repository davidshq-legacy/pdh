# Foundational Concepts and Knowledge Relating to WP and WP Plugin Development

## Introduction
This document provides a high-level overview of some central concepts related to WordPress and WordPress plugin
development which are not primarily code-based.

## The Three Major Components of WordPress
- Core
- Themes
- Plugins

## Don't Touch the Core
You should never need to edit the WordPress core, instead use plugins and/or themes to modify WP's core functionality.

## WP's Built-in APIs
WP has a number of API's built into core, use these rather than writing code to perform the same task, they've been
specifically optimized for WP and are well-tested.
- Options API - For storing data the plugin uses in the built-in WP table.
- HTTP API - For working with HTTP, e.g., accessing third party APIs.
- Plugin API - For extending WP with plugins.

## The Three Major Methods of Organizing Plugin Code
1. A single plugin file that contains functions (e.g. pdh-plugin.php is the entirety of the plugin
  - Example: https://github.com/GaryJones/move-floating-social-bar-in-genesis/blob/master/move-floating-social-bar-in-genesis.php
2. A single plugin file that contains a class, instantiated object, and optionally functions
  - Example: https://github.com/norcross/wp-comment-notes/blob/master/wp-comment-notes.php
3. A main plugin file with one or more class files.(#002)
  - See below for this structure as outlined in the plugin developer handbook.
  - Example: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate

## How to Structure One's Plugin Code
The sample folder structure included in the dev handbook is:
 plugin-name
   plugin-name.php
   uninstall.php
   /languages
   /includes
   /admin
       /admin
           /js
           /css
           /images
       /public
           /js
           /css
           /images
 
## Boilerplate Code Options
There are several different boilerplate options available that can help you get started:(#003)
 - WPPB - Probably the most popular: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 - WP CLI Scaffold - Pretty bare bones option: https://developer.wordpress.org/cli/commands/scaffold/plugin/