-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 01-Dez-2022 às 00:34
-- Versão do servidor: 10.5.18-MariaDB-cll-lve
-- versão do PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `libertet_amigosecreto`
--
CREATE DATABASE IF NOT EXISTS `libertet_amigosecreto` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `libertet_amigosecreto`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sorteio`
--

CREATE TABLE `sorteio` (
  `ID` int(11) NOT NULL,
  `PARTICIPANTE` varchar(200) NOT NULL,
  `EMAIL` varchar(200) NOT NULL,
  `SORTEIO_NOME` varchar(200) NOT NULL,
  `SORTEIO_EMAIL` varchar(200) NOT NULL,
  `HASH` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `sorteio`
--
ALTER TABLE `sorteio`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `sorteio`
--
ALTER TABLE `sorteio`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
