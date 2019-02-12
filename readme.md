# Sample API
This sample API was built almost entirely from code written by Mike Dalisay at Code of a Ninja. 99.999% of the code is his. You can find the original here: https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html

## Enhancements
### Completed
- Updated Delete object to check for existence of requested product before executing DELETE statement
- Updated Update object to check for existence of requested product before executing UPDATE statement

### Planned
- Update object: currently returns a success if the product exists and the query is executed, even if zero (0) rows are modified. Planned enhancement is to correct this behavior.
- Move the database name, user name, and password to a config file
- Move the home page URL to a config file

## Getting Started
1. Copy the SQL statements from **create_database.sql** and execute them against your database server.
2. Create a database user with a strong password.
3. Grant your database user CREATE, READ, UPDATE, and DELETE (CRUD) rights to your database.
4. Update lines 4, 5, and 6 in **database_sample.php**, replacing them with your database name, user name, and password.
5. Rename **database_sample.php** to **database.php**
6. Update line 7 in **core_sample.php**, replacing the URL with your site's URL. Leave "/api/" in the URL string.
7. Rename **core_sample.php** to **core.php**.
8. Copy all files to your site via FTP, execpt for the /sql/ folder.

Your file structure on your site should look like this:
- api/
	- config/
		- core.php
		- database.php
	- objects/
		- product.php
		- category.php
	- product/
		- create.php
		- delete.php
		- read.php
		- read_paging.php
		- read_one.php
		- update.php
		- search.php
	- category/
		- read.php
	- shared/
		- utilities.php