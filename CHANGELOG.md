# Changelog

## 2.2.14 Keith Dunwoody

- Add group for Vancouver section life members.

## 2.2.13 Francois Bessette

- Add options to control delete of expired members from the database.

## 2.2.12 Francois Bessette

- Add an option to sync a specified list of comma-separated memberships.

## 2.2.11 Keith Dunwoody

- Add 2024/2025 Student Outdoor Club group number

## 2.2.10 Terence Goldberg

- Update: Adding prettier commit hooks to improve code formatting.

## 2.2.9 Terence Goldberg

- Fix: Fixing the display of custom membership validation errors during login so that the error is displayed correctly using Ultimate Member forms.

## 2.2.4 Terence Goldberg

- Including additional sections.

## 2.2.2 Francois Bessette

- Enhance logic to prioritize parent memberships over child memberships,
  while allowing a membership to be shortened.

## 2.2.1 Francois Bessette

- Fix bug found by Jacques where a received child data would overwrite its
  parent. This was caused by the user_login (typically ACC membership number)
  being changed but not really taking effect because of wordpress DB caching.

## 2.2.0 Francois Bessette

- Save membership status in user database. The primary condition for a
  valid user is now based on his membership status rather than the expiry date.
- Do not allow user to login if his membership is in PROC state.
  Give appropriate login error message.
- Display membership status in user profile page.
- During local DB check, give warnings for users with PROC membership status.

## 2.1.4 Francois Bessette

- Add option to doublecheck for expired users in local DB.

## 2.1.3 Francois Bessette

- Merged 2.0.7 change from Keith

## 2.1.2 Francois Bessette

- Remove "Usernames will transition from ContactID" configuration and associated
  code. This was just for the 2023-05 IT transition, no longer needed.
- Add configuration and code to better decide what to do to role when there is
  a new or expired user. Choices: na, add_role, set_role, remove_role.
- Remove code which to restore a user previous role when he renews. Too confusing.
- Print received membership status to log
- Remove Process_Expiry code which used to be run at the end of membership import.
  There is no more need to do so since 2mev now signals reliably when members
  become expired.
- Enhance code for sending email to admin, for logging
- During activation phase, if the previous plugin was not 2.1.0, examine
  the user DB and clean-up the previous_roles variable.
- Add setting to limit the number of log files
- Fix PHP error when no email address entered in the Admin to notify option box.

## 2.0.7 Keith Dunwoody

- Add sanity check for member being a member of the section being imported. The API has recently
  been returning Prince George member numbers for the Vancouver section.
- Add mapping for student memberships for Vancouver section

## 2.0.6 Francois Bessette

- Fix minor problem where member expiry is not performed when plugin is manually
  triggered and there are no changes coming from the national web site.

## 2.0.5 Francois Bessette

- Fix memory hog causing crash during process_expiry for Montreal section

## 2.0.4 Claude Vessaz

- Remove numerical requirement for member numbers
- Refuse to process any member if the member number matches, but neither email or display name match.
- Sanitize user name before updating it in the database.

## 2.0.3 Claude Vessaz

- Remove update of user_nicename field during ID migration. The nicename is used for static URLs and changing it breaks all kind of stuff.

## 2.0.2 Francois Bessette

- Sleep only 4s since 2M reduced their server throttling to 20 requests per minute

## 2.0.1 Francois Bessette

- Optimized sleeps used to avoid HTTP too many requests errors

## 2.0.0 Francois Bessette

- Adapted code to the new Interpedia-based ACC IT platform

## 1.4.2 Francois Bessette

- Fix bug where a lapsed user would still be able to login.

## 1.4.1 Francois Bessette

- Revert 2 small changes made by Claude in 1.4.0: Keep Firstname, Lastname as being the default login when creating a new account, and skip received user record if expiry seems wrong.

## 1.4.0 Claude Vessaz

- Remove option to change WP login ID.
- Change default ID to ContactId.
- Improve expired membership error message.
- Use local expiry date if it is later than ACC national provided date.
- Generate secure password for new users.
- Fix possible issue with setting initial acc_status
- Delay initial cron job run by 1h.
- Delete old email templates.

## 1.3.1 Francois Bessette

- Make imis_id optional
- users with no expiry date are now considered active

## 1.3.0 Francois Bessette

- Add options to change role when member becomes expired, and restore previous role when member renews.

## 1.2.6 Francois Bessette

- Add an option to specify the title for the notification email.

## 1.2.5 Francois Besssette

- Ignore error if data received from ACC server is missing Membership field. Temp fix for Mtl, never pushed to Github.

## 1.2.4 Francois Bessette

- The user can now configure a list of emails who will be notified whenever the web site membership changes (user created/renewed, or becames expired).
- Leave the field blank if you want no email notifications.

## 1.2.3 Francois Bessette

- Fix minor review comments

## 1.2.2 Francois Bessette

- add a new processing loop to review membership expiry date and send welcome and goodbye emails. This is done after the import phase.
- add one more user meta variable called acc_status, with value either active or inactive. This is needed to detect transition and only send 1 email.
- clean a few logs
- translate email settings to english. French can come later.

## 1.2.1 Francois Bessette

- Fix bug when default role was set to organisateur-trice.

## 1.2.0 Francois Bessette and Claude Vessaz, 2020-12-23

- Fix major bug where only the first 100 users would be imported when triggered by timer
- added setting to control mapping of user_login (username).
- added setting to control whether the user_login is updated for users already in DB.
- simplified the settings page, move CRON settings on same page
- fixed bug where the log 'Delete' button was not working
- removed obsolete settings related to changing the role during update
