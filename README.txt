Install Dependencies

After downloading the project, run the following command to install all necessary PHP dependencies using Composer:

bash
Copy code
composer install
Database Migration

Migrate the database using the provided .sql file. Ensure that your database server is running and configured properly. Import the .sql file into your database to set up the initial schema and data.

Run the server  php -S localhost:3000  -t public
Access the Admin Panel
Once the setup is complete, navigate to the admin URL to access the administration panel. Replace localhost:3000 with your local server's address if it's different:

http://localhost:3000/admin/
Admin Credentials

Log in to the admin panel using the following credentials:
Email: test@gmail.com
Password: test@123
User Password

For every new user created, the default password will be:
Password: test@123
Session Expiry

Please note that sessions will expire after 1 hour. Make sure to save your work periodically to avoid losing any unsaved changes.