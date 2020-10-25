=== Screets Live Chat X ===
Tags: chat, live chat, help desk, contact, support
Requires at least: 5.5.1
Tested up to: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Screets Live Chat is a powerful chat plugin for WordPress. It allows you to speak/chat directly with your visitors on your website.

== Description ==

A powerful tool for chatting with your visitors on your HTML, PHP or WordPress website.

= Credits =

* Icons: Ionicons - http://ionicons.com

== Installation ==

1. Upload the zip package directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

Legend:
    (+) new feature/improvement,  (*) functionality changes, (!) bugfix

    Version 2.8.8 - 22 September 2020
        *   Compatible with PHP 7.4
        *   Tested with WordPress 5.5

    Version 2.8.7 - 28 March 2020
        *   Tested with WordPress 5.4
        *   Update "update-checker" library into the latest stable version
        !   Fixed a warning in update-checker library
        
    Version 2.8.6 - 4 November 2019
        +   Added Taiwan Chinese language support (thanks to Myron Pai)
        +   Supports WordPress 5.3 and newer versions
        +   Compatible with PHP 7.3
        !   "Hide on mobile devices" option now uses wp_is_mobile function for better compatibility
    
    Version 2.8.5 - 16 Jul 2019
        +   Added "Hide looking at" in general options to help users who have high usage in real-time database
        !   Now LC uses even less real-time database sources
        
    Version 2.8.4 - 26 June 2019
        *   Now offline form notification emails can be replied to visitor (not site email)
        !   Chat widget was shown up on different IE versions. Now hidden from all
        !   Fixed paste issue on reply
        
    Version 2.8.3 - 2 January 2019
        !   Fixed undefined HTTP_USER_AGENT error
        !   Fixed conflict with other JWT libraries
        !   Fixed WPML translating for response times

    Version 2.8.2 - 30 July 2018
        +   Added 'default' option to "Always show up on homepage/blog-related pages" to prevent conflicts with "Hide on mobile devices" display option
        *   Downgrade to Firebase 4.13.0 (from 5.2.0) to be compatible with all WP installations
        !   Fixed saving chat options issue for other user roles like Editor, Author, etc...
        
    Version 2.8.1 - 24 July 2018
        *   Updated Firebase video for new Firebase UI
        *   Added security rules introduction in real-time database options
        
    Version 2.8.0 - 18 July 2018
        +   Added "collector card"
        +   Added "solved" and "not solved" feedback in chat console
        +   Added "show up for logged in WP users only" option
        +   Added "Arabian" translation (thanks to General Satam)
        *   Firebase is updated into 5.2.0 (from 4.8.2)
        *   Removed Christmas background theme
        !   Fixed appearance issue on vote buttons in chat widget
        !   Fixed issue on non-latin characters in chat console like Hebrew, Chinese...
        !   Fixed flash.svg 404 PHP warning
        !   Fixed missing operator name appearing on other chat notifications where other operators handle in chat console
        
    Version 2.7.2 - 16 May 2018
        +   Added acceptance checkbox into offline form for better GDPR compliance

    Version 2.7.1 - 14 May 2018
        +   Made compatible with Screets Docs: https://medium.com/screets
        +   Added "lcx_widget_assets" hook for adding additional styles and scripts for chat widget
        +   Added "send" button to reply box for mobile view
        *   Updated to Firebase 4.13.0
        *   Updated default.po file with some removed strings
        *   Chat widget height is better for desktop view
        -   Chat widget doesn't load on IE browsers
        
    Version 2.7.0 - 20 April 2018
        +   Added "Hide when all operators are offline" feature in general chat options
        +   Embed your custom fonts directly from chat options for your chat widget.
        +   Added US date format in chat options
        +   Added chat console URL in email notifications
        +   Added "I agree to the privacy policy" checkbox in offline form for EU GDPR compatibility
        +   Added "reset operators data" button in chat console. It is good to delete ex-operators
        *   Load last 100 chats to improve chat console performance. Archive chats to see other ones.
        *   Updated to Firebase 4.12.1
        !   Fixed js error appears on fron-end when current chat operator is not exists anymore
        !   Fixed two times saving options to update chat design 
        !   Fixed unwanted slashes in custom css
        !   Fixed the issue "except pages" in general options
        !   Fixed "Options" string translation issue. Now it can be translated
        !   Fixed Firefox bug on reply box
        !   Fixed WPML translation issue for unlogged visitors
        
    Version 2.6.1 - 22 March 2018
        +   Multilingual compatibility (WPML and Polylang plugins)
        +   Added "custom css" box in design options directly for chat widget.
        !   Fixed unexpected popup view in front-end
        !   Fixed offline form sending issue faced in some WP installations

    Version 2.6.0 - 15 March 2018
        +   Better UI for chat box and starter
        +   Working in iframe (improves your page load performance)
        +   Offline form
        +   Show online operators (when online) and recently active operators (when no operator is online)
        +   Now set both horizontal & vertical offsets separately
        +   Visitors can "end chat"
        +   Visitors can vote ended chat (solved / unsolved)
        +   New chat sounds for visitors
        +   Operators see visitors current page url without local domain. It is more clean now (i.e. "https://yourdomain.com/about" will be changed with "/about")
        *   Chats list performance is improved
        *   Go to conversations when a chat deleted automatically
        *   Updated phpscss into 0.7.4
        *   A chat will be unarchived if visitor sends new message
        !   Fixed unsupported URL in file_get_contents in some PHP servers
        !   Fixed saving options error in old PHP versions
        !   Fixed "chat console" link in top admin bar
        !   Fixed sending chat logs to wrong email while ending chat
        -   (removed) Pre-chat box
    
    Version 2.5.2 - 13 February 2018
        !   Fixed saving options in ancient PHP versions 5.4 and newer

    Version 2.5.1 - 31 January 2018
        *   Updated console.css file
        !   Fixed some installation conflicts on real-time database (i.e. resetting case number issue)
    
    Version 2.5.0 - 31 January 2018
        +   Archive chat
        +   Delete chat
        +   Re-join chat when its closed
        +   Online/offline buttons for operators
        +   Now you can set response times by online/offline status
        +   Shows recently active operators to visitors
        +   Edit basic visitor profile info (name, email, etc.)
        +   Convert plain URLs into clickable links in chat messages
        *   Improved compatibility with WP themes & plugins
        -   (Deprecated) Pre-chat height option (now it's calculated automatically).
        
    Version 2.4.0 [Major update] - 23 January 2018
        +   New UI design
        +   Better user authentication
        +   Multiple conversation
        +   Random visitor names
        +   Case numbers
        +   Email transcripts
        *   Updated Firebase into 4.8.2