<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



<!-- 

aqui van los insert

    INSERT INTO `empleados`(`nombre_empleado`, `apellido_empleado`, `cedula`) VALUES 
    ('Daniela','Silva','31531886'),
    ('Juan','Pernia','31532886'),
    ('Maria','Garcia','31532886'),
    ('Emily','Morgado','31533886');

    INSERT INTO `categorias`( `nombre_categoria`) VALUES 
    ('Utensilios'), 
    ('Equipos'), 
    ('Ingredientes y Alimentos'),
    ('Bebidas'),('Otros'); 

    INSERT INTO `suministros`(`nombre_suministro`, `precio_unitario`, `categorias_idcategorias`) VALUES 
    ('Cuchillos', 25.00, 1), 
    ('Sartenes', 150.00, 2), 
    ('Harina', 50.00, 3),
    ('Vinos', 200.00, 4), 
    ('Servilletas', 10.00, 5),
    ('Platos', 35.00, 1), 
    ('Hornos', 800.00, 2), 
    ('Arroz', 45.00, 3), 
    ('Cerveza', 70.00, 4), 
    ('Candelabros', 75.00, 5),
    ('Cucharas', 20.00, 1), 
    ('Refrigeradores', 1500.00, 2), 
    ('Aceite de Oliva', 90.00, 3),
    ('Whisky', 250.00, 4), 
    ('Velas', 20.00, 5),
    ('Tazas', 15.00, 1), 
    ('Microondas', 400.00, 2), 
    ('Azúcar', 30.00, 3), 
    ('Ron', 180.00, 4), 
    ('Flores', 50.00, 5),
    ('Tenedores de postre', 18.00, 1), 
    ('Batidoras', 300.00, 2), 
    ('Tomates', 25.00, 3), 
    ('Champaña', 220.00, 4), 
    ('Portavasos', 12.00, 5),
    ('Cuchillos de carne', 40.00, 1), 
    ('Licuadoras', 250.00, 2), 
    ('Lechuga', 15.00, 3), 
    ('Vodka', 150.00, 4), 
    ('Manteles de lino', 100.00, 5),
    ('Cucharas de té', 15.00, 1), 
    ('Congeladores', 2000.00, 2), 
    ('Queso', 60.00, 3),
    ('Tequila', 180.00, 4), 
    ('Bandejas de plata', 120.00, 5),
    ('Platos de sopa', 30.00, 1), 
    ('Parrillas', 600.00, 2), 
    ('Café', 80.00, 3), 
    ('Ginebra', 170.00, 4), 
    ('Jarrones', 70.00, 5);


    INSERT INTO `proveedores_has_suministro`( `Proveedores_idProveedores`, `Suministro_idSuministro`) VALUES (1,2);
 -->
