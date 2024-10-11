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
