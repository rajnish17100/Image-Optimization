# Image-Optimization
In this project we are going to learn how to resize an image taken through a form, with the help of php 
we will also registration and login process to access an image file(displyed on your dashboard)

create a folder named as uploads to store all the uploaded image
first of all configure your php.ini file and assign the max Size as per your wish, here  in this case it is taken as 400 MB


; Maximum allowed size for uploaded files.
upload_max_filesize = 400M

; Must be greater than or equal to upload_max_filesize
post_max_size = 400M


#Create a table register_user to store all the info of user the in the database so that later we retrive it . Refer database.sql file

#please read server.php file to get the php code of image optimization.
