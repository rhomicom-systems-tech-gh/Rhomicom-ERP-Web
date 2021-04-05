
# Rhomicom-ERP-Web
Rhomicom Enterprise Resource Planning System (Web Version)

Rhomicom Enterprise Resource Planning System is a complete ERP system proudly started in Ghana by Ghanaians.
The company behind this project is Rhomicom Systems Tech. Ltd.(http://rhomicom.com)
The software can be used to manage both backend and front-end operations of all kinds of institutions and organisations.
The sample is currently hosted on https://portal.rhomicom.com
Help can be found on https://wiki.rhomicom.com

Completed or Activated Modules
=======================================
1. Accounting
2. Person Data
3. Internal Payments (Payroll, Membership Fees, Dues and Contributions)
4. Sales/Inventory
5. Visits/Appointments
6. Events Management
7. Facility Rentals/Hospitality Management
8. Learning/Performance Management System
9. Self-Service
10. e-Voting
11. Chat Rooms/Forums
12. Banking & Microfinance Module
13. Vault Management Module
14. Visit https://wiki.rhomicom.com/ for more info

Typical Target institutions/organisations
=========================================
1. Hotels/Restaurants/Coffee shops
2. Super Markets/Boutiques/Wholesale and Retail Shops
3. Associations/Churches/Professional Bodies
4. Construction Firms
5. Academic Institutions

System Features
=========================================
- Email/SMS Alerts  
- Import/Export to Excel  
- Responsive Design (Able to work on any Computer,Tablet, Phone)  
- Online Capable (Can be accessed anywhere in the world)  
- Summary Dashboards  
- Customizable Interfaces   
- Customizable Reports
- Chat Rooms/Forums/Circulars/Announcements

Sofware Requirements
=======================
1. All Operating Systems
2. PostgreSQL Database 9.3 or later (Can be hosted on any Operating System)
3. Java 1.6 or later

Hardware Requirements
=====================
Same hardware requirements for above Sofware requirements

Technologies/Tools Used (Credits/Acknowledgements)
==================================================
1. Java 1.8-(Netbeans 8.1) or later
2. PHP 5.6 or later
3. HTML/CSS/javascript (Bootstrap, Datatables Plugin, Bootstrap Dialog, SummerNote,jQuery, fontawesome 4)
4. TIBCO JasperSoft Studio 6.2.0
5. PostgreSQL Database 9.3 or later

# TODO
1. Projects Management Module
2. Help Desk Module

# Issues

If you have any problems with or questions about this image, please contact us through a GitHub issue.

# Contributing

You are invited to contribute new features, fixes, or updates, large or small; we are always thrilled to receive pull requests, and do our best to process them as fast as we can.

Before you start to code, we recommend discussing your plans through a GitHub issue, especially for more ambitious contributions. This gives other contributors a chance to point you in the right direction, give you feedback on your design, and help you find out if someone else is working on the same thing.

# License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

# Others
composer init --require=twig/twig:1.13.* -n

composer init
composer install
composer update

composer require --dev phpunit/phpunit ^8
./vendor/bin/phpunit --version
./vendor/bin/phpunit

composer require "codeception/codeception" --dev
./vendor/bin/codecept bootstrap
./vendor/bin/codecept g:cest acceptance First
./vendor/bin/codecept run

php vendor/bin/codecept bootstrap
php vendor/bin/codecept generate:cest acceptance First