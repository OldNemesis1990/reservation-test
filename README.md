# Skedonk Reservation Platform

## Introduction

The Skedonk reservation platform allows administrators or managers to create accommodations, and clerks can create booking reservations for guests. Unfortunately, the project is not complete due to various interruptions. However, the included features showcase my capabilities.

## Installation

- PHP 8.2+
- Laravel 10
- Vue 3
- InertiaJS
- MySQL

### Dependencies

- Breeze (SSR, TailwindCSS, Vue3JS)
- Axios (promise-based HTTP library)
- Vue Datepicker UI (StartDate - EndDate date range)

## Configuration

1. Update `.env` with database parameters.
2. Create a storage link.
3. Run `composer install`.
4. Run `npm install`.
5. Run `php artisan migrate:fresh --seed` to set up roles, permissions, test case users, and admin.

## Features

- Roles and Permissions via Spatie.
- Accommodations Creation via admin or managers.
- Reservation creation, more information, Check in trigger with time in date via staff.
- Relationships between models have been established.
- Migrations are set up to Cascade on Delete where required.
- Dynamic path in storage is established on accommodation creation or image upload.
- Dispatch jobs emails with custom templates

## How to Use

1. Navigate to the login screen.
2. Log in as an admin or manager.
3. Create an accommodation.
4. Create a reservation via the "Make Booking" button.

## Nice to Knows

- Relationships between models established.
- Migrations set up to Cascade on Delete where required.
- Dynamic path in storage established on accommodation creation or image upload.

## What Has Not Been Done

- Accommodation single view.
- Accommodation update.
- Reservation update.
- Create, Update, Delete, Read Staff Members.
- Unauthorized accommodation view for guests to create their own reservation.
- Show occupied dates on reservation calendar.
- Reservation Filter.
- Accommodation availability filter.
- Logout.
- Bugs with Total amount and calendar change have been found yet unresolved.

## To-Do List

- Accommodation single view.
- Accommodation update.
- Reservation update.
- Create, Update, Delete, Read Staff Members.
- Unauthorized accommodation view for guests to create their own reservation.
- Show occupied dates on reservation calendar.
- Reservation Filter.
- Accommodation availability filter.
- Logout.
- Bugs with Total amount and calendar change resolution.