<h1>Meeting API</h1>
<br>
<p>Meeting API provides optimal meeting times for people in any part of the world, and also provides a list of countries and timezones to aid in providing accurate data, for getting optimal meeting times.</p>

<h2>Usage</h2>
<p>Clone this repo</p>
<p>Ensure you have composer installed on your machine https://getcomposer.org/download/</p>
<p>In the root directory of the application, open terminal</p>
<p>Execute: composer install</p>
<p>Execute: php artisan serve</p>
<p>Using your prefered API testing application (Postman, Thunder Client, etc.)</p>
<h3>API Routes</h3>
<p>GET(request) http://127.0.0.1:8000/api/countries_timezone : get all countires and their respective timezones</p>
<p>POST(request) http://127.0.0.1:8000/api/meeting : the following body fields are used</p>
<p>name: dates[], value:date format:YYYY-MM-DD, eg. 2022-05-07. This field represents dates that are desired to have a meeting.<span>(This field is required, and it has an array type)</span></p>
<p>name: country code, value: eg. NG. This field is required</p>
<p>name: timeZones value: eg. Africa/Lagos. This field is required</p>
<p>name: startTime value: eg. 09:00. This field is required and represents your office startTime</p>
<p>name: stopTime value: eg. 17:00. This field is required and represents your office stopTime</p>
<p>name: day2_Ops value:date format:YYYY-MM-DD, eg. 2022-05-07. This is an optional field and represents Day 2 operations</p>

<h4>Day 2 Operations</h4>
<p>Day 2 operations is the time when a product is deployed or shipped for the customer, day 2 operations involve maintaining, monitoring, and optimizing the system, in this API, a condition has been made possible where a person  involved in day2 operation would not be available for a meeting </p>

<h2>Implementation</h2>
<p>This API was built upon the timezonedb API --docs-- https://timezonedb.com/references/get-time-zone and the calendarific api --docs-- https://calendarific.com/api-documentation. These APIs are accurate, constantly up-to-date, and consumers are always updated on any changes in the schema. Also their reponse is quick as compared to querying a database for such data.</p>
<p>Considering that the provided office start and close time, reflects winter time and summer time (DST) at a particular region, with the data provided, first validation is done to confirm and accept input data, then we filter the dates with day2 operations and holidays. From the left out dates, using the office start and close times provided, I added an hour to the start time to get the first optimal time for a meeting, for a date, and subtracted 2 hours from the close time to get last optimal time for a meeting to start, for the same date. With that I provided al available time to start a meeting.</p>

<h3>Run Test Suite</h3>
<p>Execute: php artisan test</p>

<h3> How Latency could be reduced in this API</h3>
<p>Latency could be reduced in this API by first running a command: php artisan optimize. This command clears the route cache and makes a fresh cache, does this same for its configuration, and files. This API does not make use of the ORM database, so there is less to worry about latency, and we have moved our dependence on storage to faster Databases by leveraging and building on already optimized APIs</p>

<h4>Docker Container</h4>
<p>I want to mention that I really tried running this application in a docker container, and got into alot of issues. I had to recreate the application, as I had messed everything up while trying to run it in a docker container. If given the opportunity and time I would make it work. Thank you.</p>
