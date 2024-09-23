CREATE DATABASE IF NOT EXISTS escuela;

USE escuela;

CREATE TABLE IF NOT EXISTS `materias` (
  `Materia_id` tinyint(4) unsigned NOT NULL,
  `Materia` varchar(10) NOT NULL,
  `Prof_ID` smallint(5) unsigned NOT NULL,
  `anio_id` int(3) unsigned NOT NULL,
  `division_id` tinyint(3) unsigned NOT NULL,
  `grupo_id` tinyint(3) unsigned NOT NULL,
  `turno_id` tinyint(3) unsigned NOT NULL,
  `horas` tinyint(4) unsigned NOT NULL,
  `recurso_id` tinyint(3) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `materias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE IF NOT EXISTS `profesores` (
`id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `dni` int(8) unsigned DEFAULT NULL,
  `escuela` varchar(100) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `profesores`
--
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
 ADD PRIMARY KEY (`Materia_id`), ADD KEY `Prof_ID` (`Prof_ID`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;