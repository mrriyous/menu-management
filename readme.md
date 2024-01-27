# Menu Management

This project consists of a Laravel backend (spa-backend) and a Vite frontend (spa-frontend) for building a Single Page Application (SPA).

## SPA-backend (Laravel)

### Getting Started

1. Navigate to the `spa-backend` directory:

    ```bash
    cd spa-backend
    ```

2. Install Laravel dependencies:

    ```bash
    composer install
    ```

3. Create a copy of the `.env.example` file and configure your environment variables:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database configuration and other settings.

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Migrate the database:

    ```bash
    php artisan migrate
    ```
6. Seed the user:

    ```bash
    php artisan db:seed
    ```

7. Start the Laravel development server:

    ```bash
    php artisan serve
    ```

    The backend will be accessible at `http://localhost:8000`.

## SPA-frontend (Vite)

### Getting Started

1. Navigate to the `spa-frontend` directory:

    ```bash
    cd spa-frontend
    ```

2. Install frontend dependencies:

    ```bash
    npm install
    ```

3. Start the Vite development server:

    ```bash
    npm run dev
    ```

3. To change the api server:

    Go to spa-frontend/.env and change `VITE_API_URL` value

### Production Build

To build the frontend for production:

```bash
npm run build