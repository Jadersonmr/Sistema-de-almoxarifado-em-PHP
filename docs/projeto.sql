-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tempo de Geração: Ago 25, 2011 as 11:24 AM
-- Versão do Servidor: 5.0.27
-- Versão do PHP: 5.2.1
-- 
-- Banco de Dados: `projeto`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `defeitos`
-- 

CREATE TABLE `defeitos` (
  `codDefeito` int(11) NOT NULL auto_increment,
  `quantFerramenta` int(11) NOT NULL,
  `dataDefeito` date NOT NULL,
  `codFerramenta` int(11) NOT NULL,
  `observacaoDefeito` varchar(45) collate utf8_bin default NULL,
  PRIMARY KEY  (`codDefeito`),
  KEY `fk_defeitos_ferramentas1` (`codFerramenta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `defeitos`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `emprestimolaboratorios`
-- 

CREATE TABLE `emprestimolaboratorios` (
  `codEmpLaboratorio` int(11) NOT NULL auto_increment,
  `dataEmprestimo` datetime NOT NULL,
  `codUsuario` int(11) default NULL,
  `observacaoEmprestimo` varchar(45) collate utf8_bin default NULL,
  `nomeLocatario` varchar(255) collate utf8_bin default NULL,
  `snDevolvido` enum('Sim','Não') collate utf8_bin NOT NULL,
  `dataDevolucao` datetime NOT NULL,
  PRIMARY KEY  (`codEmpLaboratorio`),
  KEY `fk_laboratorios_usuario1` (`codUsuario`,`nomeLocatario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=69 ;

-- 
-- Extraindo dados da tabela `emprestimolaboratorios`
-- 

INSERT INTO `emprestimolaboratorios` (`codEmpLaboratorio`, `dataEmprestimo`, `codUsuario`, `observacaoEmprestimo`, `nomeLocatario`, `snDevolvido`, `dataDevolucao`) VALUES 
(67, '2011-08-03 17:21:31', 63, 0x616161616161616161, 0x6a61646572736f6e, 0x4ec3a36f, '0000-00-00 00:00:00'),
(68, '2011-08-05 11:08:07', 83, '', 0x6a61646572736f6e, 0x4ec3a36f, '0000-00-00 00:00:00');

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `emprestimosferramentas`
-- 

CREATE TABLE `emprestimosferramentas` (
  `codEmprestimo` int(11) NOT NULL auto_increment,
  `dataEmprestimo` datetime NOT NULL,
  `observacaoEmprestimo` varchar(100) collate utf8_bin default NULL,
  `codUsuario` int(11) NOT NULL,
  `nomeLocatario` varchar(255) collate utf8_bin NOT NULL,
  `snDevolvido` enum('Sim','Não') collate utf8_bin NOT NULL,
  `dataDevolucao` datetime NOT NULL,
  PRIMARY KEY  (`codEmprestimo`),
  KEY `fk_emprestimosferramentas_usuario1` (`codUsuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=140 ;

-- 
-- Extraindo dados da tabela `emprestimosferramentas`
-- 

INSERT INTO `emprestimosferramentas` (`codEmprestimo`, `dataEmprestimo`, `observacaoEmprestimo`, `codUsuario`, `nomeLocatario`, `snDevolvido`, `dataDevolucao`) VALUES 
(138, '2011-08-03 16:50:39', 0x61616161616161, 63, 0x6a61646572736f6e, 0x4ec3a36f, '0000-00-00 00:00:00'),
(139, '2011-08-05 11:08:15', '', 83, 0x6a61646572736f6e, 0x53696d, '2011-08-16 16:12:01');

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ferramentas`
-- 

CREATE TABLE `ferramentas` (
  `codFerramenta` int(11) NOT NULL auto_increment,
  `nomeFerramenta` varchar(30) collate utf8_bin NOT NULL,
  `quantFerramenta` int(11) NOT NULL,
  `quantDisponivel` int(11) default NULL,
  `obsFerramenta` varchar(200) collate utf8_bin default NULL,
  `dataEntrada` datetime default NULL,
  `codUsuario` int(11) default NULL,
  `codLaboratorio` int(11) default NULL,
  PRIMARY KEY  (`codFerramenta`),
  KEY `fk_ferramentas_usuario1` (`codUsuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=57 ;

-- 
-- Extraindo dados da tabela `ferramentas`
-- 

INSERT INTO `ferramentas` (`codFerramenta`, `nomeFerramenta`, `quantFerramenta`, `quantDisponivel`, `obsFerramenta`, `dataEntrada`, `codUsuario`, `codLaboratorio`) VALUES 
(22, 0x657371756164726f, 10, 8, 0x3131, '2007-06-26 19:32:52', 63, 0),
(20, 0x6d617274656c6f, 15, 15, '', '2007-06-26 19:29:35', 63, 11),
(19, 0x616c6963617465, 41, 27, 0x6161616161, '2007-06-26 19:29:24', 63, 11),
(45, 0x63686176652064652066656e6461, 30, 21, '', '2011-05-30 13:05:56', 63, 11);

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `itememprestimoferramenta`
-- 

CREATE TABLE `itememprestimoferramenta` (
  `codItemEmprestimo` int(11) NOT NULL auto_increment,
  `quantEmprestada` int(11) NOT NULL,
  `quantHistorico` int(11) NOT NULL,
  `codFerramenta` int(11) NOT NULL,
  `codEmprestimo` int(11) NOT NULL,
  PRIMARY KEY  (`codItemEmprestimo`),
  KEY `fk_itemEmprestimoFerramenta_ferramentas1` (`codFerramenta`),
  KEY `fk_itemEmprestimoFerramenta_emprestimosferramentas1` (`codEmprestimo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=218 ;

-- 
-- Extraindo dados da tabela `itememprestimoferramenta`
-- 

INSERT INTO `itememprestimoferramenta` (`codItemEmprestimo`, `quantEmprestada`, `quantHistorico`, `codFerramenta`, `codEmprestimo`) VALUES 
(212, 14, 14, 19, 138),
(213, 9, 9, 45, 138),
(214, 2, 2, 22, 138),
(215, 13, 13, 19, 139),
(216, 12, 12, 45, 139),
(217, 4, 4, 20, 139);

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `itensemplaboratorio`
-- 

CREATE TABLE `itensemplaboratorio` (
  `codItensLaboratorio` int(11) NOT NULL auto_increment,
  `codLaboratorio` int(11) NOT NULL,
  `codEmpLaboratorio` int(11) NOT NULL,
  PRIMARY KEY  (`codItensLaboratorio`),
  KEY `fk_itensEmpLaboratorio_laboratorios1` (`codLaboratorio`),
  KEY `fk_itensEmpLaboratorio_emprestimoLaboratorios1` (`codEmpLaboratorio`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=97 ;

-- 
-- Extraindo dados da tabela `itensemplaboratorio`
-- 

INSERT INTO `itensemplaboratorio` (`codItensLaboratorio`, `codLaboratorio`, `codEmpLaboratorio`) VALUES 
(94, 13, 67),
(95, 14, 67),
(96, 11, 68);

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `laboratorios`
-- 

CREATE TABLE `laboratorios` (
  `codLaboratorio` int(11) NOT NULL auto_increment,
  `nomeLaboratorio` varchar(45) collate utf8_bin NOT NULL,
  `numeroLaboratorio` varchar(3) collate utf8_bin NOT NULL,
  `observacaoLaboratorio` varchar(255) collate utf8_bin NOT NULL,
  `sn_emprestado` enum('Sim','Não') collate utf8_bin NOT NULL,
  PRIMARY KEY  (`codLaboratorio`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=25 ;

-- 
-- Extraindo dados da tabela `laboratorios`
-- 

INSERT INTO `laboratorios` (`codLaboratorio`, `nomeLaboratorio`, `numeroLaboratorio`, `observacaoLaboratorio`, `sn_emprestado`) VALUES 
(11, 0x656c6574726f7465636e696361, 0x3334, '', 0x53696d),
(10, 0x6d6563616e696361, 0x3231, 0x6161616161, 0x4ec3a36f),
(13, 0x656c6574726f6e696361, 0x3432, '', 0x53696d),
(14, 0x696e666f726d6174696361, 0x3635, '', 0x53696d);

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuario`
-- 

CREATE TABLE `usuario` (
  `codUsuario` int(11) NOT NULL auto_increment,
  `nomeUsuario` varchar(50) collate utf8_bin NOT NULL,
  `permissaoUsuario` enum('Admin','Requisitante') collate utf8_bin default NULL,
  `enderecoUsuario` varchar(50) collate utf8_bin default NULL,
  `telefoneUsuario` varchar(45) collate utf8_bin default NULL,
  `emailUsuario` varchar(45) collate utf8_bin default NULL,
  `senha` varchar(40) collate utf8_bin NOT NULL,
  `observacaoUsuario` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`codUsuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=84 ;

-- 
-- Extraindo dados da tabela `usuario`
-- 

INSERT INTO `usuario` (`codUsuario`, `nomeUsuario`, `permissaoUsuario`, `enderecoUsuario`, `telefoneUsuario`, `emailUsuario`, `senha`, `observacaoUsuario`) VALUES 
(63, 0x6a61646572736f6e, 0x41646d696e, 0x6b6d3337, 0x28313129313131312d31313131, 0x6a61646572736f6e2e6b6d333740626f6c2e636f6d, 0x3230326362393632616335393037356239363462303731353264323334623730, ''),
(83, 0x416161, 0x5265717569736974616e7465, 0x6c6167756e61, 0x28313129313131312d31313131, 0x616161406161612e636f6d, 0x6434316438636439386630306232303465393830303939386563663834323765, '');
