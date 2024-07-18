Installation
Step 1: Install Composer
Download Composer:

Visit getcomposer.org/download and follow the instructions for your operating system.
Install Composer:

Once downloaded, run the Composer installer.
Follow the on-screen instructions to complete the installation.
Verify Installation:

Open a terminal or command prompt.
Type composer --version to ensure Composer has been installed correctly.
Step 2: Set Up Environment Variables

Clone the Repository

Create .env File:

In the project root directory, create a .env file.
Copy the contents of .env.example (if provided) into .env.

DB_HOST=localhost
DB_DATABASE=mydatabase
DB_USERNAME=root
DB_PASSWORD=secret


Step 3: Install Dependencies

composer install

