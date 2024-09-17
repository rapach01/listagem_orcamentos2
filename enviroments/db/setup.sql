-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/09/2024 às 21:57
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `setup`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `estruturas`
--

CREATE TABLE `estruturas` (
  `id_estrutura` int(11) NOT NULL,
  `descricao_estrutura` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estruturas`
--

INSERT INTO `estruturas` (`id_estrutura`, `descricao_estrutura`) VALUES
(1, 'n1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estrutura_materiais`
--

CREATE TABLE `estrutura_materiais` (
  `id_estrutura_material` int(11) NOT NULL,
  `id_estrutura` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estrutura_materiais`
--

INSERT INTO `estrutura_materiais` (`id_estrutura_material`, `id_estrutura`, `id_material`, `quantidade`) VALUES
(1, 1, 1, 12),
(2, 1, 2, 2),
(3, 1, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais`
--

CREATE TABLE `materiais` (
  `id_material` int(11) NOT NULL,
  `nome_material` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `materiais`
--

INSERT INTO `materiais` (`id_material`, `nome_material`) VALUES
(1, 'parafuso'),
(2, 'cruzeta'),
(3, 'mao francesa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `postes`
--

CREATE TABLE `postes` (
  `id` int(11) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `tipo` int(11) NOT NULL,
  `parafuso_nivel_1` varchar(255) NOT NULL,
  `parafuso_nivel_2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `postes`
--

INSERT INTO `postes` (`id`, `codigo`, `tipo`, `parafuso_nivel_1`, `parafuso_nivel_2`) VALUES
(1, '', 10, '3', '4'),
(2, 'teste', 10, '3', '4');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `estruturas`
--
ALTER TABLE `estruturas`
  ADD PRIMARY KEY (`id_estrutura`);

--
-- Índices de tabela `estrutura_materiais`
--
ALTER TABLE `estrutura_materiais`
  ADD PRIMARY KEY (`id_estrutura_material`),
  ADD KEY `id_estrutura` (`id_estrutura`),
  ADD KEY `id_material` (`id_material`);

--
-- Índices de tabela `materiais`
--
ALTER TABLE `materiais`
  ADD PRIMARY KEY (`id_material`);

--
-- Índices de tabela `postes`
--
ALTER TABLE `postes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estruturas`
--
ALTER TABLE `estruturas`
  MODIFY `id_estrutura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estrutura_materiais`
--
ALTER TABLE `estrutura_materiais`
  MODIFY `id_estrutura_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `materiais`
--
ALTER TABLE `materiais`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `postes`
--
ALTER TABLE `postes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `estrutura_materiais`
--
ALTER TABLE `estrutura_materiais`
  ADD CONSTRAINT `estrutura_materiais_ibfk_1` FOREIGN KEY (`id_estrutura`) REFERENCES `estruturas` (`id_estrutura`),
  ADD CONSTRAINT `estrutura_materiais_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `materiais` (`id_material`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
