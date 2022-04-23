## About This Project

This is a web application prototype for URLs shortener built using Laravel and VueJS.

-   For any given URL it generates a short URL in the format of www.domain.com/[hash].
-   In short URLs [hash] values are unique and consist of 6 alphanumeric symbols.
-   It recognizes duplicate URL and instead of generating new short URL, shows previously created one.
-   Upon submission, the URL is checked using the "Google Safe Browsing" API (https://developers.google.com/safe-browsing/v4/lookup-api).
-   If any match is found from the "Google Safe Browsing" API, it displays an warning message, "The URL you've entered is not safe for browsing!";
-   Upon opening the short URL, the user is redirected to the original URL.
-   There's a 'visits' column in the database to count how many times the short URL has been visited.
-   For any invalid URL it displays an error message, "The URL you've entered is not Valid!".

## Deploying The Project

-   Clone this git repository.

    git clone https://github.com/nirnoy112/url-shortener.git

-   Go to project directory and install required dependencies.

    cd url-shortener

    composer install

-   Create .env file, appliacation key and set database credentials in the .env file.

    copy .env.example .env

    php artisan key:generate

-   Run database migration.

    php artisan migrate

-   Run NPM commands for VueJS installation.

    npm install

    npm run dev

-   Finally, run the application on the server.

    php artisan serve
