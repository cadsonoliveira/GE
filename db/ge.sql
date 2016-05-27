-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `easysge`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `anamnese`
--

CREATE TABLE IF NOT EXISTS `anamnese` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_anamnese` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `boa_saude` char(1) DEFAULT NULL,
  `boa_saude_porque` varchar(255) DEFAULT NULL,
  `visitas_freq_medico` char(1) DEFAULT NULL,
  `visitas_freq_medico_motivo` varchar(255) DEFAULT NULL,
  `medicacao_rotina` char(1) DEFAULT NULL,
  `medicacao_rotina_qual` varchar(255) DEFAULT NULL,
  `alergia` char(1) DEFAULT NULL,
  `alergia_de_que` varchar(255) DEFAULT NULL,
  `doencas_previas` char(1) DEFAULT NULL,
  `doencas_previas_qual` varchar(255) DEFAULT NULL,
  `cirurgias_previas` char(1) DEFAULT NULL,
  `cirurgias_previas_qual` varchar(255) DEFAULT NULL,
  `hepatite` char(1) DEFAULT NULL,
  `diabetis` char(1) DEFAULT NULL,
  `febre_reumatica` char(1) DEFAULT NULL,
  `doencas_arterial` char(1) DEFAULT NULL,
  `pressao_arterial` char(1) DEFAULT NULL,
  `acid_fraturas_odont` char(1) DEFAULT NULL,
  `acid_fraturas_odont_qual` varchar(255) DEFAULT NULL,
  `dific_abert_boca` char(1) DEFAULT NULL,
  `muita_salivacao` char(1) DEFAULT NULL,
  `ansia_vomito` char(1) DEFAULT NULL,
  `dor_nas_costas` char(1) DEFAULT NULL,
  `traumatismo_dentario` char(1) DEFAULT NULL,
  `recomendacao_medica` varchar(255) DEFAULT NULL,
  `restricao_medicamentos` char(1) DEFAULT NULL,
  `restricao_medicamentos_qual` varchar(255) DEFAULT NULL,
  `visita_regular_dent` char(1) DEFAULT NULL,
  `ultimo_trat_odont` int(11) DEFAULT NULL,
  `inf_adicional_importante` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_anamnese`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `anamnese`
--

INSERT INTO `anamnese` (`instancia`,`id_anamnese`, `id_pessoa`, `boa_saude`, `boa_saude_porque`, `visitas_freq_medico`, `visitas_freq_medico_motivo`, `medicacao_rotina`, `medicacao_rotina_qual`, `alergia`, `alergia_de_que`, `doencas_previas`, `doencas_previas_qual`, `cirurgias_previas`, `cirurgias_previas_qual`, `hepatite`, `diabetis`, `febre_reumatica`, `doencas_arterial`, `pressao_arterial`, `acid_fraturas_odont`, `acid_fraturas_odont_qual`, `dific_abert_boca`, `muita_salivacao`, `ansia_vomito`, `dor_nas_costas`, `traumatismo_dentario`, `recomendacao_medica`, `restricao_medicamentos`, `restricao_medicamentos_qual`, `visita_regular_dent`, `ultimo_trat_odont`, `inf_adicional_importante`) VALUES
(0,5, 58, 'S', '', 'N', '', 'N', '', 'N', '', '', '', '', '', 'N', 'N', 'N', 'N', 'N', '', '', 'N', '', '', '', '', '', '', '', '', NULL, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `arcada_dentaria`
--

CREATE TABLE IF NOT EXISTS `arcada_dentaria` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_arcada_dentaria` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `dente` varchar(2) DEFAULT NULL,
  `obs` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_arcada_dentaria`, instancia),
  KEY `id_ad_pessoa` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------





-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracao`
--

CREATE TABLE IF NOT EXISTS `configuracao` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_configuracao` int(11) NOT NULL AUTO_INCREMENT,
  `id_especialidade` int(11) NOT NULL,
  `texto_index` longtext,
  PRIMARY KEY (`id_configuracao`, instancia),
  KEY `id_especialidade` (`id_especialidade`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `configuracao`
--

INSERT INTO `configuracao` (`instancia`,`id_configuracao`, `id_especialidade`, `texto_index`) VALUES
(0,1, 1, '<p>Consultório Online é uma iniciativa que visa ofercer um sistema adquado para gestão de especialidades médicas em consultórios a clínicas, com simplicidade, agilidade e baixo custo. Você se ocupa dos pacientes e nós cuidados de sua gestão e mantemos seus dados seguros por no mínimo 20 anos. Com backups diários</p>\n\n<p>Consultório Online te permite testar, experimentar e avaliar o sistema por 3 meses gratuitamente. Nossa meta é atendê-lo e acessorá-lo.</p>\n\n<p>Com muita ousadia, buscamos revolucionar o mercado oferecendo conectividade com SMS, WhatsApp, estando disponível em qualquer computador do mundo sem necessidade de uma única instalação. Você pode utilizar o Consultório Online em diversas clínicas, sem misturar pacientes de uma clínica com outra.\nNossa meta é que você esteja conosco nos próximos 20 anos, criando mais opções, incorporar tecnologias de ponta e inovando na velocidade do mundo digital, para que você possa obter um contínuo crescimento, mas acima de tudo, poder perceber o elevado grau de satisfação de seus pacientes. Que venha o futuro! Nós estaremos lá!</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `consulta`
--

CREATE TABLE IF NOT EXISTS `consulta` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_consulta` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_consulta`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `consulta`
--

INSERT INTO `consulta` (`instancia`,`id_consulta`, `data`, `hora`, `duracao`, `status`) VALUES
(0,1, '2013-05-31', '11:00:00', 120, 0),
(0,2, '2013-05-31', '09:00:00', 30, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contato`
--

CREATE TABLE IF NOT EXISTS `contato` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_contato` int(11) NOT NULL AUTO_INCREMENT,
  `tel_fixo` varchar(14) DEFAULT NULL,
  `tel_comercial` varchar(14) DEFAULT NULL,
  `tel_celular` varchar(16) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_contato`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=133 ;

--
-- Extraindo dados da tabela `contato`
--

INSERT INTO `contato` (`instancia`,`id_contato`, `tel_fixo`, `tel_comercial`, `tel_celular`, `email`) VALUES
(0,113, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'teste@teste.com'),
(0,114, '', '', '', ''),
(0,115, '', '', '', ''),
(0,116, '', '', '', ''),
(0,117, '', '', '', ''),
(0,118, '', '', '', ''),
(0,119, '', '', '', ''),
(0,120, '', '', '', ''),
(0,121, '', '', '', ''),
(0,122, '', '', '', ''),
(0,123, '', '', '', ''),
(0,124, '', '', '', ''),
(0,125, '', '', '', ''),
(0,126, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'plano@plano.com'),
(0,127, '', '', '', ''),
(0,128, '', '', '', ''),
(0,129, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'teste@teste.com'),
(0,130, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'teste@teste.com'),
(0,131, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'teste@teste.com'),
(0,132, '(31) 1234-5678', '(21) 1234-5678', '(11) 1-2345-6789', 'usuario@usuario.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissional_encaminhador`
--

CREATE TABLE IF NOT EXISTS `profissional_encaminhador` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_profissional_encaminhador` int(11) NOT NULL AUTO_INCREMENT,
  `CRO` varchar(20) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `id_contato` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_profissional_encaminhador`,instancia),
  KEY `id_de_contato` (`id_contato`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE IF NOT EXISTS `documentos` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(11) NOT NULL,
  `observacoes` varchar(1000) DEFAULT NULL,
  `receituario` varchar(1000) DEFAULT NULL,
  `imagem_caminho` varchar(200) DEFAULT NULL,
  `data_documento` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_documento`,instancia),
  KEY `id_do_pessoa` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

CREATE TABLE IF NOT EXISTS `endereco` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_endereco` int(11) NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(45) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(45) DEFAULT NULL,
  `bairro` varchar(45) DEFAULT NULL,
  `cidade` varchar(45) DEFAULT NULL,
  `sigla_estado` varchar(2) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`id_endereco`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Extraindo dados da tabela `endereco`
--

INSERT INTO `endereco` (`instancia`,`id_endereco`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `sigla_estado`, `cep`) VALUES
(0,68, 'RUA DOIS', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,71, 'RUA DOIS', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,72, 'RUA DOIS', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,73, 'RUA ', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,74, 'RUA ', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,75, 'RUA ', '1111111111', 'CASA', 'JARDIM', 'CATHARINES', 'ES', '31710-000'),
(0,76, '', '', '', '', '', 'MG', ''),
(0,77, 'RUA UM', '1111', 'CASA', 'CENTRO', 'BELO HORIZONTE', 'MG', '31710-690'),
(0,78, '', '', '', '', '', 'MG', ''),
(0,79, '', '', '', '', '', 'MG', ''),
(0,80, '', '', '', '', '', 'MG', ''),
(0,81, '', '', '', '', '', 'MG', ''),
(0,82, '', '', '', '', '', 'MG', ''),
(0,83, '', '', '', '', '', 'MG', ''),
(0,84, '', '', '', '', '', 'MG', ''),
(0,85, '', '', '', '', '', 'MG', ''),
(0,86, '', '', '', '', '', 'MG', ''),
(0,87, '', '', '', '', '', 'MG', ''),
(0,88, '', '', '', '', '', 'MG', ''),
(0,89, '', '', '', '', '', 'MG', ''),
(0,90, 'RUA UM', '1111', 'CASA', 'CENTRO', 'BELO HORIZONTE', 'MG', '31710-690'),
(0,91, 'RUA UM', '1111', 'CASA', 'CENTRO', 'BELO HORIZONTE', 'MG', '31710-690'),
(0,92, 'RUA UM', '1111', 'CASA', 'CENTRO', 'BELO HORIZONTE', 'MG', '31710-690'),
(0,93, 'RUA UM', '1111', 'CASA', 'CENTRO', 'BELO HORIZONTE', 'MG', '31710-690');

-- --------------------------------------------------------

--
-- Estrutura da tabela `er_consulta_tratamento`
--

CREATE TABLE IF NOT EXISTS `er_consulta_tratamento` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_consulta_tratamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_consulta` int(11) NOT NULL,
  `id_tratamento` int(11) NOT NULL,
  PRIMARY KEY (`id_consulta_tratamento`,instancia),
  KEY `id_erct_consulta` (`id_consulta`),
  KEY `id_erct_tratamento` (`id_tratamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `er_consulta_tratamento`
--

INSERT INTO `er_consulta_tratamento` ((`instancia`,`id_consulta_tratamento`, `id_consulta`, `id_tratamento`) VALUES
(0,1, 1, 55),
(0,2, 2, 63);

-- --------------------------------------------------------

--
-- Estrutura da tabela `er_especialidade_profissional`
--

CREATE TABLE IF NOT EXISTS `er_especialidade_profissional` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_especialidade_dentista` int(11) NOT NULL AUTO_INCREMENT,
  `id_profissional_encaminhador` int(11) NOT NULL,
  `id_especialidade` int(11) NOT NULL,
  PRIMARY KEY (`id_especialidade_dentista`,instancia),
  KEY `id_ered_profissional_encaminhador` (`id_profissional_encaminhador`),
  KEY `id_ered_especialidade` (`id_especialidade_dentista`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `especialidade`
--

CREATE TABLE IF NOT EXISTS `especialidade` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_especialidade` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  PRIMARY KEY (`id_especialidade`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Extraindo dados da tabela `especialidade`
--

INSERT INTO `especialidade` (`instancia`,`id_especialidade`, `descricao`) VALUES
(0,1, 'Clinico Geral'),
(0,2, 'Ortopodia'),
(0,4, 'Pediatria'),
(0,5, 'Otorrinolaringologia'),
(0,6, 'Endocrinologia'),
(0,7, 'Pediatria'),
(0,8, 'Gastroenterologia'),
(0,9, 'Dermatologia'),
(0,10, 'Dentistica'),
(0,11, 'Angiologia'),
(0,12, 'Cardiologista'),
(0,13, 'Ginecologia'),
(0,14, 'Geriatria'),
(0,15, 'Cardiologista'),
(0,16, 'Pneumologia'),
(0,17, 'Psiquistria');


-- --------------------------------------------------------

--
-- Estrutura da tabela `especialidade_usuario`
--

CREATE TABLE IF NOT EXISTS `especialidade_usuario` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_pessoa` int(11) NOT NULL,
  `id_especialidade` int(11) NOT NULL,
  PRIMARY KEY (`id_pessoa`,`id_especialidade`,instancia),
  KEY `id_especialidade` (`id_especialidade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `especialidade_usuario`
--

INSERT INTO `especialidade_usuario` (`instancia`,`id_pessoa`, `id_especialidade`) VALUES
(0,57, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem`
--

CREATE TABLE IF NOT EXISTS `imagem` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_imagem` int(11) NOT NULL AUTO_INCREMENT,
  `id_tratamento` int(11) DEFAULT NULL,
  `indice` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `caminho` varchar(50) NOT NULL,
  `obs` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_imagem`,instancia),
  KEY `id_img_tratamento` (`id_tratamento`),
  KEY `id_imagem` (`id_imagem`),
  KEY `id_tratamento` (`id_tratamento`),
  KEY `id_imagem_2` (`id_imagem`),
  KEY `id_tratamento_2` (`id_tratamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Extraindo dados da tabela `imagem`
--

INSERT INTO `imagem` (`instancia`,`id_imagem`, `id_tratamento`, `indice`, `data`, `caminho`, `obs`) VALUES
(0,30, 55, 2, '2013-05-22', '2205201316052169.jpg', 'Consulta padrão'),
(0,31, 55, 2, '2013-05-22', '2205201316052163.jpg', 'Consulta padrão'),
(0,32, 55, 2, '2013-05-22', '2205201316052152.jpg', 'Consulta padrão'),
(0,33, 55, 2, '2013-05-22', '2205201316052161.jpg', 'Consulta padrão'),
(0,34, 55, 2, '2013-05-22', '2205201316052173.jpg', 'Consulta padrão'),
(0,35, 55, 2, '2013-05-22', '2205201316052122.jpg', 'Consulta padrão'),
(0,36, 55, 2, '2013-05-22', '2205201316052159.jpg', 'Consulta padrão'),
(0,37, 55, 2, '2013-05-22', '2205201316052142.jpg', 'Consulta padrão'),
(0,38, 55, 2, '2013-05-22', '2205201316052189.jpg', 'Consulta padrão'),
(0,39, 55, 2, '2013-05-22', '2205201316052123.jpg', 'Consulta padrão'),
(0,40, 55, 2, '2013-05-22', '2205201318053985.jpg', 'Consulta padrão'),
(0,41, 55, 2, '2013-05-22', '2205201318053974.jpg', 'Consulta padrão'),
(0,42, 55, 2, '2013-05-22', '2205201318053987.jpg', 'Consulta padrão'),
(0,43, 55, 2, '2013-05-22', '2205201318053991.jpg', 'Consulta padrão'),
(0,44, 55, 2, '2013-05-22', '2205201318053954.jpg', 'Consulta padrão'),
(0,45, 55, 2, '2013-05-22', '2205201318053922.jpg', 'Consulta padrão'),
(0,46, 55, 2, '2013-05-22', '2205201318053922.jpg', 'Consulta padrão'),
(0,47, 55, 2, '2013-05-22', '2205201318053944.jpg', 'Consulta padrão'),
(0,48, 55, 2, '2013-05-22', '2205201318053925.jpg', 'Consulta padrão'),
(0,49, 55, 2, '2013-05-22', '2205201318053991.jpg', 'Consulta padrão'),
(0,50, 55, 2, '2013-05-22', '2205201318053966.jpg', 'Consulta padrão'),
(0,51, 55, 2, '2013-05-22', '2205201318053928.jpg', 'Consulta padrão'),
(0,52, 55, 2, '2013-05-22', '2205201318053997.jpg', 'Consulta padrão'),
(0,53, 55, 2, '2013-05-22', '2205201318053937.jpg', 'Consulta padrão'),
(0,54, 55, 2, '2013-05-22', '2205201318053990.jpg', 'Consulta padrão'),
(0,55, 55, 2, '2013-05-22', '2205201318053978.jpg', 'Consulta padrão'),
(0,56, 55, 2, '2013-05-22', '2205201318053914.jpg', 'Consulta padrão'),
(0,57, 55, 2, '2013-05-22', '2205201318053972.jpg', 'Consulta padrão'),
(0,58, 55, 2, '2013-05-22', '2205201318053914.jpg', 'Consulta padrão'),
(0,59, 55, 2, '2013-05-22', '2205201318053976.jpg', 'Consulta padrão'),
(0,60, 55, 2, '2013-05-22', '2205201318053977.jpg', 'Consulta padrão'),
(0,61, 55, 2, '2013-05-22', '2205201318053954.jpg', 'Consulta padrão'),
(0,62, 55, 2, '2013-05-22', '2205201318054012.jpg', 'Consulta padrão'),
(0,63, 55, 2, '2013-05-22', '2205201318054057.jpg', 'Consulta padrão'),
(0,64, 55, 2, '2013-05-22', '2205201318054028.jpg', 'Consulta padrão');

-- --------------------------------------------------------

--
-- Estrutura da tabela `img_parametros`
--

CREATE TABLE IF NOT EXISTS `img_parametros` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_img_parametros` int(11) NOT NULL AUTO_INCREMENT,
  `id_imagem` int(11) NOT NULL,
  PRIMARY KEY (`id_img_parametros`,instancia),
  KEY `id_imgpar_imagem` (`id_imagem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `match_code`
--

CREATE TABLE IF NOT EXISTS `match_code` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_match_code` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(80) NOT NULL,
  `tipo` enum('TRATAMENTO','RETRATAMENTO') NOT NULL,
  `id_especialidade` int(11) NOT NULL,
  PRIMARY KEY (`id_match_code`),
  KEY `id_especialidade` (`id_especialidade`,instancia)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `match_code`
--

INSERT INTO `match_code` (`instancia`,`id_match_code`, `descricao`, `tipo`, `id_especialidade`) VALUES
(0,1, 'RIZOGENESE INCOMPLETA', 'TRATAMENTO', 1),
(0,2, 'ABCESSO AGUDO OU CRÔNICO', 'TRATAMENTO', 1),
(0,3, 'FINALIDADE PROTÉTICA', 'TRATAMENTO', 1),
(0,4, 'NECROSE PULPAR', 'TRATAMENTO', 1),
(0,5, 'DIAGNÓ\0STICO DE TRINCA OU FRATURA', 'TRATAMENTO', 1),
(0,6, 'PROCEDIMENTO PRÉ PROTÓTICO', 'TRATAMENTO', 1),
(0,7, 'DESCONTAMINAÇÃO POR PDT', 'TRATAMENTO', 1),
(0,8, 'REABSORÇÃO INTERNA OU EXTERNA', 'TRATAMENTO', 1),
(0,9, 'ESPAÇO PRA PINO', 'TRATAMENTO', 1),
(0,10, 'REMOÇÃO DE NÚCLEO METÁLICO', 'RETRATAMENTO', 1),
(0,11, 'REMOÇÃO DE NÚ\0CLEO DE FIBRA', 'RETRATAMENTO', 1),
(0,12, 'REMOÇÃ\0O DE CONE DE AG', 'RETRATAMENTO', 1),
(0,13, 'REMOÇÃ\0O DE LIMA FRATURADA', 'RETRATAMENTO', 1),
(0,14, 'VEDAMENTO DE PERFURAÇÃ\0O - MTA', 'RETRATAMENTO', 1),
(0,15, 'VEDAMENTO DE PERFURAÇÃO - MEMBRANA DE COLÁ\0GENO', 'RETRATAMENTO', 1),
(0,16, 'VEDAMENTO DE PERFURAÇÃ\0O - OUTRO', 'RETRATAMENTO', 1),
(0,17, 'REMOÇÃO DE COROA PROTÓ\0TICA', 'RETRATAMENTO', 1),
(0,18, 'CIRURGIA PERIAPICAL - CURETAGEM', 'RETRATAMENTO', 1),
(0,19, 'CIRURGIA PERIAPICAL - APICETOMIA', 'RETRATAMENTO', 1),
(0,20, 'ESPAÇ\0O PRA PINO', 'RETRATAMENTO', 1),
(0,21, 'CONSULTA', 'TRATAMENTO', 1),
(0,22, 'PROCEDIMENTO 01', 'TRATAMENTO', 1),
(0,23, 'PROCEDIMENTO 02', 'TRATAMENTO', 1),
(0,24, 'PROCEDIMENTO 03', 'TRATAMENTO', 1),
(0,25, 'PROCEDIMENTO 04', 'TRATAMENTO', 1),
(0,26, 'PROCEDIMENTO 05', 'TRATAMENTO', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente`
--

CREATE TABLE IF NOT EXISTS `paciente` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `id_plano_saude` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `obs` varchar(500) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `num_carteira_convenio` varchar(20) DEFAULT NULL,
  `validade_carteira` varchar(10) DEFAULT NULL,
  `caminho_foto` varchar(50) DEFAULT NULL,
  `id_dentista_encaminhador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`,instancia),
  KEY `id_p_dentista_encaminhador` (`id_dentista_encaminhador`),
  KEY `id_p_plano_saude` (`id_plano_saude`),
  KEY `id_p_pessoa` (`id_pessoa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Extraindo dados da tabela `paciente`
--

INSERT INTO `paciente` (`instancia`,`id_pessoa`, `id_plano_saude`, `status`, `obs`, `data_cadastro`, `num_carteira_convenio`, `validade_carteira`, `caminho_foto`, `id_dentista_encaminhador`) VALUES
(0,58, 1, 0, '', '2013-05-22', '', '', '2205201316052539.jpg', NULL),
(0,59, NULL, 0, '', '2013-05-22', '', '', '2205201316053358.jpg', NULL),
(0,60, NULL, 0, '', '2013-05-22', '', '', '2305201315055911.jpg', NULL),
(0,61, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,62, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,63, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,64, NULL, 0, '', '2013-05-23', '', '', '2305201309054621.jpg', NULL),
(0,65, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,66, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,67, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,68, NULL, 0, '', '2013-05-23', '', '', '', NULL),
(0,69, NULL, 0, '', '2013-05-23', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

CREATE TABLE IF NOT EXISTS `pessoa` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `rg` varchar(15) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `id_contato` int(11) DEFAULT NULL,
  `id_endereco` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`,instancia),
  KEY `id_pe_telefone` (`id_contato`),
  KEY `id_pe_endereco` (`id_endereco`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Extraindo dados da tabela `pessoa`
--

INSERT INTO `pessoa` (`instancia`,`id_pessoa`, `nome`, `sexo`, `rg`, `cpf`, `data_nasc`, `id_contato`, `id_endereco`) VALUES
(0,57, 'ADMINISTRADOR', 'M', '00-00.000.000', '812.333.931-38', '1990-06-05', 132, 93),
(0,58, 'PACIENTE 01 - PACIENTE PARA TESTE', 'M', '', '', '1990-06-05', 114, 78),
(0,59, 'PACIENTE 02', 'M', '', '', '1990-06-05', 115, 79),
(0,60, 'PACIENTE 03', '', '', '', '1990-06-05', 116, 80),
(0,61, 'PACIENTE 04', 'F', '', '', '1990-06-05', 117, 81),
(0,62, 'PACIENTE 05', 'M', '', '', '1987-05-08', 118, 82),
(0,63, 'PACIENTE 06', 'F', '', '', '1987-05-08', 119, 83),
(0,64, 'PACIENTE 07', 'M', '', '', '1987-05-08', 120, 84),
(0,65, 'PACIENTE 08', '', '', '', '1987-05-08', 121, 85),
(0,66, 'PACIENTE 09', '', '', '', '1987-05-08', 122, 86);

-- --------------------------------------------------------

--
-- Estrutura da tabela `planosaude`
--

CREATE TABLE IF NOT EXISTS `planosaude` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_plano_saude` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `id_contato` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_plano_saude`,instancia),
  KEY `id_ps_contato` (`id_contato`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `planosaude`
--

INSERT INTO `planosaude` (`instancia`,`id_plano_saude`, `codigo`, `nome`, `id_contato`) VALUES
(0,1, '', 'Particular', 126),
(0,2, '', 'Bradesco Saúde', 127),
(0,3, '', 'Unimed', 128);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tratamento`
--

CREATE TABLE IF NOT EXISTS `tratamento` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_tratamento` int(11) NOT NULL AUTO_INCREMENT,
  `dente` varchar(3) NOT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `data_inic` date NOT NULL,
  `data_term` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id_match_code` int(11) NOT NULL,
  `descricao` varchar(500) DEFAULT NULL,
  `sucesso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tratamento`,instancia),
  KEY `id_trat_pessoa` (`id_pessoa`),
  KEY `id_match_code` (`id_match_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Extraindo dados da tabela `tratamento`
--

INSERT INTO `tratamento` (`instancia`,`id_tratamento`, `dente`, `id_pessoa`, `data_inic`, `data_term`, `status`, `id_match_code`, `descricao`, `sucesso`) VALUES
(0,55, '100', 58, '2013-05-22', NULL, 0, 21, 'Consulta padrão', 2),
(0,56, '100', 59, '2013-05-22', NULL, 0, 21, 'Consulta padrão', 2),
(0,57, '100', 60, '2013-05-22', NULL, 0, 21, 'Consulta padrão', 2),
(0,58, '100', 61, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,59, '100', 62, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,60, '100', 63, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,61, '100', 64, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,62, '100', 65, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,63, '100', 66, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,64, '100', 67, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,65, '100', 68, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2),
(0,66, '100', 69, '2013-05-23', NULL, 0, 21, 'Consulta padrão', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  instancia 	int(11)	NOT NULL DEFAULT 0,
  `id_pessoa` int(11) NOT NULL,
  `login` varchar(15) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL,
  `tipo_acesso` varchar(20) DEFAULT NULL,
  `cro` varchar(20) NOT NULL,
  `ultimo_acesso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pessoa`,instancia),
  KEY `id_pessoa` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`instancia`,`id_pessoa`, `login`, `senha`, `tipo_acesso`, `cro`, `ultimo_acesso`) VALUES
(0,57, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Dentista', '01234567890123456789', '2013-05-23 20:54:49');



--
-- Tabela para configurar os preços e porcentagens dos atendimentos
--
	CREATE TABLE
    atendimento (
	    id 					INT NOT NULL,
        instancia 	int(11)	NOT NULL DEFAULT 0,
		usuario 			INT NOT NULL,
		preco				DECIMAL(5,2),
		comissao_clinica	DECIMAL(2,2),
        PRIMARY KEY(instancia, id, usuario )
    );
	
	
	  
--
-- Tabela para controle e gestão de SMS
--
	CREATE TABLE 
    sms (
	    id 					INT AUTO_INCREMENT, -- codigo de envio unico do sms
         instancia 	int(11)	NOT NULL DEFAULT 0,
		paciente 			INT NOT NULL,
        horario_consulta 	DATETIME  NOT NULL,
		cabecalho 			varchar(30),
		mensagem 			varchar(100),
		status_SMS		    int NOT NULL,
		resposta			varchar(10),
		PRIMARY KEY(id, instancia, paciente, horario_consulta)
       );
	
	
	
--
-- Tabela para configuração do SMS no gateway contratado
--
	CREATE TABLE IF NOT EXISTS 
    sms_config (
	    instancia 	int(11)	NOT NULL DEFAULT 0,
	    id 						INT NOT NULL,
		string_conexao 			varchar(150),
		add cabecalho 			varchar(50),
        PRIMARY KEY(instancia, id )
    );
  
  
  CREATE TABLE IF NOT EXISTS 
    `sms_status` (
     instancia 	int(11)	NOT NULL DEFAULT 0,
	`id_status` varchar(11) NOT NULL,
	`descricao` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `sms_status` (`instancia`,`id_status`, `descricao`) VALUES
(0,'OK', 'OK');
INSERT INTO `sms_status` (`instancia`,`id_status`, `descricao`) VALUES
(0,'ERRO', 'ERRO');


--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `arcada_dentaria`
--
ALTER TABLE `arcada_dentaria`
  ADD CONSTRAINT `id_ad_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `paciente` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `configuracao`
--
ALTER TABLE `configuracao`
  ADD CONSTRAINT `configuracao_ibfk_1` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade` (`id_especialidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `dentista_encaminhador`
--
ALTER TABLE `dentista_encaminhador`
  ADD CONSTRAINT `id_de_contato` FOREIGN KEY (`id_contato`) REFERENCES `contato` (`id_contato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `id_do_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `paciente` (`id_pessoa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `er_consulta_tratamento`
--
ALTER TABLE `er_consulta_tratamento`
  ADD CONSTRAINT `id_erct_consulta` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_erct_tratamento` FOREIGN KEY (`id_tratamento`) REFERENCES `tratamento` (`id_tratamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `er_especialidade_dentista`
--
ALTER TABLE `er_especialidade_dentista`
  ADD CONSTRAINT `id_ered_dentista_encaminhador` FOREIGN KEY (`id_dentista_encaminhador`) REFERENCES `dentista_encaminhador` (`id_dentista_encaminhador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_ered_especialidade` FOREIGN KEY (`id_especialidade_dentista`) REFERENCES `especialidade` (`id_especialidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `especialidade_usuario`
--
ALTER TABLE `especialidade_usuario`
  ADD CONSTRAINT `especialidade_usuario_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `usuario` (`id_pessoa`),
  ADD CONSTRAINT `especialidade_usuario_ibfk_2` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade` (`id_especialidade`);

--
-- Restrições para a tabela `imagem`
--
ALTER TABLE `imagem`
  ADD CONSTRAINT `id_img_tratamento` FOREIGN KEY (`id_tratamento`) REFERENCES `tratamento` (`id_tratamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `img_parametros`
--
ALTER TABLE `img_parametros`
  ADD CONSTRAINT `id_imgpar_imagem` FOREIGN KEY (`id_imagem`) REFERENCES `imagem` (`id_imagem`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para a tabela `match_code`
--
ALTER TABLE `match_code`
  ADD CONSTRAINT `match_code_ibfk_1` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade` (`id_especialidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `id_p_dentista_encaminhador` FOREIGN KEY (`id_dentista_encaminhador`) REFERENCES `dentista_encaminhador` (`id_dentista_encaminhador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_p_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_p_plano_saude` FOREIGN KEY (`id_plano_saude`) REFERENCES `planosaude` (`id_plano_saude`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD CONSTRAINT `id_pe_endereco` FOREIGN KEY (`id_endereco`) REFERENCES `endereco` (`id_endereco`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_pe_telefone` FOREIGN KEY (`id_contato`) REFERENCES `contato` (`id_contato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `planosaude`
--
ALTER TABLE `planosaude`
  ADD CONSTRAINT `id_ps_contato` FOREIGN KEY (`id_contato`) REFERENCES `contato` (`id_contato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `tratamento`
--
ALTER TABLE `tratamento`
  ADD CONSTRAINT `id_trat_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `paciente` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tratamento_ibfk_1` FOREIGN KEY (`id_match_code`) REFERENCES `match_code` (`id_match_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `id_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;










--
-- Tabela para configurar os horários que os médicos estão disponíveis
--
	
	CREATE TABLE
    horario (
	    id 			INT NOT NULL,
        instancia 	int(11)	NOT NULL DEFAULT 0,
		usuario 	INT NOT NULL,
        dia			varchar(10), -- usado para dias da semana/ talvez converter para numeros
		dt_dia 		DATE, 	
		inicio		TIME,       -- horario do inicio da disponibilidade
		fim 		TIME,       -- horario fim da disponibilidade
		duracao_consulta time,  -- duracao media da consulta a ser cadastrada
        PRIMARY KEY(instancia, id, usuario )
    );
	
	
	
	--
-- Tabela para criar os templates de prontuários
--
	
	CREATE TABLE
    TemplateProntuario (
	    id 			 INT NOT NULL AUTO_INCREMENT,
        instancia 	 int(11) NOT NULL DEFAULT 0,
		id_usuario 	INT NOT NULL,   -- usuário que criou o template
		id_especialidade	INT NOT NULL,   -- usuário que criou o template
		nomeTemplate  varchar(30),
		medicamento   varchar(40),
		concentracao  varchar(20),
		quantidade    varchar(20),
		instrucao  	  varchar(150),
		observacao    varchar(20),
		compartilhamento TINYINT,	
        PRIMARY KEY(instancia, id, id_usuario, nomeTemplate)
    );
	
	CREATE INDEX idx_TEMPLATE_PRONTUARIO ON TemplateProntuario(id_especialidade, compartilhamento );
	

