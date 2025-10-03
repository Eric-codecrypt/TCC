-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/10/2025 às 12:28
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
-- Estrutura para tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `id` int(11) NOT NULL,
  `name` varchar(37) DEFAULT NULL,
  `series` tinyint(4) DEFAULT NULL,
  `repetitions` tinyint(4) DEFAULT NULL,
  `group` varchar(9) DEFAULT NULL,
  `demo` varchar(41) DEFAULT NULL,
  `thumb` varchar(41) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exercicios`
--

INSERT INTO `exercicios` (`id`, `name`, `series`, `repetitions`, `group`, `demo`, `thumb`) VALUES
(1, 'Supino inclinado com barra', 4, 12, 'peito', 'supino_inclinado_com_barra.gif', 'supino_inclinado_com_barra.png'),
(2, 'Crucifixo reto', 3, 12, 'peito', 'crucifixo_reto.gif', 'crucifixo_reto.png'),
(3, 'Supino reto com barra', 3, 12, 'peito', 'supino_reto_com_barra.gif', 'supino_reto_com_barra.png'),
(4, 'Francês deitado com halteres', 3, 12, 'tríceps', 'frances_deitado_com_halteres.gif', 'frances_deitado_com_halteres.png'),
(5, 'Corda Cross', 4, 12, 'tríceps', 'corda_cross.gif', 'corda_cross.png'),
(6, 'Barra Cross', 3, 12, 'tríceps', 'barra_cross.gif', 'barra_cross.png'),
(7, 'Tríceps testa', 4, 12, 'tríceps', 'triceps_testa.gif', 'triceps_testa.png'),
(8, 'Levantamento terra', 3, 12, 'costas', 'levantamento_terra.gif', 'levantamento_terra.png'),
(9, 'Pulley frontal', 3, 12, 'costas', 'pulley_frontal.gif', 'pulley_frontal.png'),
(10, 'Pulley atrás', 4, 12, 'costas', 'pulley_atras.gif', 'pulley_atras.png'),
(11, 'Remada baixa', 4, 12, 'costas', 'remada_baixa.gif', 'remada_baixa.png'),
(12, 'Serrote', 4, 12, 'costas', 'serrote.gif', 'serrote.png'),
(13, 'Rosca alternada com banco inclinado', 4, 12, 'bíceps', 'rosca_alternada_com_banco_inclinado.gif', 'rosca_alternada_com_banco_inclinado.png'),
(14, 'Rosca Scott barra w', 4, 12, 'bíceps', 'rosca_scott_barra_w.gif', 'rosca_scott_barra_w.png'),
(15, 'Rosca direta barra reta', 3, 12, 'bíceps', 'rosca_direta_barra_reta.gif', 'rosca_direta_barra_reta.png'),
(16, 'Martelo em pé', 3, 12, 'bíceps', 'martelo_em_pe.gif', 'martelo_em_pe.png'),
(17, 'Rosca punho', 4, 12, 'antebraço', 'rosca_punho.gif', 'rosca_punho.png'),
(18, 'Leg press 45 graus', 4, 12, 'pernas', 'leg_press_45_graus.gif', 'leg_press_45_graus.png'),
(19, 'Extensor de pernas', 4, 12, 'pernas', 'extensor_de_pernas.gif', 'extensor_de_pernas.png'),
(20, 'Abdutora', 4, 12, 'pernas', 'abdutora.gif', 'abdutora.png'),
(21, 'Stiff', 4, 12, 'pernas', 'stiff.gif', 'stiff.png'),
(22, 'Neck Press', 4, 10, 'ombro', 'neck-press.gif', 'neck-press.png'),
(23, 'Desenvolvimento maquina', 3, 10, 'ombro', 'desenvolvimento_maquina.gif', 'desenvolvimento_maquina.png'),
(24, 'Elevação lateral com halteres sentado', 4, 10, 'ombro', 'elevacao_lateral_com_halteres_sentado.gif', 'elevacao_lateral_com_halteres_sentado.png'),
(25, 'Encolhimento com halteres', 4, 10, 'trapézio', 'encolhimento_com_halteres.gif', 'encolhimento_com_halteres.png'),
(26, 'Encolhimento com barra', 4, 10, 'trapézio', 'encolhimento_com_barra.gif', 'encolhimento_com_barra.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensalidades`
--

CREATE TABLE `mensalidades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor_cobrado` decimal(10,2) NOT NULL,
  `status_pagamento` enum('Pendente','Pago','Atrasado') NOT NULL DEFAULT 'Pendente',
  `data_pagamento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `mensalidades`
--

INSERT INTO `mensalidades` (`id`, `user_id`, `data_vencimento`, `valor_cobrado`, `status_pagamento`, `data_pagamento`) VALUES
(5, 0, '2025-11-01', 190.00, 'Pendente', '2025-08-10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id` int(11) NOT NULL,
  `nome_plano` varchar(100) NOT NULL,
  `valor_mensal` decimal(10,2) NOT NULL,
  `valor_plano_antes_desconto` decimal(5,2) NOT NULL,
  `valor_adesao` decimal(5,2) NOT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id`, `nome_plano`, `valor_mensal`, `valor_plano_antes_desconto`, `valor_adesao`, `descricao`) VALUES
(1, 'Plano Essencial', 59.90, 79.00, 29.90, 'Ideal para quem está começando. Inclui acesso livre à academia em horário reduzido (das 6h às 17h), com utilização de equipamentos básicos e vestiários. Não possui plano de treino personalizado, mas garante todo o suporte inicial da equipe para adaptação ao ambiente e desenvolvimento da rotina de treinos.'),
(2, 'Plano Plus', 89.90, 99.00, 49.90, 'Perfeito para quem busca mais flexibilidade. Acesso ilimitado em qualquer horário de funcionamento, utilização de todos os equipamentos, acompanhamento inicial com instrutor e um plano de treino personalizado. Inclui vestiários completos e participação em aulas coletivas selecionadas.'),
(3, 'Plano Premium', 129.90, 149.00, 69.90, 'A escolha ideal para quem quer o máximo de resultados. Acesso total à academia em qualquer horário, treinos personalizados atualizados mensalmente, acompanhamento próximo dos instrutores e prioridade em aulas coletivas. Também inclui área exclusiva de musculação e funcional, além de benefícios extras como descontos em suplementos e eventos da academia.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `data_inscricao_plano` date DEFAULT NULL,
  `dia_vencimento_plano` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_de_user` enum('trainer','cliente','admin') CHARACTER SET utf8 COLLATE utf8_unicode_520_nopad_ci DEFAULT 'cliente',
  `body_fat` decimal(4,2) DEFAULT NULL COMMENT 'apenas clientes',
  `peso` decimal(5,2) DEFAULT NULL COMMENT 'apenas clientes',
  `ficha_id` int(11) DEFAULT NULL COMMENT 'apenas clientes',
  `anotacoes_trainer` text DEFAULT NULL COMMENT 'apenas clientes',
  `info_treinamento` text DEFAULT NULL COMMENT 'apenas clientes',
  `trainer_id` int(11) DEFAULT NULL COMMENT 'apenas clientes',
  `mensalidade_id` int(11) DEFAULT NULL COMMENT 'apenas clientes',
  `plano_id` int(11) DEFAULT NULL COMMENT 'apenas clientes',
  `salario` decimal(8,2) DEFAULT NULL COMMENT 'apenas trainers',
  `endereco` varchar(255) DEFAULT NULL COMMENT 'apenas trainers',
  `CREF` int(11) DEFAULT NULL COMMENT 'apenas trainers'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `nome_completo`, `email`, `celular`, `CPF`, `data_inscricao_plano`, `dia_vencimento_plano`, `password`, `created_at`, `tipo_de_user`, `body_fat`, `peso`, `ficha_id`, `anotacoes_trainer`, `info_treinamento`, `trainer_id`, `mensalidade_id`, `plano_id`, `salario`, `endereco`, `CREF`) VALUES
(0, 'Admin da Silva', 'Silva@Admin.com', '12312312312312312312', '', NULL, NULL, '$2y$10$Ik.ABbHfaMRkfOwDaIeQSOpdWjg68p5Vv7XCqR04.8nTnG7ZdEhP6', '2025-08-29 11:14:05', 'admin', 0.00, 0.00, 0, '', NULL, 0, 0, 0, 0.00, '', 0),
(1, 'Trainer da Silva', 'Silva@Trainer.com', NULL, '', NULL, NULL, '$2y$10$Ik.ABbHfaMRkfOwDaIeQSOpdWjg68p5Vv7XCqR04.8nTnG7ZdEhP6', '2025-08-15 17:22:33', 'trainer', 0.00, 0.00, 0, '', NULL, 0, 0, 0, 0.00, '', 0),
(3, '', 'jonatas@docente.br', NULL, '', NULL, NULL, '$2y$10$vj7b20L2UvHyROuqheh13u0uRfA72nGRT7K8KTa9/QFqpag6nEFTm', '2025-08-20 14:21:29', 'admin', 0.00, 0.00, 0, '', NULL, 0, 0, 0, 0.00, '', 0),
(4, '', '2@GMAIL.COM', NULL, '', NULL, NULL, '$2y$10$og5VgnGy2jMsKW33mTSoJeiIlvFJgugW.4OtRtFQ6qcpdu021.3jO', '2025-08-27 16:54:26', '', 0.00, 0.00, 0, '', NULL, 0, 0, 0, 0.00, '', 0),
(6, 'Thiago', 'Thiago@gmail.com', '189910210120', '1029129192', NULL, NULL, '$2y$10$Ik.ABbHfaMRkfOwDaIeQSOpdWjg68p5Vv7XCqR04.8nTnG7ZdEhP6', '2025-09-10 11:30:50', 'cliente', NULL, NULL, NULL, NULL, 'Idade: 19 anos<br>Altura: 1.95 m<br>Peso: 95 kg<br>Objetivos: Emagrecimento, Definição muscular<br>Disponibilidade: 6x por semana<br>Nunca treinou antes<br>Não possui lesão ou limitação física<br>Não usa medicamentos atualmente<br>', NULL, NULL, 2, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensalidades`
--
ALTER TABLE `mensalidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserID` (`user_id`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `mensalidades`
--
ALTER TABLE `mensalidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `mensalidades`
--
ALTER TABLE `mensalidades`
  ADD CONSTRAINT `mensalidades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
