-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-03-2020 a las 02:53:24
-- Versión del servidor: 8.0.19
-- Versión de PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdsaloneventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int UNSIGNED NOT NULL,
  `cliente_nombre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_apellido` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `editado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int UNSIGNED NOT NULL,
  `evento_imagen` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `evento_fecha` date NOT NULL,
  `evento_precio` decimal(10,0) NOT NULL,
  `tipos_id_tipo` tinyint UNSIGNED NOT NULL,
  `clientes_id_cliente` int UNSIGNED NOT NULL,
  `editado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` tinyint UNSIGNED NOT NULL,
  `rol_nombre` varchar(32) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol_nombre`) VALUES
(1, 'administrador'),
(2, 'auxiliar'),
(3, 'consultor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_eventos`
--

CREATE TABLE `tipos_eventos` (
  `id_tipo_evento` tinyint UNSIGNED NOT NULL,
  `tipo_nombre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_imagen` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_precio_base` decimal(10,0) NOT NULL,
  `editado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `usuario_nombre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_apellido` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_password` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_numero` smallint UNSIGNED NOT NULL,
  `roles_id_rol` tinyint UNSIGNED NOT NULL,
  `editado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario_nombre`, `usuario_apellido`, `usuario_password`, `usuario_numero`, `roles_id_rol`, `editado`) VALUES
(1, 'admin', 'admin', '$2y$12$bi2eZObZCiPth7LKOd4Hpu1VOv5apG/EWc0TrYvL/Q81O850OhzHe', 1111, 1, '2020-03-14 19:50:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cliente_email_UNIQUE` (`cliente_email`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`,`tipos_id_tipo`,`clientes_id_cliente`),
  ADD UNIQUE KEY `evento_fecha_UNIQUE` (`evento_fecha`),
  ADD KEY `fk_eventos_tipos_eventos1_idx` (`tipos_id_tipo`),
  ADD KEY `fk_eventos_clientes1_idx` (`clientes_id_cliente`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `roles_nombre_UNIQUE` (`rol_nombre`);

--
-- Indices de la tabla `tipos_eventos`
--
ALTER TABLE `tipos_eventos`
  ADD PRIMARY KEY (`id_tipo_evento`),
  ADD UNIQUE KEY `tipo_nombre_UNIQUE` (`tipo_nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`,`roles_id_rol`),
  ADD UNIQUE KEY `usuario_numero_UNIQUE` (`usuario_numero`),
  ADD KEY `fk_usuarios_roles_idx` (`roles_id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_eventos`
--
ALTER TABLE `tipos_eventos`
  MODIFY `id_tipo_evento` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_clientes1` FOREIGN KEY (`clientes_id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_eventos_tipos_eventos1` FOREIGN KEY (`tipos_id_tipo`) REFERENCES `tipos_eventos` (`id_tipo_evento`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`roles_id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
