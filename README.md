# admin-shipping-charges

This package provides an Admin Shipping Charges Manager for managing shipping rates, shipping methods, and shipping zones within your Laravel application.

## Features

- Add, edit, and delete shipping methods (e.g., Standard, Express, Overnight)
- Manage shipping rates for different methods and zones
- Group geographic regions into shipping zones for targeted rates
- Assign rates based on country, state, city, or custom rules
- Enable/disable shipping methods
- Searchable, paginated listing for shipping methods and rates
- Soft-delete support for shipping methods and rates

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-shipping-charges.git"
    }
]
```

### 2. Require the package via Composer
```bash
composer require admin/shipping_charges:@dev
```

### 3. Publish assets
```bash
php artisan shipping_charges:publish --force
```
---

## Usage

1. **Create**: Add new shipping methods and rates, define shipping zones, and assign rates to zones.
2. **Read**: View shipping methods, rates, and zones in searchable, paginated tables.
3. **Update**: Modify shipping method details, rates, and zone assignments.
4. **Delete**: Soft-delete shipping methods or rates from the system.
5. **Enable/Disable Methods**: Update the status of shipping methods.
6. **Zone Management**: Group locations into zones for flexible rate assignment.

## Admin Panel Routes

| Method | Endpoint                                 | Description                              |
| ------ | ---------------------------------------- | ---------------------------------------- |
| GET    | /shipping_methods                        | List all shipping methods                |
| POST   | /shipping_methods                        | Create a new shipping method             |
| GET    | /shipping_methods/{shipping_method}      | Get shipping method details              |
| PUT    | /shipping_methods/{shipping_method}      | Update a shipping method                 |
| DELETE | /shipping_methods/{shipping_method}      | Delete a shipping method                 |
| POST   | /shipping_methods/updateStatus           | Enable/disable a shipping method         |
| GET    | /shipping_rates                          | List all shipping rates                  |
| POST   | /shipping_rates                          | Create a new shipping rate               |
| GET    | /shipping_rates/{shipping_rate}          | Get shipping rate details                |
| PUT    | /shipping_rates/{shipping_rate}          | Update a shipping rate                   |
| DELETE | /shipping_rates/{shipping_rate}          | Delete a shipping rate                   |

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // shipping charges routes here
});
```

## License

This package is open-sourced software licensed under the MIT license.