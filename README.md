# PropertiesCRUD

## Brief
This application is a PHP & MySQL CRUD system designed to create, update and delete properties in the database. 

Data enters the database through an API. The way in which data is added is through a file called update.php which is suggested to be a scheduled task on the server. Each time the file is run, all new entries are added to the database.

## Database Table: Proprerties

This is the structure of the data table

Name | Type
--- | ---
uuid | varchar(50)
country | varchar(50)	
county | varchar(50)
description | varchar(550)
address | varchar(50)	
image_full | varchar(50)
image_thumbnail | varchar(50)
latitude | varchar(50)
longitude | varchar(50)	
num_bathrooms | varchar(50)	
num_bedrooms | varchar(50)	
price | varchar(50)	
property_type | varchar(50)
town | varchar(50)
type | varchar(50)
