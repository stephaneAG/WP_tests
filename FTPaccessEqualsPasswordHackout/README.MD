##### 1st method, using functions.php

- we start by altering the 'functions.php' of the theme currently active:
  /wp-content/themes/<themename>

- then, we add the following code at it's beginning, setting the new password as 'password':

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

- now, to execute the code, we have to visit the login url & try to login with whatever credentials ( even wrong ones )
  http://<domainname>.com/wp-login.php or /wp-admin.php or /wp-login or /wp-admin 

- after that, we have to comment out the line we added in the 'functions.php' file

- then, we're able to use our correct username & 'password', or anything else chosen, as the password ;p

##### 2nd method, using phpadmin

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

##### 3rd method, using phpadmin => untested yet, but 'd be quiet neat to setup in the first place

- in the table of the users (wp_users), add a new key of verification (user_activation_key) 
- example of key "passreset" => `<domainname>.com/wp-login.php?action=rp&key=passreset&login=<username>`



Don't remember how to use Markdown on github ?
=> https://guides.github.com/features/mastering-markdown/
