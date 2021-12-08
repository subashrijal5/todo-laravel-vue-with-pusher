
<!-- GETTING STARTED -->
## Getting Started


### Prerequisites

You will need PHP, Composer and Node.js. For MacOS I recommend installing them with [Homebrew](https://brew.sh/). For Windows see instructions for [PHP](https://windows.php.net/download/), [Composer](https://getcomposer.org/doc/00-intro.md#installation-windows) and [Node](https://nodejs.org/en/download/).

### Installation

1. Get your free Pusher API Keys at [https://pusher.com](https://pusher.com)
2. Clone this repo
3. Install Composer packages
   ```sh
   composer install
   ```
4. Install NPM packages
   ```sh
   npm install
   ```
5. Create a mysql database 
    ```
    create database and set it on your env file
    ```
6. Enter your API keys in `.env`
   ```
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=
   ```
7. Enter the path to your database file
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=todo
    DB_USERNAME=root
    DB_PASSWORD=

    ```
8. Initilise the database
    ```sh
    php artisan migrate
    ```
9. Compile the webpages and run it
    ```sh
    npm run dev
    php artisan serve
    ```

<!-- USAGE EXAMPLES -->
## Usage

Go to http://127.0.0.1:8000/, register a couple of users or you can run commande php artisan db:seed to create users

<!-- CONTACT -->
## Contact

You can contact me by : subashrijal5@gmail.com
