# ABOUT

TikTok Video & Music Downloader PHP Script. Non Watermarked videos downloads works with videos posted after July, 27th 2020 aswell.

Let your visitors browse the videos before they can download. Search via @username, #challenge, Music URL or Video URL. 

Options to be able to show ads on multiple places within the website. 

You will require valid subscription of API endpoint on RapidAPI https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9

The script is lightweight, no bloats and uses phpfastcache to cache and server data faster.

The UI is done using Bootstrap and you can update it to your suit.

Uncompiled SCSS and JS files are in `src` folder so you can update it as you want to.

# Installation
This don't need complex setup, everything is kept simple as possible. No database calls are engaged to keep away the bloats.
Assumign you have `composer` & `NodeJS & NPM` installed on your system
- `composer install` to install packages required.
- `npm install` to install packages required for GulpJS tasks
- `gulp build` to build for production if you changed anything or use what's provided on `build` folder.
- Copy files from `build` folder if you want it in remote server or simply do `gulp defualt` while on dev environment.
- head over to https://example.com/admin.php assuming https://example.com is your domain and the scirpt is installed on your root of your web document directory.
- Follow on-screen instructions to setup some basic settings.

# Forgotten password, migration or re-install
- remove `installed.php` file from `_config` direcotry
- head over to `https://example.com/admin.php` then follow on-screen instructions to reset your password. 

# Customization
I used boostrap to do the UI, have few bootswatch.com thingy going, but you can always update the it to your need if you know the secret little magic of web. If you require, I can do a custom version, contact me via my profile.

# API rate limit
API Rate limit depends on your subscription plan on RapidAPI https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9

# Will I or my server get banned?
Absolutely not. Your server is never exposed while making calls as we deliver the API results to you via RapidAPI and our servers in cloud. 