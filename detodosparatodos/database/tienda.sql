-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2021 a las 20:49:20
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--
CREATE DATABASE IF NOT EXISTS `tienda` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `tienda`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `gestionarinsersiondinamica`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionarinsersiondinamica` (IN `p_tabla` VARCHAR(100), IN `p_datos` VARCHAR(10000))  BEGIN

DECLARE i  INT;
DECLARE fila  VARCHAR(255);
Declare longitudFila int;

Set i = 1;

loop_filas:LOOP

	Select SPLIT_STR(p_datos, '@', i) into fila;
    select length(fila) into longitudFila;
    
     if (longitudFila = 0) THEN

        leave loop_filas;
        
      else  
    
    	SET @sentenciaSQL = CONCAT( "INSERT INTO ", p_tabla ," VALUES (", fila, ");" );
      PREPARE insertar FROM @sentenciaSQL;
      EXECUTE insertar;
    
    end if;
	
    set i = i + 1;
End loop;
End$$

DROP PROCEDURE IF EXISTS `gestionar_categoria`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_categoria` (IN `p_operacion` INT, IN `p_nombre` INT)  BEGIN 
DECLARE codigo int;
DECLARE cantidad int;

IF(p_operacion=0) THEN
	SELECT COUNT(1) INTO cantidad FROM categorias c WHERE c.nombre=p_nombre;
    IF(cantidad=0) THEN
    	INSERT INTO categorias VALUES(NULL, p_nombre);
    END IF;
ELSE
    SELECT c.id INTO codigo FROM categorias c WHERE c.nombre=p_nombre;
    
    DELETE FROM categorias WHERE id=codigo;

END IF;


END$$

DROP PROCEDURE IF EXISTS `gestionar_ciudad`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_ciudad` (IN `p_operacion` INT, IN `p_nombre` INT)  BEGIN 
DECLARE codigo int;
DECLARE cantidad int;

IF(p_operacion=0) THEN
	SELECT COUNT(1) INTO cantidad FROM ciudades c WHERE c.nombre=p_nombre;
    IF(cantidad=0) THEN
    	INSERT INTO ciudades VALUES(NULL, p_nombre);
    END IF;
ELSE
    SELECT c.id INTO codigo FROM ciudades c WHERE c.nombre=p_nombre;
    
    DELETE FROM ciudades WHERE id=codigo;

END IF;


END$$

DROP PROCEDURE IF EXISTS `gestionar_imagenes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_imagenes` (IN `p_datos` VARCHAR(10000))  BEGIN
Set AUTOCOMMIT = 0;    

START TRANSACTION;

 call gestionarinserciondinamica('imagenes',p_datos);

COMMIT WORK;


END$$

DROP PROCEDURE IF EXISTS `gestionar_pedido`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_pedido` (IN `p_operacion` INT, IN `p_usuario` VARCHAR(15), IN `p_ciudad` INT, IN `p_direccion` VARCHAR(80), IN `p_coste` INT, IN `p_estado` VARCHAR(20), IN `p_datos` INT)  BEGIN
Declare identificador int;
Set AUTOCOMMIT = 0;    

START TRANSACTION;

select s.secuencia into identificador from secuencia_pedido s for update;

insert into pedidos values(identificador, p_usuario, p_ciudad, p_coste, p_estado, sysdate(),CURRENT_TIME());

 select replace(p_datos,'-',identificador) into p_datos;
 call gestionarinserciondinamica('detalles_pedidos',p_datos);

 Update secuencia_pedido s set s.secuencia = s.secuencia + 1;

COMMIT WORK;


END$$

DROP PROCEDURE IF EXISTS `gestionar_producto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_producto` (IN `p_operacion` INT, IN `p_id` VARCHAR(20), IN `p_categoria` INT, IN `p_nombre` VARCHAR(40), IN `p_descripcion` TEXT, IN `p_precio` INT, IN `p_stock` INT, IN `p_oferta` VARCHAR(2))  BEGIN
DECLARE cantidad int;

IF(p_operacion=0) THEN
	SELECT COUNT(1) INTO cantidad FROM productos p WHERE p.id=p_id;
    IF(cantidad=0) THEN
    	INSERT INTO productos VALUES(p_id, p_categoria, p_nombre, p_descripcion, p_precio, p_stock, p_oferta, sysdate());
    ELSE
    	UPDATE productos p SET p.nombre=p_nombre, p.descripcion=p_descripcion, p.precio=p_precio, p.stock=p_stock, p.oferta=p_oferta, p.fecha=sysdate() WHERE p.id=p_id;
    END IF;
ELSE
	DELETE FROM productos WHERE id=p_id;
END IF;

END$$

DROP PROCEDURE IF EXISTS `gestionar_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `gestionar_usuario` (IN `p_operacion` INT, IN `p_id` VARCHAR(15), IN `p_nombre` VARCHAR(40), IN `p_apellidos` VARCHAR(40), IN `p_email` VARCHAR(60), IN `p_password` VARCHAR(140), IN `p_rol` VARCHAR(20), IN `p_imagen` BLOB, IN `p_numero` VARCHAR(14))  BEGIN
DECLARE cantidad int;

IF(p_operacion=0) THEN
	SELECT COUNT(1) INTO cantidad FROM usuarios WHERE usuarios=p_id;
    IF(cantidad=0) THEN
    	INSERT INTO usuarios VALUES(p_id, p_nombres, p_apellidos, p_email, p_password, p_rol, p_imagen, p_numero);
    ELSE
    	UPDATE usuarios u SET u.nombres=p_nombres, u.apellidos=p_apellidos, u.email=p_email, u.password=p_password, u.rol=p_rol, u.imagen=p_imagen, u.numero=p_numero WHERE u.id=p_id;
    END IF;
ELSE
	DELETE FROM usuarios WHERE id=p_id;
END IF;

END$$

--
-- Funciones
--
DROP FUNCTION IF EXISTS `SPLIT_STR`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STR` (`x` VARCHAR(255), `delim` VARCHAR(12), `pos` INT) RETURNS VARCHAR(255) CHARSET utf8mb4 RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos - 1)) + 1), delim, '')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--
-- Creación: 03-12-2021 a las 17:16:08
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--
-- Creación: 03-12-2021 a las 17:15:29
--

DROP TABLE IF EXISTS `ciudades`;
CREATE TABLE `ciudades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedidos`
--
-- Creación: 03-12-2021 a las 17:27:26
--

DROP TABLE IF EXISTS `detalles_pedidos`;
CREATE TABLE `detalles_pedidos` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `unidades` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--
-- Creación: 03-12-2021 a las 17:21:47
--

DROP TABLE IF EXISTS `imagenes`;
CREATE TABLE `imagenes` (
  `producto_id` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `imagen` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--
-- Creación: 03-12-2021 a las 17:25:32
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `ciudad_id` int(11) NOT NULL,
  `direccion` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  `coste` int(11) NOT NULL,
  `estado` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--
-- Creación: 03-12-2021 a las 19:16:05
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(40) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` text COLLATE latin1_spanish_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `oferta` varchar(2) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secuencia_pedido`
--
-- Creación: 03-12-2021 a las 17:23:00
-- Última actualización: 03-12-2021 a las 17:23:13
--

DROP TABLE IF EXISTS `secuencia_pedido`;
CREATE TABLE `secuencia_pedido` (
  `secuencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `secuencia_pedido`
--

INSERT INTO `secuencia_pedido` (`secuencia`) VALUES
(1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
-- Creación: 03-12-2021 a las 17:18:48
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `nombres` varchar(40) COLLATE latin1_spanish_ci NOT NULL,
  `apellidos` varchar(40) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `password` varchar(140) COLLATE latin1_spanish_ci NOT NULL,
  `rol` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `imagen` blob NOT NULL,
  `numero` varchar(14) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_pedidos`
--
ALTER TABLE `detalles_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD KEY `imagenes_productos_fk` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_usuarios_fk` (`usuario_id`),
  ADD KEY `pedidos_ciudades_fk` (`ciudad_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categorias_fk` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_productos_fk` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ciudades_fk` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_usuarios_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categorias_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
