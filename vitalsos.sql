-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-01-2025 a las 23:03:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vitalsos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emergencias`
--

CREATE TABLE `emergencias` (
                               `ID_Emergencia` int(11) NOT NULL,
                               `ID_Usuario` int(11) NOT NULL,
                               `nombre_usuario` varchar(255) NOT NULL,
                               `mail_usuario` varchar(255) NOT NULL,
                               `telefono_usuario` int(11) NOT NULL,
                               `ubicacion` varchar(255) NOT NULL,
                               `policia` tinyint(1) NOT NULL,
                               `bomberos` tinyint(1) NOT NULL,
                               `ambulancia` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
                            `ID_Usuario` int(11) NOT NULL,
                            `nombre` varchar(255) NOT NULL,
                            `contraseña` varchar(255) NOT NULL,
                            `mail` varchar(255) NOT NULL,
                            `telefono_emergencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `emergencias`
--
ALTER TABLE `emergencias`
    ADD PRIMARY KEY (`ID_Emergencia`),
  ADD KEY `ID_Usuario` (`ID_Usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
    ADD PRIMARY KEY (`ID_Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `emergencias`
--
ALTER TABLE `emergencias`
    MODIFY `ID_Emergencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
    MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `emergencias`
--
ALTER TABLE `emergencias`
    ADD CONSTRAINT `emergencias_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuarios` (`ID_Usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;