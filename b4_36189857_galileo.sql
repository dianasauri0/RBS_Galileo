-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql312.byethost4.com
-- Tiempo de generación: 15-08-2024 a las 18:53:54
-- Versión del servidor: 10.6.19-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `b4_36189857_galileo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE `pendientes` (
  `pendiente_id` int(11) UNSIGNED NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `estado` varchar(10) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(11) UNSIGNED NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `descripcion`, `precio`) VALUES
(1, 'Air Jordan 1 Zoom Cmft 2 \"Muslin\"', '12999.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `contrasenia` varchar(100) NOT NULL,
  `rol` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `contrasenia`, `rol`) VALUES
(19, 'a', '$2y$10$AKHM03qVUX3OvnuupFbiSe2if2ht2ZY75WnHVQQcpB41Y5umQtCam', 'admin'),
(20, 'b', '$2y$10$6KCJcIpE9MH76uS.xfxSNOrEikWzoLuciXDJUSY3BiP0csVBOLFKG', 'usuario'),
(21, 'a1', '$2y$10$zmGCIrcZyOzYHkcGyhVYBuMPyGX/jUtgBhwTMBhcwWBK3Xw8rzjL2', 'usuario'),
(22, 'que', '$2y$10$rjiAadW/6nqZYpOZY9CwB.5.wNYdQGnlctFxDmdwIkEbojUkJUFs.', 'usuario'),
(23, 'quee', '$2y$10$8zq3HTsUwYQp5j0ENDvsH.2VeamgR./CadamMZIC09DwkSXGsan4a', 'usuario'),
(24, 'usuario', '$2y$10$3xW/cWnOmDrdDsCV0Mm5euH6g1cwFaTIny12EZ14PLNDap1qoG2HS', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `order_id` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`pendiente_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `orden_id` (`order_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `id_producto` (`producto_id`),
  ADD KEY `id_producto_2` (`producto_id`),
  ADD KEY `id_producto_3` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `pendiente_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
