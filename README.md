# Devspinotek LMS - Question Bank

Modul Bank Soal yang reusable untuk aplikasi LMS berbasis Laravel.

## Instalasi di Project Client (mis. `event-bigimpact`)

1. Tambahkan repository GitHub public ke `composer.json` project:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/devspinotek/lms-question-bank.git"
    }
]
```

2. Install package:

```bash
composer require devspinotek/lms-question-bank:^1.0
```

3. (Opsional) Publish config jika ingin menyesuaikan prefix route, middleware, atau model:

```bash
php artisan vendor:publish --tag=question-bank-config
```

4. Jalankan migration:

```bash
php artisan migrate
```

5. (Opsional) Override view di `resources/views/vendor/question-bank/`:

```bash
php artisan vendor:publish --tag=question-bank-views
```

