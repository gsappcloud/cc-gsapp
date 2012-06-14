// $Id: README.txt,v 1.1.2.5 2009/03/04 04:05:34 mundowen Exp $

Pagination (Node)
-----------------
INSTALLATION

To install, place the entire "pagination" folder within your modules directory. Under Administer -> Site Building -> Modules, enabled the pagination module.

CONFIGURATION

You may now select which node types you wish to enable pagination on by navigating to Administer -> Site Configuration -> Pagination. The main content of arbitrary node types (Page, Story, etc...) may be paginated according to one of three methods:

Method 1: Content is paginated by a selectable "words per page" count.
Method 2: Content is paginated by manual breaks, inserted by the content creator.
Method 3: Content is paginated by manual breaks, based on <h3> tags.

Method 1 allows for quick and easy pagination, and is ideal for users who are looking to have their longer content split into multiple pages with the least amount of hassle. Just select the "words per page" threshold for a particular content type, and all existing and future nodes of that type will be automatically paginated accordingly.

Methods 2 and 3 allow for fine-tuned control over pagination breaks, useful for content creators who need to set specific break points in their content. Method 2 paginates content based on the presence of break tags ([pagebreak] or [ header = SOME TITLE ]), whereas Method 3 paginates based on <h3> elements.

note: To use Method 3 pagination, make sure <h3> tags are allowed under your Input Filters.

PAGER DISPLAY

Pagination (Node) offers two styles of pager display. The default display uses Drupal's pagination, which shows as a collection of page numbers, including typical pager controls (such as next page, previous page, etc...). In addition to that, Pagination (Node) allows administrators to utilize a "Table of Contents" style list, which creates an index of pages, mapped to an optional page name. Content types may be adjusted to display the default pager, the table of contents pager, or both simultaneously.

PAGE HEADINGS

If a particular Content type is set to display a "Table of Contents" style list, page headings may be added for each page under any method. Methods 2 and 3 offer the more straight forward approaches, as content creators can add the page heading specifically in the page break: ie. [ header = Sample Page Header ], or <h3>Sample Page Header</h3>. Regardless of paging method chosen, pages which do not have a specific title set will default to "Page x" within the table of contents. The only exception is the first page, which will always be the original title of the content.

To set page titles under method 1, content creators may enter a collection of page titles while creating / updating their content. The interface will show the expected number of pages the content will have, and content creators may add a page titles (one per line) to match that number. The page estimate will be updated periodically while content is being added or updated.

THEMING NOTES

The default pager will respect alterations via the theme_pager hook. The table of contents may likewise be modified. Table of contents links are handled through theme_item_list. In addition, the ToC may be modified by the presence of a toc.tpl.php file in your theme. The ToC is a container (id="pagination-toc"), while the ToC menu may be styled based on their respective classes (class="pagination-toc-list" and class="pagination-toc-item").

MAINTAINER

mundanity [http://drupal.org/user/373605]