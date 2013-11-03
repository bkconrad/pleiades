# Pleiades - The Bitfighter Level Database

*Find your place among the stars*

## Installing

### Dependencies

 * PHP, MySQL, Apache, all properly configured
 * Apache mod_rewrite enabled
 * `Override` enabled so that the root `.htaccess` takes effect
 * PHP GD module
 * Functional PhpBB3 instance
 * Git (for retrieving dependencies as submodules)
 * A database for Pleiades (you should probably call it `pleiades`)

### Development Dependencies

 * PHPUnit from phpunit.de
 * XDebug (available as `php-xdebug` in Debian)
 * A test database (you should probably call it `pleiades_test`)
 * The `compass` gem from rubygems.org

### Instructions

 1. Checkout the repo into a suitable directory (you **need** to actually check it out via `git`).
 2. `cd` in to the repo.
 3. `git submodule init` and then `git submodule update`.
 4. `cd app/Config` and `cp database.php.default database.php`.
 5. Edit database.php as needed with MySQL or other database info.
 6. If this is a production server, edit core.php and change `Configure::write('debug', 2);` to `Configure::write('debug', 0);`
 7. Load the schema into the DB: `mysql -uroot -proot pleiades < schema.sql`
 8. It should run now!

### Development instructions

 8. To run tests, visit `localhost/pleiades/test.php`. If any of them fail, expect trouble.
 9. To work on the stylesheets, run `compass watch sass` from the root directory, and edit `sass/src/pleiades.scss`
 10. Hackity hack, and send your pull requests to kaen on github :)
 11. If you have ssh access with a configured pubkey on bitfighter.org, simply running `./deploy.sh` will update the server from the github repo.

## Acknowledgements

My sincere thanks to Watusimoto and raptor on #bitfighter at irc.freenode.net
for writing a game that inspired me to create such a preposterous contraption,
and for their patience during this process.
