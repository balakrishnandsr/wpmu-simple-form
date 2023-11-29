# WPMU Simple Form
A simple form plugin.

# Usage
WPMU Simple Form Plugin used to store and get user data.

# Available Filters
* `filter: wpmu_simple_form_list_content`:  to change the list content/style
* `filter: wpmu_simple_form_customizable_content`:  to change the form content/style

# Available ShortCode
* `[my_form]`:  to show the form
* `[my_list]`:  to list the stored data

# REST API - End Points

* `www.yoursite.com/wp-json/wpmu-simple-form-api/v1`:  to list all stored result
* `www.yoursite.com/wp-json/wpmu-simple-form-api/v1`:  to store the new data. example {'name': 'Bala', 'user_notes': 'Example Note'}
* `www.yoursite.com/wp-json/wpmu-simple-form-api/v1/id`:  to get the stored result by id. exmple we send the numbers(id of data) via url

