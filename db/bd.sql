SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE IF EXISTS teclado_custom;
CREATE DATABASE IF NOT EXISTS teclado_custom DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE teclado_custom;

CREATE TABLE carritos (
  id int(11) NOT NULL,
  usuario_id int(11) NOT NULL,
  producto_id int(11) NOT NULL,
  tabla varchar(50) NOT NULL,
  cantidad int(11) DEFAULT 1,
  fecha timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO carritos (id, usuario_id, producto_id, tabla, cantidad, fecha) VALUES
(38, 16, 2, 'switches', 1, '2025-05-18 19:51:50'),
(41, 17, 2, 'deskmats', 1, '2025-05-18 20:11:35'),
(42, 17, 2, 'silenciadores', 1, '2025-05-18 20:11:48'),
(43, 17, 11, 'keyboards', 1, '2025-05-18 20:11:58'),
(44, 17, 4, 'switches', 1, '2025-05-18 20:12:12');

CREATE TABLE deskmats (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  tamanio enum('900x400','800x300','XL','M','Custom') DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO deskmats (id, nombre, descripcion, tamanio, imagen, precio, precio_original) VALUES
(1, 'Cyber Grid', 'Deskmat con líneas futuristas', '900x400', 'media/deskmats/cyber.jpg', 29.99, NULL),
(2, 'Retro Wave', 'Diseño estilo synthwave', '800x300', 'media/deskmats/retro.jpg', 27.99, NULL),
(3, 'Galaxy XL Mat', 'Alfombrilla XL con diseño galáctico', 'XL', 'media/deskmats/galaxy-xl.jpg', 24.99, 29.99),
(4, 'Retro Wave 900', 'Estilo retro en tamaño 900x400', '900x400', 'media/deskmats/retro-wave.jpg', 19.99, 24.99),
(5, 'Dark Nebula M', 'Deskmats compacta con tonos espaciales', 'M', 'media/deskmats/dark-nebula.jpg', 14.99, NULL),
(6, 'Samurai Spirit', 'Estética japonesa para escritorio completo', '900x400', 'media/deskmats/samurai.jpg', 22.99, 27.99),
(7, 'Cyber Grid 800', 'Estilo cibernético en tamaño 800x300', '800x300', 'media/deskmats/cyber-grid.jpg', 17.99, 19.99),
(8, 'FindDkey Exclusive', 'Diseño exclusivo personalizado FindDkey', 'Custom', 'media/deskmats/finddkey-custom.jpg', 34.99, 39.99),
(9, 'Ocean Drift', 'Diseño oceánico relajante', 'XL', 'media/deskmats/ocean-drift.jpg', 26.99, NULL),
(10, 'Marble Smoke', 'Diseño elegante tipo mármol', '800x300', 'media/deskmats/marble-smoke.jpg', 18.50, 22.00),
(11, 'Tech Grid', 'Minimalista en gris para setup neutro', 'M', 'media/deskmats/tech-grid.jpg', 13.99, 15.99),
(12, 'Blossom Spring', 'Estilo floral delicado para espacios coloridos', '900x400', 'media/deskmats/blossom.jpg', 21.99, NULL);

CREATE TABLE keyboards (
  id int(11) NOT NULL,
  nombre varchar(100) NOT NULL,
  descripcion text DEFAULT NULL,
  marca varchar(50) DEFAULT NULL,
  tamanio enum('Full-size','TKL','75%','65%','60%','40%') DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL,
  lenguaje varchar(10) DEFAULT NULL,
  features varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO keyboards (id, nombre, descripcion, marca, tamanio, imagen, precio, precio_original, lenguaje, features) VALUES
(1, 'Keychron K6', 'Teclado mecánico compacto con Bluetooth', 'Keychron', '65%', 'media/keyboards/keychron-k6.jpg', 90.99, 109.99, 'ANSI', 'Wireless'),
(2, 'Keychron Q1', 'Teclado premium totalmente personalizable', 'Keychron', '75%', 'media/keyboards/keychron-q1.jpg', 179.99, NULL, 'ANSI', 'Hot-swap, RGB'),
(3, 'QwertyKeys QK65', 'Teclado customizable con sonido profundo', 'QwertyKeys', '65%', 'media/keyboards/qk65.jpg', 149.99, 169.99, 'ISO', 'Gasket mount'),
(4, 'QwertyKeys QK60', 'Modelo compacto ideal para viajes', 'QwertyKeys', '60%', 'media/keyboards/qk60.jpg', 129.99, 139.99, 'ISO', 'Compact'),
(5, 'Geon F1-8X', 'Teclado entusiasta de alta gama', 'Geon', 'TKL', 'media/keyboards/f1-8x.jpg', 299.99, 349.99, 'ANSI', 'Gasket mount, Premium'),
(6, 'Geon Frog TKL', 'Modelo TKL con montura Gasket', 'Geon', 'TKL', 'media/keyboards/frog-tkl.jpg', 249.99, NULL, 'ISO', 'Gasket mount'),
(7, 'Eloquent Clicks 60', 'Teclado con diseño exclusivo', 'Eloquent', '60%', 'media/keyboards/ec60.jpg', 159.99, 189.99, 'ISO', 'Exclusive Design'),
(8, 'Eloquent Clicks 75', 'Modelo intermedio con estética sobria', 'Eloquent', '75%', 'media/keyboards/ec75.jpg', 179.99, NULL, 'ISO', 'Gasket mount'),
(9, 'Nuphy Air75', 'Low profile inalámbrico RGB', 'Nuphy', '75%', 'media/keyboards/air75.jpg', 129.99, NULL, 'ANSI', 'Wireless, Low-profile, RGB'),
(10, 'Nuphy Halo65', 'Teclado compacto con retroiluminación', 'Nuphy', '65%', 'media/keyboards/halo65.jpg', 139.99, 159.99, 'ISO', 'RGB, Backlit'),
(11, 'Keychron K2', 'Modelo versátil con batería de larga duración', 'Keychron', '75%', 'media/keyboards/keychron-k2.jpg', 99.99, 119.99, 'ANSI', 'Wireless'),
(12, 'QwertyKeys QK75', 'Modelo robusto con diseño refinado', 'QwertyKeys', '75%', 'media/keyboards/qk75.jpg', 169.99, NULL, 'ISO', 'RGB, Gasket mount'),
(13, 'Geonworks W1-AT', 'Teclado premium full-size para oficina y gaming', 'Geon', 'Full-size', 'media/keyboards/w1-at.jpg', 329.99, 359.99, 'ANSI', 'Full-size, Premium'),
(14, 'Eloquent Clicks 40', 'Teclado ultracompacto para minimalistas', 'Eloquent', '40%', 'media/keyboards/ec40.jpg', 139.99, NULL, 'ISO', 'Ultra compact, Minimalist'),
(15, 'Nuphy Air60', 'Versión más ligera del Air75', 'Nuphy', '60%', 'media/keyboards/air60.jpg', 119.99, 129.99, 'ANSI', 'Low-profile, Wireless'),
(16, 'Keychron V1', 'Teclado mecánico básico con hot-swap', 'Keychron', '75%', 'media/keyboards/keychron-v1.jpg', 89.99, NULL, 'ANSI', 'Hot-swap'),
(17, 'QwertyKeys Compact', 'Modelo económico de 60%', 'QwertyKeys', '60%', 'media/keyboards/qk-compact.jpg', 109.99, 129.99, 'ISO', 'Compact, Budget'),
(18, 'Geon Mini 60', 'Teclado premium ultracompacto', 'Geon', '60%', 'media/keyboards/geon-mini.jpg', 279.99, 299.99, 'ISO', 'Premium, Compact'),
(19, 'Eloquent Pro65', 'Modelo entusiasta 65%', 'Eloquent', '65%', 'media/keyboards/pro65.jpg', 189.99, 219.99, 'ISO', 'Enthusiast, 65%'),
(20, 'Nuphy Studio Full', 'Full-size RGB profesional', 'Nuphy', 'Full-size', 'media/keyboards/nuphy-full.jpg', 159.99, 199.99, 'ANSI', 'Full-size, RGB');

CREATE TABLE keycaps (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  marca enum('GMK','Pbtfans','Signature Plastics') DEFAULT NULL,
  material enum('ABS','PBT') DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO keycaps (id, nombre, descripcion, marca, material, imagen, precio, precio_original) VALUES
(1, 'Glitch PBT', 'Set de keycaps con diseño glitch', 'Pbtfans', 'PBT', 'media/keycaps/glitch.jpg', 49.99, 55.99),
(2, 'Galaxy ABS', 'Keycaps con diseño de galaxia', 'GMK', 'ABS', 'media/keycaps/galaxy.jpg', 39.99, 44.99),
(3, 'Retro Classic', 'Keycaps estilo retro beige clásico', 'Signature Plastics', 'PBT', 'media/keycaps/retro.jpg', 59.99, 69.99),
(4, 'Neon Wave', 'Set de keycaps brillantes con efecto neón', 'Pbtfans', 'ABS', 'media/keycaps/neonwave.jpg', 49.99, 59.99),
(5, 'Carbon GMK', 'Set oscuro con acentos naranjas', 'GMK', 'ABS', 'media/keycaps/carbon.jpg', 129.99, 149.99),
(6, 'Ice Mint', 'Keycaps frescos con tonos pastel', 'Pbtfans', 'PBT', 'media/keycaps/icemint.jpg', 64.99, NULL),
(7, 'Skull Black', 'Keycaps negros con calaveras grabadas', 'GMK', 'PBT', 'media/keycaps/skullblack.jpg', 139.99, 159.99),
(8, 'Classic Beige', 'Diseño clásico ANSI beige', 'Signature Plastics', 'ABS', 'media/keycaps/classicbeige.jpg', 54.99, 69.99),
(9, 'Sky Blue', 'Keycaps azul cielo con leyendas limpias', 'Pbtfans', 'PBT', 'media/keycaps/skyblue.jpg', 44.99, 49.99),
(10, 'Dark Matter', 'Keycaps negros mate con letras gris oscuro', 'GMK', 'ABS', 'media/keycaps/darkmatter.jpg', 119.99, NULL),
(11, 'Bubble Pop', 'Set multicolor inspirado en caramelos', 'Signature Plastics', 'PBT', 'media/keycaps/bubblepop.jpg', 74.99, 84.99),
(12, 'Ocean Depths', 'Tonos azules profundos y leyendas submarinas', 'GMK', 'PBT', 'media/keycaps/ocean.jpg', 134.99, 154.99),
(14, 'GMK CYL BLOT KEYCAPS', 'Inspired by Viking rituals of Northern Europe', 'GMK', 'PBT', 'media/keycaps/Cyl_blot.jpg', 60.00, NULL),
(15, 'PBT GLITCH KEYCAPS', 'Inspired by the bowling alleys of 90s, this keycap set submerged your keyboard in a light playful nostalgia. ', 'Pbtfans', 'PBT', 'media/keycaps/GlitchPBT.jpg', 69.90, NULL);

CREATE TABLE lube (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO lube (id, nombre, descripcion, imagen, precio, precio_original) VALUES
(1, 'Krytox 205g0', 'Lubricante ideal para switches lineales', 'media/lube/krytox-205g0.jpg', 9.99, 11.99),
(2, 'Tribosys 3204', 'Lubricante versátil para switches táctiles', 'media/lube/tribosys-3204.jpg', 8.50, 9.99),
(3, 'Glaze Pro', 'Lubricante premium para estabilizadores', 'media/lube/glaze-pro.jpg', 7.99, NULL);

CREATE TABLE pcb (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  hotswap tinyint(1) DEFAULT NULL,
  layout enum('ANSI','ISO','HHKB') DEFAULT NULL,
  rgb tinyint(1) DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pcb (id, nombre, descripcion, hotswap, layout, rgb, imagen, precio, precio_original) VALUES
(1, 'DZ60 PCB', 'PCB compacta 60% con soporte RGB', 1, 'ANSI', 1, 'media/pcb/dz60.jpg', 24.99, 29.99),
(2, 'Bakeneko 65 PCB', 'Compatible con ANSI, sin hotswap', 0, 'ANSI', 0, 'media/pcb/bakeneko65.jpg', 17.99, NULL),
(3, 'Sofle PCB', 'PCB split ergonómica con RGB', 1, 'ISO', 1, 'media/pcb/sofle.jpg', 28.50, 32.99);

CREATE TABLE pedidos (
  id int(11) NOT NULL,
  usuario_id int(11) DEFAULT NULL,
  tabla varchar(50) NOT NULL,
  producto_tipo enum('case','pcb','plate','switch','keycap','stabilizer','silenciador','cable','firmware','deskmat') DEFAULT NULL,
  producto_id int(11) DEFAULT NULL,
  fecha_pedido timestamp NOT NULL DEFAULT current_timestamp(),
  estado enum('Pendiente','Procesado','Enviado','Entregado') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pedidos (id, usuario_id, tabla, producto_tipo, producto_id, fecha_pedido, estado) VALUES
(1, 9, '', '', 2, '2025-05-18 01:00:40', 'Enviado'),
(2, 9, '', '', 2, '2025-05-18 01:01:55', 'Pendiente'),
(3, 11, '', '', 2, '2025-05-18 01:03:12', 'Pendiente'),
(4, 11, '', '', 2, '2025-05-18 01:09:01', 'Pendiente'),
(5, 11, '', '', 2, '2025-05-18 11:48:04', 'Pendiente'),
(6, 13, '', '', 2, '2025-05-18 16:35:08', 'Pendiente'),
(16, 13, 'keyboards', NULL, 1, '2025-05-18 16:47:29', 'Pendiente'),
(17, 13, 'switches', NULL, 4, '2025-05-18 16:47:49', 'Pendiente'),
(18, 13, 'switches', NULL, 2, '2025-05-18 16:51:25', 'Pendiente'),
(19, 13, 'keyboards', NULL, 20, '2025-05-18 16:51:46', 'Pendiente'),
(20, 16, 'keyboards', NULL, 1, '2025-05-18 19:50:44', 'Pendiente'),
(21, 17, 'keycaps', NULL, 1, '2025-05-18 20:11:13', 'Pendiente'),
(22, 17, 'tools', NULL, 1, '2025-05-18 20:11:13', 'Enviado'),
(23, 18, 'switches', NULL, 2, '2025-05-19 13:11:00', 'Pendiente');

CREATE TABLE silenciadores (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  tipo enum('Foam PCB','Foam Case','Silicona Estabilizadores') DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO silenciadores (id, nombre, descripcion, tipo, imagen, precio, precio_original) VALUES
(1, 'Foam Mod', 'Espuma para placa PCB', 'Foam PCB', 'media/silenciadores/foam-mod.jpg', 3.99, 4.50),
(2, 'Case Foam XL', 'Espuma para el interior de la carcasa', 'Foam Case', 'media/silenciadores/case-foam.jpg', 5.99, NULL),
(3, 'Stab Silencers', 'Silicona para estabilizadores', 'Silicona Estabilizadores', 'media/silenciadores/stab-silencers.jpg', 2.99, 3.99);

CREATE TABLE stabilizers (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  tipo enum('Plate-mounted','PCB-mounted') DEFAULT NULL,
  lubricado tinyint(1) DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO stabilizers (id, nombre, descripcion, tipo, lubricado, imagen, precio, precio_original) VALUES
(1, 'Durock V2', 'Estabilizadores plate-mounted lubricados', 'Plate-mounted', 1, 'media/stabilizers/durock-v2.jpg', 11.99, 14.99),
(2, 'TX Stabilizers', 'Estabilizadores PCB-mounted sin lubricar', 'PCB-mounted', 0, 'media/stabilizers/tx.jpg', 9.99, NULL),
(3, 'C3 Equalz V3', 'Set completo de stabs prelubricados', 'PCB-mounted', 1, 'media/stabilizers/equalz-v3.jpg', 13.50, 15.99);

CREATE TABLE switches (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  tipo enum('Lineal','Táctil','Clicky') DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO switches (id, nombre, descripcion, tipo, imagen, precio, precio_original) VALUES
(1, 'Gateron Red', 'Switch lineal suave y silencioso', 'Lineal', 'media/switches/gateron-red.jpg', 0.35, 0.45),
(2, 'Gateron Brown', 'Switch táctil con bump suave', 'Táctil', 'media/switches/gateron-brown.jpg', 0.38, NULL),
(3, 'Gateron Blue', 'Switch clicky con sonido fuerte', 'Clicky', 'media/switches/gateron-blue.jpg', 0.40, 0.50),
(4, 'Kailh Box White', 'Clicky con alta definición sonora', 'Clicky', 'media/switches/kailh-box-white.jpg', 0.42, 0.49),
(5, 'Cherry MX Red', 'Switch clásico para gaming', 'Lineal', 'media/switches/cherry-mx-red.jpg', 0.45, NULL),
(6, 'Cherry MX Brown', 'Táctil, versátil para escribir y jugar', 'Táctil', 'media/switches/cherry-mx-brown.jpg', 0.47, 0.60),
(7, 'Holy Panda', 'Táctil premium con feedback fuerte', 'Táctil', 'media/switches/holy-panda.jpg', 0.75, 0.85),
(8, 'TTC Gold Pink', 'Lineal muy suave con precarga baja', 'Lineal', 'media/switches/ttc-gold-pink.jpg', 0.55, NULL),
(9, 'Kailh Speed Silver', 'Lineal ultra rápido para gaming', 'Lineal', 'media/switches/kailh-silver.jpg', 0.50, 0.60),
(10, 'Outemu Blue', 'Clicky económico con buen tacto', 'Clicky', 'media/switches/outemu-blue.jpg', 0.25, 0.30);

CREATE TABLE tools (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  descripcion varchar(255) DEFAULT NULL,
  imagen varchar(255) DEFAULT NULL,
  precio decimal(8,2) NOT NULL,
  precio_original decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tools (id, nombre, descripcion, imagen, precio, precio_original) VALUES
(1, 'Switch Puller Pro', 'Extractor de switches profesional', 'media/tools/switch-puller.jpg', 5.99, 6.99),
(2, 'Keycap Puller Deluxe', 'Extractor de keycaps ergonómico', 'media/tools/keycap-puller.jpg', 4.50, NULL),
(3, 'Toolkit FindDkey', 'Kit completo para montaje de teclados', 'media/tools/toolkit.jpg', 19.99, 24.99);

CREATE TABLE usuarios (
  id int(11) NOT NULL,
  nombre varchar(100) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  role enum('user','admin') DEFAULT 'user',
  fecha_registro timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios (id, nombre, email, password, role, fecha_registro) VALUES
(2, 'Iván', 'ivanvegahdez9@gmail.com', '$2y$10$GrVXMN09s.oCwSEzxaF3oOwln0gFB0bldktuDVqzNweJ/f3P.xTyy', 'user', '2025-05-16 21:28:18'),
(3, 'Raul', 'ivanvegahdez7@gmail.com', '$2y$10$mqN9entKqsaO8o/4hPzvX.Y9BbIYjBGabaIT7NMAvv39AYdwMquiS', 'user', '2025-05-16 21:31:36'),
(4, 'Pedro', 'yoelhdez96@gmail.com', '$2y$10$oN7gTkmBuIsBcXOl573jdual6RJWFi4I/ZyHlX/i7Pg8.ileoiB4S', 'user', '2025-05-16 21:36:42'),
(5, 'paula', 'paula@gmail.com', '$2y$10$lBlSgqFoFlu4T2RYu2N.gONMYUi3yCr9T0uIXxs9OteHXB/j0ZKRW', 'user', '2025-05-16 21:38:28'),
(6, 'juan', 'juan@gmail.com', '$2y$10$e0LhBANvZB9xdZV/jlsQUeWh/f8U7aBRNCkjTNytSoVLhRp4mLZzS', 'user', '2025-05-16 21:42:29'),
(7, 'oliver', 'oliver@gmail.com', '$2y$10$h7x.Vux3bvedEzg5g/lzyeBTTdvsrLABn86ou2KCPYbWVZwKZ7MHm', 'user', '2025-05-16 21:49:18'),
(8, 'alba', 'alba@gmail.com', '$2y$10$X6FdriymdV0Xj0pKxDACrus.UMAtQJXNudRHKy74sxQecj5sZSoz2', 'user', '2025-05-16 23:19:17'),
(9, 'isaac', 'isaac@gmail.com', '$2y$10$QUpCApp92WolqIAo36g1UeK8H2qq5n.GlL/L24TRDf.8nJYURDR4O', 'user', '2025-05-17 15:57:08'),
(10, 'Iván', 'ivanvegahdez@gmail.com', '$2y$10$MK3SlQSS.7mybSXtq6HxMOm0TZ6itRFsC3DWofYT2gDfKLpGXl/wy', 'user', '2025-05-17 21:13:41'),
(11, 'juanant', 'juanant@gmail.com', '$2y$10$ipuE6cMZaTzXz1T9ihlNpe0LvkBSy7keX4.3tJOAXCvN9YGifovZC', 'user', '2025-05-17 21:19:07'),
(12, 'Iván', '123@gmail.com', '$2y$10$nVOe1DqD2scItVsWO8Hzne1fgnpDACUNlffe2ZM/nJ5wWcthJcE9u', 'user', '2025-05-18 01:09:52'),
(13, 'admin1', 'admin@gmail.com', '$2y$10$FuoHxFk2TAA6Ax.zH44wLOi.OvsVuW0kfrcJOVnr1cMAYd7EhH3wW', 'admin', '2025-05-18 11:58:02'),
(15, 'admin2', 'admin2@gmail.com', '$2y$10$RjE8VfNsfNZVTb5dI2zkyunhCW4p5z5aqcZhTg8OcBBu.Pcy5Zuly', 'admin', '2025-05-18 13:20:06'),
(16, 'Tayri', 'tayravegahdez@gmail.com', '$2y$10$/5w1XtlcwEP.YpZAi3R.FuVyo1W7TdIaWSL5gAGN.EQWIoZ2YRn/2', 'user', '2025-05-18 19:48:25'),
(17, 'Iván', 'ivang@gmail.com', '$2y$10$PII4ZAubPJ9PrStwDRwBE.20M2gb5IhNAZx0mrA1tL5VT8jl0X/Ri', 'user', '2025-05-18 20:08:58'),
(18, 'naya', 'naya@gmail.com', '$2y$10$HdoLESyJ2veauOdfOLKAaeYZXgqRpbzpmdXvkEoZAK2R0tk5.6YiS', 'user', '2025-05-19 13:09:42');


ALTER TABLE carritos
  ADD PRIMARY KEY (id),
  ADD KEY usuario_id (usuario_id);

ALTER TABLE deskmats
  ADD PRIMARY KEY (id);

ALTER TABLE keyboards
  ADD PRIMARY KEY (id);

ALTER TABLE keycaps
  ADD PRIMARY KEY (id);

ALTER TABLE lube
  ADD PRIMARY KEY (id);

ALTER TABLE pcb
  ADD PRIMARY KEY (id);

ALTER TABLE pedidos
  ADD PRIMARY KEY (id),
  ADD KEY usuario_id (usuario_id);

ALTER TABLE silenciadores
  ADD PRIMARY KEY (id);

ALTER TABLE stabilizers
  ADD PRIMARY KEY (id);

ALTER TABLE switches
  ADD PRIMARY KEY (id);

ALTER TABLE tools
  ADD PRIMARY KEY (id);

ALTER TABLE usuarios
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY email (email);


ALTER TABLE carritos
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

ALTER TABLE deskmats
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE keyboards
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE keycaps
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE lube
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE pcb
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE pedidos
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE silenciadores
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE stabilizers
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE switches
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

ALTER TABLE tools
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE usuarios
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;


ALTER TABLE carritos
  ADD CONSTRAINT carritos_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id);

ALTER TABLE pedidos
  ADD CONSTRAINT pedidos_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;