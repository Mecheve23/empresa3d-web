-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2026 a las 18:41:41
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
-- Base de datos: `db_empresa3d`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_cedula` varchar(20) DEFAULT NULL,
  `nombre_apellido` varchar(100) DEFAULT NULL,
  `num_ruc` varchar(20) DEFAULT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `celular` varchar(20) NOT NULL,
  `ubicacion` varchar(150) NOT NULL,
  `direccion` text NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `archivo_tecnico` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cotizaciones`
--

INSERT INTO `cotizaciones` (`id`, `tipo_documento`, `num_cedula`, `nombre_apellido`, `num_ruc`, `razon_social`, `celular`, `ubicacion`, `direccion`, `asunto`, `nombre`, `email`, `mensaje`, `archivo_tecnico`, `fecha`) VALUES
(19, 'cedula', '0953657855', 'Marcos Echeverria', '', NULL, '0991366695', 'Guayas/Guayaquil/Ecuador', 'Urbanizacion Santa Leonor Mz 4 V12', '', NULL, NULL, 'Servicio de Diseño de piezas - esto es un detalle de la solicitud ', '1793033501_6adf851d1d75a.pdf', '2026-10-26 16:51:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_info`
--

CREATE TABLE `empresa_info` (
  `id` int(11) NOT NULL,
  `quienes_somos` text NOT NULL,
  `mision` text NOT NULL,
  `vision` text NOT NULL,
  `google_maps` text NOT NULL,
  `logo` varchar(255) DEFAULT 'LogoDYF.png',
  `historia` text DEFAULT NULL,
  `politica_calidad` text DEFAULT NULL,
  `banner_titulo` varchar(255) DEFAULT 'Impresión 3D de Calidad para tus Ideas',
  `banner_subtitulo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa_info`
--

INSERT INTO `empresa_info` (`id`, `quienes_somos`, `mision`, `vision`, `google_maps`, `logo`, `historia`, `politica_calidad`, `banner_titulo`, `banner_subtitulo`) VALUES
(1, 'Somos una empresa dedicada a la fabricación digital y soluciones avanzadas en impresión 3D. Nos especializamos en transformar ideas, planos y modelos tridimensionales en objetos físicos tangibles con alta precisión, ofreciendo un servicio ágil y personalizado tanto para proyectos particulares como para el sector industrial.', 'Brindar servicios de impresión y diseño 3D de alta calidad, ofreciendo soluciones eficientes, precisas y personalizadas que impulsen la innovación de nuestros clientes, optimizando tiempos de entrega y garantizando acabados profesionales en cada pieza.', 'Brindar servicios de impresión y diseño 3D de alta calidad, ofreciendo soluciones eficientes, precisas y personalizadas que impulsen la innovación de nuestros clientes, optimizando tiempos de entrega y garantizando acabados profesionales en cada pieza.', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.914242699863!2d-79.886!3d-2.189!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMsKwMTEnMjAuNCJTIDc5wrA1MycxMi4wIlc!5e0!3m2!1ses!2sec!4v1650000000000', 'logo_1784742290.png', 'Fundada con la visión de acercar las tecnologías de fabricación aditiva al mercado local, comenzamos como un taller técnico especializado en prototipado rápido. Con el tiempo y la confianza de nuestros clientes, hemos expandido nuestra capacidad de producción para abarcar desde piezas mecánicas de alta exigencia hasta elementos decorativos y funcionales a medida.', 'Nos comprometemos a garantizar la máxima precisión y resistencia en cada una de nuestras impresiones. Para ello, utilizamos insumos de alta categoría, realizamos un mantenimiento preventivo riguroso a nuestros equipos y sometemos cada pieza a un control de calidad técnico antes de su entrega final.', 'Impresión 3D de Calidad para tus Ideas ', 'Fabricamos e imprimimos productos personalizados para clientes industriales y particulares. ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_proyectos`
--

CREATE TABLE `imagenes_proyectos` (
  `id` int(11) NOT NULL,
  `proyecto_id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_proyectos`
--

INSERT INTO `imagenes_proyectos` (`id`, `proyecto_id`, `imagen`) VALUES
(9, 50, '1786063473_0_Protector de Polea.jpg'),
(10, 50, '1786063473_1_Protector polea.jpg'),
(11, 51, '1786114138_0_contenedor.jpg'),
(12, 52, '1786551166_0_IMAGEN2.jpeg'),
(13, 52, '1786551166_1_IMAGEN3.jpeg'),
(14, 53, '1786551269_0_IMAGEN1.jpeg'),
(15, 54, '1786551309_0_IMAGEN5.jpeg'),
(16, 55, '1786551387_0_IMAGEN5.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `fecha_registro`) VALUES
(15, 'Retrovisor de carro ', 'esto es un retrovisor', 24.99, 'prod_1786550298.jpeg', '2026-08-12 15:58:18'),
(19, 'Protector de Polea', 'esto es una polea para un objeto', 60.00, 'prod_1786550834.jpg', '2026-08-12 16:07:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `titulo`, `descripcion`, `imagen`, `fecha`) VALUES
(50, 'Protector de Polea', 'Esto es un protector ', '1786063473_0_Protector de Polea.jpg', '2026-08-07 00:44:33'),
(51, 'Contenedor', 'esto es un contenedor blanco ', '1786114138_0_contenedor.jpg', '2026-08-07 14:48:58'),
(52, 'proyecto 1 y 2 ', 'esto son proyectos ', '1786551166_0_IMAGEN2.jpeg', '2026-08-12 16:12:46'),
(53, 'proyecto 3 ', 'esto es un proyecto 3 ', '1786551269_0_IMAGEN1.jpeg', '2026-08-12 16:14:29'),
(54, 'proyecto 4 ', 'esto es un proyecto 4 ', '1786551309_0_IMAGEN5.jpeg', '2026-08-12 16:15:09'),
(55, 'proyecto 5', 'esto es un proyecto 5 ', '1786551387_0_IMAGEN5.jpeg', '2026-08-12 16:16:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT 'usuario.png',
  `email` varchar(255) DEFAULT 'marcos.echeverria.chicaiza@gmail.com',
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `foto_perfil`, `email`, `token_recuperacion`, `nombre_completo`) VALUES
(1, 'admin', 'admin1234', 'usuario.png', 'administracion@di-fabrica.com', NULL, 'Christian Eduardo'),
(9, 'juan_Lopez', '7488e331b8b64e5794da3fa4eb10ad5d', 'usuario.png', 'marcos.echeverria.chicaiza@gmail.com', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa_info`
--
ALTER TABLE `empresa_info`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes_proyectos`
--
ALTER TABLE `imagenes_proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyecto_id` (`proyecto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `empresa_info`
--
ALTER TABLE `empresa_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagenes_proyectos`
--
ALTER TABLE `imagenes_proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes_proyectos`
--
ALTER TABLE `imagenes_proyectos`
  ADD CONSTRAINT `imagenes_proyectos_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
