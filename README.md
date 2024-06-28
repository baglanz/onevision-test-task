Laravel Project Setup with Sail and Docker


Prerequisites

Docker: Make sure Docker is installed on your machine. You can download Docker from (“https://www.docker.com/get-started”).

Docker Compose: Docker Compose is included with Docker Desktop, but if you're using Linux, you might need to install it separately. Check the official documentation for instructions (“https://docs.docker.com/compose/install”).

Getting Started

Step 1: Clone the Repository

First, clone the repository to your local machine:

git clone https://github.com/baglanz/onevision-test-task

cd your-repository


Step 2: Configure Environment Variables

Copy the example environment file to create your own .env file:

cp .env.example .env

Open the .env file and update the necessary database settings:

DB_CONNECTION=pgsql

DB_HOST=postgres

DB_PORT=5432

DB_DATABASE=onevision

DB_USERNAME=root

DB_PASSWORD=root

Step 3: Install Dependencies

Install the project dependencies using Composer:

composer install

Step 4: Set Up Docker Containers

Run the following command to start the Docker containers using Laravel Sail:

./vendor/bin/sail up -d


Step 5: Run Database Migrations

Run the database migrations to set up your database schema:

./vendor/bin/sail artisan migrate


Accessing the Application

Your Laravel application should now be up and running. You can access it in your web browser at http://localhost:8080 (or the port you configured in your .env file).

Stopping the Containers

To stop the Docker containers, use the following command:

./vendor/bin/sail down



Troubleshooting

If you encounter any issues, here are some common troubleshooting steps:

1. Clearing Configuration Cache:

./vendor/bin/sail artisan config:clear


2. Clearing Application Cache:

./vendor/bin/sail artisan cache:clear

3. Checking Container Logs:

./vendor/bin/sail logs

4. Checking Database Connection: Make sure your database service is running and correctly configured in the .env file.
