# SpiderWebDevTask2

## Github API Search and Statistics
This web application provides basic information and statistics of a github repository and github user. You can search repositories by keyword, search repositories of a github user and get some information and activity statistics of a repository. You can also check out the statistics for total number of github repositories in different languages and thereby judge their popularity.This web application uses laravel ( a php framework ) and runs on a mysql database.

##Build instructions
* Install xampp or wampp server. Click [here](https://www.apachefriends.org/index.html) to go to xampp installation page. Make sure you install it properly. Go to xampp control panel and start the Apache and mySQL modules.

* Install laravel in your local machine. You can do that by visiting the website [laravel.com](http://laravel.com) and check out the documentation. 

* You can even run this app without HOMESTEAD. I personally didn't use HOMESTEAD during my application development. You have to turn on vt-x in your bios settings (if it's not turned on by default) to install Homestead.

* Download this project to your local machine and extract the zip file. If you are using xampp server, put the extracted project folder in htdocs sub-directory within the xampp installation directory.

* In the command prompt, navigate to the project directory and type the command

composer update

The composer will automatically download the entire laravel framework and the php dependencies used in this project. You need not manually download any of them. If you get any error, first check whether your composer is properly installed by running the command

composer  
(or)
composer -v

(This works only in the case where composer is globally installed).

* Now you can find the vendor directory is created in your project folder. Please check in the vendor directory whether the autoload.php file is present. If not, probably there might be some error in your installation.

* Now , change the .env.example file in the project folder to .env

* if you are  using xampp server and not using homestead, make the folowing changes in the .env file.

DB_HOST=localhost

DB_DATABASE=github

DB_USERNAME=root

DB_PASSWORD=''

(The default password is empty. So the two single quotation marks mean empty)
Here 'github' is the name of the database (you can give any other name as you like).
DB_USERNAME and DB_PASSWORD are the environmental variables which represent your database username and password respectively. Generally, the values which I have given above automatically hold true in your case too since these are  default for xampp server. If you have some other username and password, please change these environmental variables accordingly.

* run the command

php artisan key:generate

This command automatically sets the environmental variable APP_KEY

* Create a new database with name 'github' ie, the same name which you gave to DB_DATABASE in the .env file. You can do so through the phpMyAdmin tool in your server.

* run the command 

php artisan migrate 

This command creates all the necessary tables in the database.

* With these build instructions, you are ready to run the project.To do so, go to your browser and type the url

localhost/{folder-path}/{folder-name}/public/

Here {folder-path} is the path where you placed this project in your server and {folder-name} is the name you gave to this project folder. If you placed it in xampp/htdocs directory and named it SpiderWebDevTask2, then the homepage url is 

localhost/SpiderWebDevTask2/public/

##Modules used 
1.[Lavacharts](lavacharts.com) : For drawing graphs.

2.Bootstrap :For responsive web design and styling.

3.[KnpLabs Github API package](https://github.com/KnpLabs/php-github-api) : This package is an  Object Oriented wrapper for GitHub API, written with PHP 5 and uses github api/v3 .

As I have previously said, there is no need to manually dowmload any modules or dependencies used in this project as the composer does this for you automatically.
 
##Server routes
* '/'           : Home (or) Welcome page
* '/auth/login' : Login page for registered users
*  '/auth/logout' : To logout
*  '/auth/register' : To register
*  '/github/repo/info' : Displays a form where you can submit a repository name and it's owner name and get it's info.
*  '/github/repo/search' : Displays a form where you can submit a key word and search repositories associated with the keyword.
*  '/github/repo/statistics' : Displays a form where you can submit a repository name and it's owner name and get the activity statistics of the repository based on number of commits per day.
*  '/github/statistics' : Displays a bar graph of  top 5 languages in github with most number of repositories and also you can add a language to compare the number of repositories in that language with these five languages.
*  '/github/user/info' : Displays a form where you can submit a github username and get his/her info.
*  '/github/user/repos/search' : Displays a form where you can submit the github user name and get all his repositories.
*  '/github/search_history' : Displays the previously made searches of  a logged in user. 

So, for example, if you are on the '/github/search_history' route, the url in the browser will  be

localhost/{folder-path}/{folder-name}/public/github/search_history

where {folder-name} and {folder-path} have same meanings as explained previously.

##Tables
1.'github_users' table:
This table stores the names and information about the gihub users who are searched by the user (both registered and unregistered users) in 10 columns.

2.'repositories' table:
This table stores the names and  information about the repositories which are searched by the user (both registered and unregistered users) in total 12 columns. Whenever a user searches for a repository, both the repository info and github user info are stored in the 'repositories' table and 'github_users' table. The column 'github_user_id' on 'repositories' table corresponds to 'id' column in the 'github_users' table.

3.'users' table :
Stores the information of registered users of this website.

4.'repository_user' table:
A user of this website can search many repositories and a repository can be searched by many users. This table is a pivot table (as Laravel calls it)  which  stores the foreign keys 'user_id' and 'repository_id' to display previously searched repositories by a logged in user. 

5.'github_user_user' table:
This pivot table establishes the many to many relationship between the website users and github users searched by them.It has foreign keys 'user_id' and 'github_user_id' to display previously searched github users by a logged in user.

6.'github_languages_statistics' table:
This table stores the languages and no of repositories in that language.

**Note**:
I didn't set up any OAuth between github and my app. This is because I personally felt that by setting up an OAuth, the persons checking this project must register this app in github website and provide their client-id and secret which is little difficult. Actually, the github-search-api rate limit for unauthorized api requests is 10/minute while it is just 30/minute even for authorised requests. I took all possible precautions not to exceed both the github-search-api rate limit as well as the maximum execution time of server (30 seconds). But if the execution time exceeds 30 seconds due to slow internet or some other reason, I recommend to change its value to 60 in the php.ini file in your server. Also, please wait for a few seconds and make your request again, if you encounter 'exceeded-rate-limit' error (there is very less probability of encountering this error).

##SCREENSHOTS
![welcome page](/screenshots/welcome.png)
![login page](/screenshots/login.png)
![registration page](/screenshots/register.png)
![repo info page](/screenshots/repo_info.png)
![repo search](/screenshots/repo_search.png)
![repo statistics](/screenshots/repo_stats.png)
![statistics](/screenshots/stats.png)
![user_info](/screenshots/user_info.png)
![user repos](/screenshots/user_repos.png)
![search_history](/screenshots/history.png)
