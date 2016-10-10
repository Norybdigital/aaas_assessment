# aaas_assessment

This program requires PHP 5.5 and curl.  The root folder and the documents folder need to be readable and writable.

The program is pretty straightforward.  When you first load it up, you will need to fetch the DOIs by hitting the generate button.  The program iterates through the dois.txt file and creates a new json file for DOI.  It also creates an index.json file that contains the names and publishers of each of the DOIs.

When you return to the index page, you should see a long list of all the DOIs.  When you click one of the links, you will see the details page for the selected item.