#!/usr/bin/python3

# Parse the Irish Times 400 list, and create data suitable for using as an ersatz search-engine.
# Note: there is no possibility of an _actual_ aggregator/search engine unless or until a company
# or person/people put serious effort into standardizing search results, etc etc.

from lxml import html
import pprint
import re
import requests
import sqlite3

# We write the results to a local DB. Nothing fancy, doesn't have to scale.
conn = sqlite3.connect('production.db')
c = conn.cursor()

# This vomit is the regex which parses the Irish Times list. I presume a CMS is being used behind the scenes,
# because the tags surrounding the sites, text, etc, are all confused in a way which strongly suggests
# a combination of human editing and default decisions by tag-happy CMSes. Neededless to say, there isn't
# a semantic parsing avaialble.
link_text_regex="href=\"(.+?)\"><(?:strong|u)>(.+?)<\/(?:strong|\/u)>.?<\/a><br>(.*)<\/p>|href=\"(.+?)\"><u>(.+?)<\/u><\/a><\/strong>(?:.+?)<br>(.*)<\/p>|href=\"(.+?)\" target=(?:.+?)>(.+?)<\/a>(?:.+?)<br>(.+?)<\/p>|href=\"(.+?)\">(.+?)<\/a><\/strong><br>(.+?)<\/p>"
pattern=re.compile(link_text_regex)

# Go to the IT. Currently this article is not paywalled, as best I can tell.
page = requests.get('https://www.irishtimes.com/news/consumer/400-yes-400-irish-retailers-for-all-your-online-christmas-shopping-1.4386351')
tree = html.fromstring(page.content)

# Parse the DOM. XPath is moderately convenient for this, but can't get us all the way,
# so we gotta use regexes.
for x in tree.xpath('//*[@id="content_left"]/article/div/section/div/p'):
    para = (html.tostring(x))
    site_details = re.findall(pattern, str(para))

    if len(site_details) == 0:
        continue

    line = list(filter(None, site_details[0]))
    url = line[0].replace('%20', '')
    sitename = line[1]
    description = line[2].replace('%20', '')

    c.execute("INSERT INTO searchDestinations(name, description, mainURL) VALUES (?, ?, ?)",
        (sitename, description, url))

    # For concatenating into a text file for custom search engine upload
    print (url)

# Save (commit) the changes, and close.
conn.commit()
conn.close()

# Format of the to-be-searched sites
# CREATE TABLE searchDestinations(
#   destinationID INTEGER PRIMARY KEY,
#   name TEXT,
#   description TEXT,
#   mainURL TEXT,
#   searchURL TEXT,
#   tags TEXT
