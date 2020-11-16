Search engine for Irish online retail.

Python3 & nodejs.

Python requirements:
 - lxml
 - requests

Init:
sqlite3 production.db < db/schema.sql

The python reads an article and populates a DB. That's actually not needed in this version, but will be in another.

search.php is where the main action takes place. It basically relies on pre-configured custom search engines for 99% of the work,
redirecting the user to the chosen search engine and with the term in question.
