##### 1st method, functions.php => didn't work for my purpose ( maybe I'd have to add it to wp-login.php instead ? .. )

```php
<?php

/*
*    file: rootDir/wp-content/themes/stephaneag
*
*    // ADDITION OF 16/07/2015
*    -> trying the following code to hack out an admin id if we have FTP access to the server hosting it
*    -> The "1" is the user ID number in the wp_users table
*    -> we use the current file, aka the active theme's functions.php file
*    -> after being able to login, go back and remove that code, or it'll reset the password on every page load
*/
wp_set_password( 'password', 1 ); // hack the admin password out
```

##### 2nd method, using phpadmin => worked flawlessly ;)

- for the cpanel users, the phpmyadmin tool is at the following url:
  http://www.<domainname>.com:2082/cpsess1007265171/frontend/x3/sql/PhpMyAdmin.html

- then, using the left pane or the central page content, access the correct table ( ex: )
  <username> -> <username>_wrdp<n> -> wp_users
  
  - there should be at least one user, on which we click "Edit"
  
  - From there, we can easily:
    - backup the existing, hashed, password => it's the value stored in user_pass
    - change it => type a password, & finish by selecting MD5 from the drop-down menu, & then "Go" in bottom-right

Ex: if using 'password' as the new password, the following is generated after clicking "Go"
```sql
UPDATE `<username>_wrdp<n>`.`wp_users` SET `user_pass` = MD5( 'password' ) WHERE `wp_users`.`ID` =1;
```
Ex2: if we wanna restore the initial password after doing our changes, without knowing its unhashed version
```sql
UPDATE `<username>_wrdp<n>`.`wp_users` SET `user_pass` = '5f4dcc3b5aa765d61d8327deb882cf99' WHERE `wp_users`.`ID` =1;
```
