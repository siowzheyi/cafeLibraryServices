<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Business rule

1. A user must clear penalty before borrowing next item
2. A library only collection is through penalty late return
3. A cafe only collection is through order fees
4. Renting/borrowing process must get approval from staff, then only start to borrow
5. Return process no need get approval from staff, just need to return physically at counter
6. Payment gateway is paypal
7. Push notification is one signal for android only
8. Once staff approved the renting/borrowing request, cannot reject anymore
9. Beverage is categorized by category, book is categorized by genre, room is categorized by type
