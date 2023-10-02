# Online Recipe Management System

## Brief Description

This application is an online recipe management system that makes use of 3 virtual 
machines; a user interface, a database server, and an admin interface.

## VM 1: User Interface

This vm will be repsonsible for running the user web interface of the application, where 
users interact with the system.

Users will be able to:

1. Create an account
2. Log in to their account
3. Search for recipes
4. Create and submit custom recipes


## VM 2: Database Server

This vm will be responsible for data storage. It will use MySQL to host the database that
contains data related to this application.

The database has the following tables:

1. User 
    - Stores information related to the user such as username and password
    - Stores access level of user (user or admin)
3. Ingredient
    - Stores names and identifiers of all ingredients used in the system
4. Recipe
    - Stores information related to the recipe itself such as intructions
5. RecipeIngredient
    - Matches ingredients with recipes using the unique identifiers


## VM 3: Admin Interface

This vm will be responsible for running the admin web interface of the application, 
where administrators interact with the system with a higher level of
privilege than regular users.

Admin will be able to:

1. Create and delete user accounts
2. Create, edit, and delete recipes
3. Approve or deny recipes submitted by users and pending review


## Deploying and Running the Application

### Prerequisites
1. You will need an AWS account, so if you haven't already create an AWS account
2. You will need a private key for the EC2 instances

### Vagrant helper VM

1. Clone the repository to your own local machine
2. Check that you have vagrant installed
    - You can do this by running `vagrant --version`
3. If you do not have vagrant installed you can install it here: 
    <https://developer.hashicorp.com/vagrant/downloads?product_intent=vagrant>
4. Make sure you have VirtualBox downloaded on your machine
    - If you do not already have VitualBox, you can download it here:
    <https://www.virtualbox.org/wiki/Downloads>
    - If you are running on arm64 architecture you will need to download the 
    'macOS/ARM64 BETA' version from the test builds (although as this is a 
    developer preview, you may encounter issues):
    <https://www.virtualbox.org/wiki/Testbuilds>
5. From the cloned project repository location run: `vagrant up`
    - This will automatically start the vagrant virtual machione
6. Run `vagrant ssh default` 


### AWS CLI

1. From your AWS account copy the AWS CLI
2. From the vagrant virtual machine command line run `nano ~/.aws/credentials`
3. Paste the AWS CLI into the ~/.aws/credentials.
4. Run `export TF_VAR_private_key_path="[path/to/private/key]"` replacing the placeholder with 
    the actual path to the private key


### Terraform 

1. Initialise and apply terraform by running the following three commands:
    - `terraform init`
    - `terraform plan`
    - `terraform apply`
        - type `yes` when prompted
        - take note of the IP addresses printed by the .tf file as you will need them later, 
        such as to ssh into the EC2 instance.
2. Next set your AWS access key and AWS secret key using the following commands, replacing the square 
    brackets with the actual values which can be found in your AWS account or in ~/.aws/credentials:
    - `AWS_ACCESS_KEY=$([AWS ACCESS KEY])`
    - `AWS_SECRET_KEY=$([AWS SECRET KEY])`
3. Next we will be saving the IP addresses of the EC2 instances, the servers' internal 
    IP addresses, the endpoint of the RDS instance, and the sns topic arn, run the following commands:
    - `RDS_ENDPOINT=$(terraform output rds_endpoint | tr -d '"')`
    - `USER_IP=$(terraform output user_interface | tr -d '"')`
    - `ADMIN_IP=$(terraform output admin_interface | tr -d '"')`
    - `USER_INTERNAL_IP=$(terraform output user_internal_ip | tr -d '"')`
    - `ADMIN_INTERNAL_IP=$(terraform output admin_internal_ip | tr -d '"')`
    - `SNS_TOPIC_ARN=$(terraform output sns_topic_arn | tr -d '"')`


### Setting the Internal IP Addresses

2. Run the following commands to replace the placeholders for the servers' internal IP addresses
    into both .conf files:
    - `sed -i "s/INTERNAL_ADMIN_IP_PLACEHOLDER/$ADMIN_INTERNAL_IP/g" admin-website.conf`
    - `sed -i "s/INTERNAL_USER_IP_PLACEHOLDER/$USER_INTERNAL_IP/g" admin-website.conf`
    - `sed -i "s/INTERNAL_ADMIN_IP_PLACEHOLDER/$ADMIN_INTERNAL_IP/g" user-website.conf`
    - `sed -i "s/INTERNAL_USER_IP_PLACEHOLDER/$USER_INTERNAL_IP/g" user-website.conf`
1. Run the following commands to do the same for the EC2 public IP addresses:
    - `sed -i "s/ADMIN_IP_PLACEHOLDER/$ADMIN_IP/g" admin-website.conf`
    - `sed -i "s/USER_IP_PLACEHOLDER/$USER_IP/g" admin-website.conf`
    - `sed -i "s/ADMIN_IP_PLACEHOLDER/$ADMIN_IP/g" user-website.conf`
    - `sed -i "s/USER_IP_PLACEHOLDER/$USER_IP/g" user-website.conf`
3. Run the following commands to do the same for the AWS access and secret keys:
    - `sed -i "s/AWS_ACCESS_KEY_PLACEHOLDER/$AWS_ACCESS_KEY/g" admin-website.conf`
    - `sed -i "s/AWS_SECRET_KEY_PLACEHOLDER/$AWS_SECRET_KEY/g" admin-website.conf`
    - `sed -i "s/AWS_ACCESS_KEY_PLACEHOLDER/$AWS_ACCESS_KEY/g" user-website.conf`
    - `sed -i "s/AWS_SECRET_KEY_PLACEHOLDER/$AWS_SECRET_KEY/g" user-website.conf`


### Setting the RDS Endpoint

2. Run the following command to use the RDS endpoint ot create a db_config.php which will be used by the
    php files to get the database credentials:
    - `sed "s/RDS_ENDPOINT_PLACEHOLDER/$RDS_ENDPOINT/" db_config_template.php > db_config.php`

### (Optional) Check if apache is running on the EC2 instances

1. Run the following commands to ssh into one of the EC2 instances using the path to your private key
    and the ip address of the EC2 instance:
    - `ssh -i [path/to/private/key] ubuntu@[EC2-ip-address]`
2. Once in the EC2 command line, run the following:
    - `sudo systemctl status apache2`
    - If the apache sever is running you should see output similar to the following:

### User Interface EC2 Web Server

1. In a new terminal run the following command to ssh into the user interface EC2:
    - `ssh -i ~\.ssh\cosc349-2023.pem ubuntu@[user-EC2-ip]`
2. Run the follwoing commands:
    - `sudo chown ubuntu:ubuntu /var/www/html/`
    - `sudo chown ubuntu:ubuntu /etc/apache2/sites-available/`
3. Remove the default apache page:
    - `sudo rm /var/www/html/index.html`
5. In a new terminal run the following commands to copy files into the user interface EC2 using your private key: 
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\www\user ubuntu@[user-EC2-ip]:/var/www/html/`
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\www\common ubuntu@[user-EC2-ip]:/var/www/html/`
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\user-website.conf ubuntu@[user-EC2-ip]:/etc/apache2/sites-available/`
    - `scp -i ~\.ssh\cosc349-2023.pem tf-deploy\db_config.php ubuntu@[user-EC2-ip]:/var/www/html/common`
6. Back in the ssh terminal run these commands to install and configure composer and php-xml:
    - `sudo apt install composer`
    - `cd /var/www/html`
    - `composer require aws/aws-sdk-php`
    - `sudo apt-get install php-xml`
7. Run these two commands to enable our configurations and restart the server:
    - `sudo a2ensite user-website`
    - `sudo systemctl reload apache2`

### Admin Interface EC2 Web Server

1. In a new terminal run the following command to ssh into the admin interface EC2:
    - `ssh -i ~\.ssh\cosc349-2023.pem ubuntu@[admin-EC2-ip]`
2. Run the follwoing commands:
    - `sudo chown ubuntu:ubuntu /var/www/html/`
    - `sudo chown ubuntu:ubuntu /etc/apache2/sites-available/`
3. Remove the default apache page:
    - `sudo rm /var/www/html/index.html` 
4. Exit the EC2 by runing `exit`
5. Run the following commands to copy files into the admin interface EC2 using your private key: 
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\www\admin ubuntu@[admin-EC2-ip]:/var/www/html/`
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\www\common ubuntu@[admin-EC2-ip]:/var/www/html/`
    - `scp -r -i ~\.ssh\cosc349-2023.pem tf-deploy\admin-website.conf ubuntu@[admin-EC2-ip]:/etc/apache2/sites-available/`
    - `scp -i ~\.ssh\cosc349-2023.pem tf-deploy\db_config.php ubuntu@[admin-EC2-ip]:/var/www/html/common`
6. Back in the ssh terminal run these commands to install and configure composer and php-xml:
    - `sudo apt install composer`
    - `cd /var/www/html`
    - `composer require aws/aws-sdk-php`
    - `sudo apt-get install php-xml`
7. Run these two commands to enable our configurations and restart the server:
    - `sudo a2ensite admin-website`
    - `sudo systemctl reload apache2`

### Create Database and Load in Data

1. Back in the helper vm terminal, run the following command to create the database: 
    - `mysql -h [RDS-Endpoint] -P 3306 -u admin -p mydb < local_database.sql`
    - When prompted enter the mysql password (same password that was set by database.tf)
    - Make sure to replace the [RDS-Endpoint] with the actual endpoint which should have been printed
    - If you are copying the endpoint from the output of the database.tf file, make sure not to include the port number
    to the terminal output by the database.tf after running terraform apply
2. Run the following command to load dummy data into the database:
    - `mysql -h [RDS-Endpoint] -P 3306 -u admin -p mydb < insert-data.sql`
    - Enter the mysql password when prompted

### (Optional) Test database has been configured correctly

1. Ssh into RDS instance by running the follwoing:
    - `mysql -h [RDS-Endpoint] -P 3306 -u admin -p`
    - Enter the mysql password when prompted
    - Run `use mydb;`
    - Run `show tables;`, you should see the expected tables printed
    - Run a query, for example: `select * from Recipe;`, you should see some dummy recipes printed

### Visit application in browser

The application should be fully deloyed and running by this point, you can view the application by visiting either of 
the public IP address for the EC2 instances, both of which should redirect you to the sign in page, where you will have 
the option to create an account or log in using the pre loaded dummy data:
    - Use username "Sam123" and password "password123" to enter the application with a user level privilege (user interface)
    - Use username "admin1" and password "password1" to enter the application with an admin level privilege (admin interface)

Please note that creating an account will only grant you with the basic user level privilege and access to the user 
interface. Please use the pre loaded admin credentials to access the admin interface.