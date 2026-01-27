# HR Performance Management System (HRPMS)

composer install;

php artisan migrate --path=database/migrations/version100;

composer dump-autoload;

php artisan db:seed;

php artisan key:generate;

php artisan storage:link;

npm install

npm run build

php artisan serve

username 
samileking9@gmail.com
armandodavis@teganas.co.tz
swaumu.davis@teganas.co.tz
same password for all users === Password


TODO
-> expense approve in admin task profile => Done

-> user management => incomplete

-> edit task  => Done

-> Expenses management

-> breadcrumbs

-> complete user profile only for admin

-> improve dashboard

-> search to the top bar => Done

-> logs

-> system notifications

-> to fix user assigned budget can not exceed total task budget

if anything found miss add here


## TESTING OBSERVATIONS
1. Edit task button haifany kazi(Inaleta error)
2. Close button and Delete button ya Task hairespond => Done
3. Approve button haifanyi kazi => Done
4. Assign user button inafungua fresh kiwidget ila ukisubmit inaleta mysql error => hii pia nadhan haujarun command ya php artisan migrate:fresh --path=database/migrations/version100
5. Activate and diactivate button departments arent working too => Done
6. User's Tasks arent retrieved yet inaleta mysql query instead of data  => confirm hiyo command hapo juuu
7. search bar not connected yet => Done

8. We don't have the expenses page, we have a repeated task page on expenses page.
9. When we add a new user we should be able to set, edit passwords => Done
10. When the user is given an admin role or HOD, The user type should change as for now it only display staff. => Done
11. The department section, the list of department I suggest them to be clickable, show the list of staff in that department, we can extra add the list of tasks and tabs for tasks and staffs in that department. Lastly is the edit and deactivate button can be inside the clickable table.
12. Modify the dashboard on completed tasks, right now it returns all the tasks as completed
13. On Tasks, the task progress is showing value like 11% before even the task starting, or being assigned to somehow.
14. The activate button on tasks returns errors => Done
15. On performance evaluation the member of staff should be evaluated once, One user shouldnt be evaluated twice.s => Done


## Important
1. if task status is equal to done or complete admin user can not add expense, document or assign new user, can not delete or update task
2. 
