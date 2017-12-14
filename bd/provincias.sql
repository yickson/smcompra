-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-12-2017 a las 11:36:40
-- Versión del servidor: 5.5.56-cll-lve
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `smcompra_test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

DROP TABLE IF EXISTS `provincias`;
CREATE TABLE `provincias` (
  `provincia_id` int(11) NOT NULL,
  `provincia_nombre` varchar(64) NOT NULL,
  `region_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`provincia_id`, `provincia_nombre`, `region_id`) VALUES
(1, 'Arica', 1),
(2, 'Parinacota', 1),
(3, 'Iquique', 2),
(4, 'El Tamarugal', 2),
(5, 'Antofagasta', 3),
(6, 'El Loa', 3),
(7, 'Tocopilla', 3),
(8, 'Chañaral', 4),
(9, 'Copiapó', 4),
(10, 'Huasco', 4),
(11, 'Choapa', 5),
(12, 'Elqui', 5),
(13, 'Limarí', 5),
(14, 'Isla de Pascua', 6),
(15, 'Los Andes', 6),
(16, 'Petorca', 6),
(17, 'Quillota', 6),
(18, 'San Antonio', 6),
(19, 'San Felipe de Aconcagua', 6),
(20, 'Valparaiso', 6),
(21, 'Chacabuco', 7),
(22, 'Cordillera', 7),
(23, 'Maipo', 7),
(24, 'Melipilla', 7),
(25, 'Santiago', 7),
(26, 'Talagante', 7),
(27, 'Cachapoal', 8),
(28, 'Cardenal Caro', 8),
(29, 'Colchagua', 8),
(30, 'Cauquenes', 9),
(31, 'Curicó', 9),
(32, 'Linares', 9),
(33, 'Talca', 9),
(34, 'Arauco', 10),
(35, 'Bio Bío', 10),
(36, 'Concepción', 10),
(37, 'Ñuble', 10),
(38, 'Cautín', 11),
(39, 'Malleco', 11),
(40, 'Valdivia', 12),
(41, 'Ranco', 12),
(42, 'Chiloé', 13),
(43, 'Llanquihue', 13),
(44, 'Osorno', 13),
(45, 'Palena', 13),
(46, 'Aisén', 14),
(47, 'Capitán Prat', 14),
(48, 'Coihaique', 14),
(49, 'General Carrera', 14),
(50, 'Antártica Chilena', 15),
(51, 'Magallanes', 15),
(52, 'Tierra del Fuego', 15),
(53, 'Última Esperanza', 15);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`provincia_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `provincias`
--
ALTER TABLE `provincias`
  MODIFY `provincia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
