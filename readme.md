<p align="center"><img src="http://i.imgur.com/65d4Lob.png" width="300"></p>


# Scrum Burndown Tool
Build with Laravel 5.4, Vuejs 2.3 and has support for the Jira Agile REST API

The default Jira chart does not give you alot of information, it only shows the story points left in the sprint.
To squels this information gap, behold the new burndown, featured with muiltiple burndown lines. In this way the Product Owner, Scrum master and the Development team can see the real progress of the sprint.

Not all companies have the (expencive) Jira software tool, to make this burndown usefull for as many scrum masters and mortals, the tool can also be used manualy (although a bit less cool).

-----
## Table of Contents

* [Features](#item1)
* [Quick Start](#item2)
* [Installation Guide](#item3)
* [User Guide](#item4)
* [Screenshots](#item5)

-----
<a name="item1"></a>
## Features
* Homepage lists all sprints, with seperation of active sprints and previous sprints
* Indepth view of sprint progress
    * Total work (Story Points)
    * Work done
    * Progress (sub-tasks and bugs/issues valued against Total work)
    * Ideal guideline through the end of the sprint
* Abillity to fetch all the statistics with the Jira Agile REST API
* Adding as many sprint/boards as you like
* Manualy editing/configuring the sprints

-----
<a name="item2"></a>
## Quick Start
Clone this repository and install the dependencies.

    $ git clone https://github.com/PaulReekers/scrum-burndown-tool.git CUSTOM_DIRECTORY && cd CUSTOM_DIRECTORY
    $ composer install

Rename the .env.example to .env, **create a database** and edit the .env file.

    $ mv .env.example .env
    $ vim .env

Generate an application key, migrate the tables and seed data.

    $ php artisan key:generate
    $ php artisan migrate
    $ php artisan db:seed

Install node and npm following one of the techniques explained within this [link](https://gist.github.com/isaacs/579814) to create and compile the assets of the application.

    $ npm install
    $ npm run production


You are done, now run the tool.

    $ php artisan serve

Open [http://localhost:8000](http://localhost:8000) in your browser.

To access the admin panel, open
[http://localhost:8000/login](http://localhost:8000/login) in your browser.

There is a default user `admin`, email address `admin@admin.com` and password `burndown` account created.

-----
<a name="item3"></a>
## Installation Guide

If you want to use the Jira API, you need to create 2 filters in Jira:

**Total work**:

    project = <Project>
    AND (issuetype = Story
        OR issuetype = Sub-task
        OR issuetype = Task
        OR issuetype = Bug)
    AND Sprint in openSprints()


**Work done**:

    project = <Project>
    AND (issuetype = Story
        OR issuetype = Sub-task
        OR issuetype = Task
        OR issuetype = Bug)
    AND Sprint in openSprints()
    AND status in (Done, Closed, Cancelled)
    ORDER BY rank DESC`


**Create a cronjob**:

    $ crontab -e

Add the following rule:

    * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1


There is a scheduled command that refreshes all the active burndown boards at 07:00.

Check if its correctly saved.

    $ crontab -l

<a name="item4"></a>
## User Guide

**Total work**:

Total open story points
Formula:

    Total Story Points - Story Points Done

**Progress**:

All (sub-)tasks and bugs/issues valued against the Total Story Points.
Formula:

    ( Total Story Points / Total Tasks ) * ( Total Tasks - Tasks Done )

**Ideal**:

A guideline through the end of the sprint
Formula:

    Total Story Points - (Total Story Points / Total days sprint) * Days left

**Total Story Points**:

Total amound of Story Points currently in the sprint.

----
**CLI commands**

    $ php artisan list

Show all the routes

    $ php artisan route:list

Run the cronjobs commands manually

    $ php artisan schedule:run

Clear Cache

    $ php artisan config:clear

-----
<a name="item5"></a>
## Screenshots

Homepage overview:
<img src="http://i.imgur.com/nNxYGch.png">

Edit Sprint:
<img src="http://i.imgur.com/IAObgt7.png">

Burndown Chart:
<img src="http://i.imgur.com/YuKXK79.png">

