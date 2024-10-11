# How to run this project in development mode
1. clone the project (which already you have done ;)
2. run `cp .env.example .env` // create a file .env and then copy all content from .env.example to .env
3. run `composer update`
4. run `php artisan key:generate`
5. run `npm install`
6. run `php artisan migrate` On prompt, **YES** to create sqlite db
7. run `php artisan migrate:fresh --seed`

Now we have to run below 4 command in 4 terminal or tmux(4 pane)
1. `php artisan serve`
2. `npm run dev`
3. `php artisan schedule:work`
4. `php artisan queue:listen`

Now,
open brower and hit http://localhost:8000 (or any other port if 8000 was not available, check terminal output for correct port)
Search with any random nid and you will get a status "not registered" and a link to register.
Register with at least one user to test notification sending.

Note that:
If you are testing at thursday or friday then you will not get notification in next section
so modify the db and set user vaccine_schedule column in users table to tomorrow date.

Update VACCINE_REMINDER_SENT_HOUR="21:00" in the .env and set a recent future time so that schedule command run asap.
Now restart schedule command on terminal

After schedule run check laravel log at storage/logs/laravel.log and you will see the mail sent

Thank you.


# How to make search faster
When users search an item to get status, we are querying the data with the nid column.
This nid column has a unique index, so we will get benefit of indexing, which will help us
to get the data very fast.
Even for very large amounts of data, we can get consistent fast speed by increasing mysql innodb_buffer_pool_size.
But only relying on the innodb buffer is not enough because it is a shared space and other indexes (data) can impact our nid index.

So for that, we might have a memory-based cache (ex: Redis) strategy between our web server and database.
When users search with a nid, we will first look for schedule_date against the nid in the cache.
If found, we will return the result analyzing the schedule_date, and we don't need to call the database.
If not found, then we will call the database, get the schedule_date, and store it in the cache if found.
This way, once the schedule_date is in cache, we can get fast speed for the search.

And for the cache eviction policy, we can use the LRU (Least Recently Used) technique.


# Additional requirment of sending SMS
If in future we get a requirment to send SMS, we just have to modify SendScheduleReminderNotification and
append the code for sending sms in the handle method. We can use helper_fuction/Third party solution/API call or
a custom class to send SMS.
