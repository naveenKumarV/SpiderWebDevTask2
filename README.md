# SpiderWebDevTask2

## Github API Search and Statistics
This web application provides basic information and statistics of a github repository and github user. You can search repositories by keyword, search repositories of a github user and get some information and activity statistics of a repository. You can also check out the statistics for total number of github repositories in different languages and thereby judge their popularity.This web application uses laravel ( a php framework ) and runs on a mysql database.

##Build instructions
*Install xampp or wampp server. Click [here](https://www.apachefriends.org/index.html) to go to xampp installation page. Make sure you install it properly.

*Install laravel in your local machine. You can do that by visiting the website [laravel.com](http://laravel.com) and check out the documentation. 

*You can even run this app without HOMESTEAD. I personally didn't use HOMESTEAD during my application development. You have to turn on vt-x in your bios settings (if its not turned on by default) to install Homestead.

*Download this project to your local machine and extract the zip file. If you are using xampp server, put the extracted project folder in htdocs sub-directory within the xampp installation directory.

*In the command prompt, navigate to the project directory and type the command

composer update

The composer will automatically download the entire laravel framework and the php dependencies used in this project. You need not manually download any of them. If you get any error, first check whether your composer is properly installed by running the command

composer  
(or)
composer -v

*Now you can find the vendor directory is created in your project folder. Please check in the vendor directory whether the autoload.php file is present. If not, probably there might be some error in your installation.

*Now , change the .env.example file in the project folder to .env

*if you are  using xampp server and not using homestead, make the folowing changes in the .env file.

DB_HOST=localhost

DB_DATABASE=github

DB_USERNAME=root

DB_PASSWORD=''

Here 'github' is the name of the database (you can give any other name as you like).
DB_USERNAME and DB_PASSWORD are the environmental variables which represent your database username and password respectively. Generally, the values which I have given above automatically hold true in your case too since these are  default for xampp server. If you have some other username and password, please change these environmental variables accordingly.

*run the command

php artisan key:generate

This command automatically sets the environmental variable APP_KEY

*Create a new database with name 'github' ie, the same name which you gave to DB_DATABASE in the .env file. You can do so through the phpMyAdmin tool in your server.

*run the command 

php artisan migrate 

This command creates all the necessary tables in the database.

*With these build instructions, you are ready to run the project.To do so, go to your browser and type the url

localhost/{folder-path}/{folder-name}/public/

Here {folder-path} is the path where you placed this project in your server and {folder-name} is the name you gave to this project folder. If you placed it in xampp/htdocs directory and named it SpiderWebDevTask2, then the homepage url is 

localhost/SpiderWebDevTask2/public/

##Modules used 
1.[Lavacharts](lavacharts.com) : For drawing graphs.

2.Bootstrap :For responsive web design and styling.

3.[KnpLabs Github API package](https://github.com/KnpLabs/php-github-api) : This package is an  Object Oriented wrapper for GitHub API, written with PHP 5 and uses github api/v3 .

As I have previously said, there is no need to manually dowmload any modules or dependencies used in this project as the composer does this for you automatically.
