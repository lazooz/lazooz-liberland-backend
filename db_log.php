<?php
die;
/*

 
db.location_payload.ensureIndex( { user_id: 1 } ) 

db.friend_recommend_requests.ensureIndex( { recommending_user_id: 1 } ) 
 
db.createCollection("contacts")

db.contacts.ensureIndex( { "user_id": 1, "cellphone_int": 1 } )

db.users.ensureIndex( {"cellphone": 1 } )


db.createCollection("user_distance_accumulative_daily")
db.user_distance_accumulative_daily.ensureIndex( { "user_id": 1, "time": 1 } )
 
 
db.createCollection("user_location_history")
db.user_location_history.ensureIndex( { "user_id": 1, "time": 1 } )



db.createCollection("client_exception_model")
db.client_exception_model.ensureIndex( { "user_id": 1 } )

db.createCollection("public_key")
db.public_key.ensureIndex( { "user_id": 1 } )


deployed to prod, dev , test

------------- 24/8/2014 -----------------

db.createCollection("blockchain_code")
db.blockchain_code.ensureIndex( { "code": 1 } )


------------- 24/8/2014 -----------------

24/8/2014 - deployed to prod, dev , test

------------- 26/8/2014 -----------------

db.createCollection("block_chain_pending_balance")
db.block_chain_pending_balance.ensureIndex( { "user_id": 1 } )

------------- 26/8/2014 -----------------




------------- 27/8/2014 -----------------

db.createCollection("block_chain_report")

db.createCollection("block_chain_report_user_log")
db.block_chain_report_user_log.ensureIndex( { "user_id": 1 } )
db.block_chain_report_user_log.ensureIndex( { "report_id": 1 } )

------------- 27/8/2014 -----------------


------------- 28/8/2014 -----------------
db.createCollection("stats_users_month")
db.stats_users_month.ensureIndex( {"user_id": 1, "month": 1 } )

db.createCollection("stats_users_day")
db.stats_users_day.ensureIndex( {"user_id": 1, "day": 1 } )

------------- 28/8/2014 -----------------

4/9/2014 - deployed to client, dev8080 

db.createCollection("stats_total_users_month")
db.stats_users_month.ensureIndex( { "month": 1 } )

4/9/2014 - deployed to client,dev8080


------------- 8/9/2014 -----------------

db.createCollection("client_const_data")


------------- 8/9/2014 -----------------


10/9/2014 - deployed to client,dev8080


------------- 11/9/2014 -----------------

db.createCollection("client_report_issue")


------------- 11/9/2014 -----------------

11/9/2014 - deployed to client,dev8080







------------- 21/9/2014 -----------------

db.location_payload.ensureIndex( {"user_id": 1, "loc_timestamp": -1 } )

------------- 21/9/2014 -----------------


22/9/2014 - deployed to client,dev8080,dev

------------- 22/9/2014 -----------------
db.createCollection("client_push_messages")
------------- 22/9/2014 -----------------

22/9/2014 - deployed to dev8080 , dev , client

------------- 29/9/2014 -----------------
db.createCollection("suspicious_users")
db.suspicious_users.ensureIndex( { "user_id": 1 } )
------------- 29/9/2014 -----------------

29/9/2014 - deployed to dev8080 , dev , client


------------- 30/9/2014 -----------------

db.createCollection("stats_all_users_day")
db.stats_all_users_day.ensureIndex( { "day": 1 } )

------------- 30/9/2014 -----------------

30/9/2014 - deployed to dev8080 , dev , client



------------- 1/10/2014 -----------------

db.createCollection("stats_total_users_day")
db.stats_total_users_day.ensureIndex( { "day": 1 } )

------------- 1/10/2014 -----------------
1/10/2014 - deployed to dev8080 , dev , client


------------- 17/10/2014 -----------------

db.location_payload.ensureIndex( { "db_insert_time": 1 } )

db.users.ensureIndex( { "created_time": 1 } )

------------- 17/10/2014 -----------------

17/10/2014 - deployed to dev8080 , dev , client





 */