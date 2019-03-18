# API
Provides REST API for various goals

## Data

The API is mainly used to retrieve data.
But there are several routes to fill.
For example, `POST /api/mobile/v1/upload` and `POST /api/mobile/v1/comment_add`.

To fill in the data, you need to run the import.
Import starts with `php artisan import:*` commands.

Caching starts automatically at the end of the import.
