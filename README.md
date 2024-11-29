# Project Setup

## Requirements

Before setting up the project, make sure you have the following installed:

-   [PHP](https://www.php.net/) (preferably PHP 8.1 or higher)
-   [Composer](https://getcomposer.org/)
-   [Node.js](https://nodejs.org/)
-   [NPM](https://www.npmjs.com/)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/Kanoe99/sort-app.git
cd sort-app
```

### 2. Install dependencies

```bash
composer install
npm i
```

### 3. ENV

Copy contents of .env.example into newly created .env

### 4. DB

Create database.sqlite in database folder
run

```bash
php artisan migrate:fresh --seed
```

### 5. KEY

run

```bash
php artisan key:generate
```

### 6. Run App

```bash
sail up -d
```

or if you have no 'sail shortcut available

```bash
./vendor/bin/sail up -d
```

---

# Настройка проекта

## Требования

Прежде чем настроить проект, убедитесь, что у вас установлены следующие компоненты:

-   [PHP](https://www.php.net/) (желательно PHP 8.1 или выше)
-   [Composer](https://getcomposer.org/)
-   [Node.js](https://nodejs.org/)
-   [NPM](https://www.npmjs.com/)

## Установка

### 1. Клонируйте репозиторий

```bash
git clone https://github.com/Kanoe99/sort-app.git
cd sort-app
```

### 2. Установка зависимостей

```bash
composer install
npm i
```

### 3. ENV

Скопируйте содержимое из .env.example в новый файл .env.

### 4. DB

Создайте файл database.sqlite в папке database.
Затем выполните команду:

```bash
php artisan migrate:fresh --seed
```

### 5. Ключ

Выполните команду

```bash
php artisan key:generate
```

### 6. Запустите приложение

```bash
sail up -d
```

или, если нету шортката 'sail'

```bash
./vendor/bin/sail up -d
```
