# MySQLi Assignment - To-Do List by T-Ralph

## MySQL Database Setup
- Duplicate `includes/DB.Credentials.Sample.php` and rename to `includes/DB.Credentials.php`
- Set Database Credentials at `includes/DB.Credentials.php`
- Import SQL database at `database/mysqltododatabase.sql`
- Database name is `mysqltododatabase` (you can change the database name, just ensure it is properly noted at `includes/DB.Credentials.php`)
- `database/mysqltododatabase.sql` contains sample data like:
    - Sample user account:
        - Username: Sample User 1
        - Password: 1@resU@elpmaS
    - Sample Categories
    - Sample Active To-do Task
    - Sample Overdue To-do Task
    - Sample Completed To-do Task

## Entity Relationship Diagram
![ERD](img/ERD.png?raw=true)

## Unexpected Feature
- Added user account functionalities so each user can manage their separate to-do tasks.

## Requirements
- Create an ERD of your database
- Create an SQL file to import and create tables/seed any data (An instructor should be able to import your SQL file and be ready to go.) Make sure your .sql file has the name of the database at the top like this: -- Database: `adventureworks`
- A task has a due date
- A task has a category
- The user is able to add an item to the Active To-Do’s list by using the input field and add button
- The user is able to move an active to-do to the Complete To-Do’s list
- The user should be able to remove a task entirely
- There should be three statuses a task can have:
    - To do - tasks that are not complete and the due date has not passed
    - Overdue - tasks that are not complete and are past the due date
    - Completed - tasks that are complete 

## Challenges
- The ability to add/edit/remove Task Categories
- Styling the project to look nice
- An unexpected feature is present (Make sure you mention what it is in your README.md file.)

Docs: https://docs.google.com/document/d/1rnpsH7MAWg5vDiOqBNxU_HNgSOAOFix60s8f-5W6VVY/edit# <br>
Trello: https://trello.com/b/w9M5WTo5/mysqli-assignment-to-do-list-by-t-ralph <br>
GitHub: https://github.com/TECHCareers-by-Manpower/mysqli-assignment-to-do-list-T-Ralph <br>