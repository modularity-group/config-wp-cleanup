# config-wp-cleanup

Clean and optimize several details in WP-Dashboard and Frontend-Output.

---

Version: 1.5.2

Author: Matze @ https://modularity.group

License: MIT

---

**whitelabel**
- removes WP Logos
- shows useful admin-footer
- styles wp-login page width base variables
- adds author-branding in header-markup
- removes "Customize" from admin-bar

**admin**
- remove most dashboard widgets exept `right_now` for all roles and `site_health` for administrator role
- extend `right_now` to show content count from all post types
- show all admin nag notices only for admins and developers
- hide block editor field for custom classes
- minimized admin bar in frontend

**frontend**
- remove unnecessary meta-tags, scripts, embeds and settings from html-head

---

1.5.2 
- remove admion bar css in frontend - not needed for collapsed admion bar

1.5.1
- small fixes

1.5.0 
- merge wp-whitelabel, dashboard-clean, remove-html-classes and source-clean into wp-cleanup
- disable admin_nag messages for non-admins/developers

1.0.2
- Update new core style variables for login
- show "Site Health" Widget only for Administrator and Developer Role
- add custom post type counts to "Right Now" Widget 

1.0.1
- Replace WP-Logo in block-editor's upper left corner with back-arrow 
