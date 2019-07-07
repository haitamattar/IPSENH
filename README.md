# Portfolio

[![pipeline status](https://attardev.com/root/portfolio/badges/master/pipeline.svg)](https://attardev.com/root/portfolio/commits/master)

A portfolio.

## Development

This section contains instructions for Developers who work on this project.

### Setup

We use laravel, so make sure you have that installed.
See [https://laravel.com/docs/5.7](https://laravel.com/docs/5.7#installation).

Now you can setup the `.env` file and generate a key to secure your sessions.

```
mv .env.example .env
php artisan key:generate
```

Link the storage using:
```
php artisan storage:link
```

The dependencies can be created with:

```
composer install
npm install
```

Then compile the sass/css and js files using:

```
npm run dev
```

Then setup your database using:
```
php artisan migrate --seed
```

### Running

You can serve the application from the `public/` folder with your server of choice. Or use:

```
php artisan serve
```

### Compiling sass/css and js changes

The sass, css, and js files are compiled and copied to the `public` folder using Laravel Mix as defined in `webpack.mix.js`.
To avoid having to manually recompile this for every change, you can open a new terminal and run

```
npm run watch
```

This will continue to listen to changes to any of the affected files and recompile when changes are made.

### Testing locally

You can run the backend (PHP) tests using:
```
composer test
```

If you want to run the frontend (VueJS) test you can use:
```
npm run test
```

### Linting

To manually lint use:
```
npm run lint
```

If you want ESLint to automatically fix some issues you can run:
> Warning: this may mess up some of the indentation

```
npm run lint-fix
```
