-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/08/2025 às 18:23
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
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensalidades`
--

CREATE TABLE `mensalidades` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DataVencimento` date NOT NULL,
  `ValorCobrado` decimal(10,2) NOT NULL,
  `StatusPagamento` enum('Pendente','Pago','Atrasado') NOT NULL DEFAULT 'Pendente',
  `DataPagamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `ID` int(11) NOT NULL,
  `NomeDoPlano` varchar(100) NOT NULL,
  `ValorMensal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`ID`, `NomeDoPlano`, `ValorMensal`) VALUES
(1, 'Plano Musculação', 99.90),
(2, 'Plano Crossfit', 149.90),
(3, 'Plano Full Access', 189.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `NomeCompleto` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `Telefone` varchar(20) DEFAULT NULL,
  `DataInscricao` date DEFAULT NULL,
  `DiaVencimento` int(11) DEFAULT NULL,
  `PlanoID` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin','instrutor') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `NomeCompleto`, `email`, `Telefone`, `DataInscricao`, `DiaVencimento`, `PlanoID`, `password`, `created_at`, `role`) VALUES
(1, 'Nox', 'Eric de souza palma', 'ericsouzapalma123@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$JiCpDEPiRZMCBl8cYTVrZOr1Fb9rxuRS8HQaXPmQqL.edciKhxPSG', '2025-08-15 17:22:33', 'admin'),
(3, 'jon', NULL, 'jonatas@docente.br', NULL, NULL, NULL, NULL, '$2y$10$vj7b20L2UvHyROuqheh13u0uRfA72nGRT7K8KTa9/QFqpag6nEFTm', '2025-08-20 14:21:29', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `mensalidades`
--
ALTER TABLE `mensalidades`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `PlanoID` (`PlanoID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `mensalidades`
--
ALTER TABLE `mensalidades`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `mensalidades`
--
ALTER TABLE `mensalidades`
  ADD CONSTRAINT `mensalidades_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`PlanoID`) REFERENCES `planos` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
