#!/usr/bin/python3

from lxml import html
import pprint
import re
import requests
import sqlite3

conn = sqlite3.connect('production.db')
c = conn.cursor()


# CREATE TABLE searchDestinations(
#   destinationID INTEGER PRIMARY KEY,
#   name TEXT,
#   description TEXT,
#   mainURL TEXT,
#   searchURL TEXT,
#   tags TEXT

link_text_regex="href=\"(.+?)\"><(?:strong|u)>(.+?)<\/(?:strong|\/u)>.?<\/a><br>(.*)<\/p>|href=\"(.+?)\"><u>(.+?)<\/u><\/a><\/strong>(?:.+?)<br>(.*)<\/p>|href=\"(.+?)\" target=(?:.+?)>(.+?)<\/a>(?:.+?)<br>(.+?)<\/p>|href=\"(.+?)\">(.+?)<\/a><\/strong><br>(.+?)<\/p>"
pattern=re.compile(link_text_regex)

page = requests.get('https://www.irishtimes.com/news/consumer/400-yes-400-irish-retailers-for-all-your-online-christmas-shopping-1.4386351')
tree = html.fromstring(page.content)

#for x in tree.xpath('//a'):
#    print (html.tostring(x))

for x in tree.xpath('//*[@id="content_left"]/article/div/section/div/p'):
    para = (html.tostring(x))
    site_details = re.findall(pattern, str(para))
    
    if len(site_details) == 0:
        continue
        
    line = list(filter(None, site_details[0]))

    c.execute("INSERT INTO searchDestinations(name, description, mainURL) VALUES (?, ?, ?)",
        (line[0].replace('%20', ''),
        line[1],
        line[2].replace('%20', '')))
    #print("INSERT INTO searchDestinations(mainURL, name, description) VALUES (%s, %s, %s)", line[0], line[1], line[2])
    #print (line)

# Insert a row of data

# Save (commit) the changes
conn.commit()

# We can also close the connection if we are done with it.
# Just be sure any changes have been committed or they will be lost.
conn.close()

    #link_plus_text = para
    #link = x.xpath('.//a')
    # if len(link) != 0:
    #     for y in link:
    #         strong = y.xpath('.//strong')
    #         print (html.tostring(y))
    #         print (html.tostring(strong))

#for x in tree.xpath('//p[@class="no_name"]'):
#    for y in tree.
#    print (html.tostring(x))
