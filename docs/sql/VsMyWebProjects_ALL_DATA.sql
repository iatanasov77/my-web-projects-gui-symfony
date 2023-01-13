-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 11, 2023 at 07:10 PM
-- Server version: 8.0.26
-- PHP Version: 8.2.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `VsMyWebProjects_BACKUP`
--

--
-- Dumping data for table `VSAPP_Applications`
--

INSERT INTO `VSAPP_Applications` (`id`, `title`, `hostname`, `code`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'My Web Projects', 'myprojects.lh', 'my-web-projects', 1, '2022-04-09 21:43:03', NULL);

--
-- Dumping data for table `VSAPP_Settings`
--

INSERT INTO `VSAPP_Settings` (`id`, `maintenanceMode`, `theme`, `application_id`, `maintenance_page_id`) VALUES
(1, 0, NULL, NULL, NULL),
(2, 0, NULL, 1, NULL);

--
-- Dumping data for table `VSWPG_Categories`
--

INSERT INTO `VSWPG_Categories` (`id`, `name`) VALUES
(2, 'VS OpenSource Projects'),
(3, 'Third-Party Projects'),
(4, 'VS Enterprise Projects');

--
-- Dumping data for table `VSWPG_PhpbrewExtensions`
--

INSERT INTO `VSWPG_PhpbrewExtensions` (`id`, `name`, `description`, `github_repo`, `branch`) VALUES
(1, 'cassandra', 'DataStax PHP Extension', 'datastax/php-driver', 'master');

--
-- Dumping data for table `VSWPG_Projects`
--

INSERT INTO `VSWPG_Projects` (`id`, `category_id`, `name`, `description`, `source_type`, `repository`, `branch`, `project_root`, `install_manual`, `predefinedType`, `predefinedTypeParams`, `url`) VALUES
(1, 2, 'VS SalaryJ', NULL, 'git', 'https://gitlab.com/iatanasov77/salary-j-2', 'master', '/projects/VS_SalaryJ', 'NO INSTALL MANUAL', NULL, NULL, NULL),
(2, 3, 'SULU', NULL, NULL, 'https://github.com/sulu/skeleton', 'master', '/projects/SULU', 'READ <a href=\"http://docs.sulu.io/en/latest/book/getting-started.html\" target=\"__blank\">THIS</a>', NULL, NULL, NULL),
(3, 3, 'Magento', NULL, NULL, 'NO', 'NO', '/projects/Magento', '<p><a href=\"https://devdocs.magento.com/guides/v2.3/install-gde/composer.html\" target=\"__blank\">Install Magento 2.3 with Composer</a></p>', NULL, NULL, NULL),
(5, 3, 'Sylius', NULL, NULL, 'none', 'none', '/projects/Sylius', '<p><a href=\"https://docs.sylius.com/en/1.6/getting-started-with-sylius/installation.html\" target=\"__blank\">Read Installation Manual</a> Version: 1.6</p>', NULL, NULL, NULL),
(6, 4, 'VankoSoft.Org', NULL, NULL, 'https://gitlab.com/iatanasov77/vankosoft.org.git', 'develop', '/projects/VankoSoft.Org', NULL, NULL, NULL, NULL),
(7, 4, 'BabyMarket 2', NULL, NULL, 'https://gitlab.com/iatanasov77/babymarket.bg_2.git', 'develop', '/projects/BabyMarket_2', NULL, NULL, NULL, NULL),
(8, 2, 'Okta_AspNetCoreMysql', NULL, 'git', 'https://github.com/oktadeveloper/okta-aspnetcore-mysql-twilio-example.git', 'master', '/projects/Okta_AspNetCoreMysql', NULL, NULL, NULL, NULL),
(11, 2, 'TestHosts', NULL, NULL, NULL, NULL, '/projects/TestHosts', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `VSWPG_ProjectsHosts`
--

INSERT INTO `VSWPG_ProjectsHosts` (`id`, `project_id`, `host_type`, `options`, `host`, `document_root`, `with_ssl`) VALUES
(1, 1, 'Lamp', 'null', 'junona.lh', '/projects-myspace/Test', 0),
(13, 11, 'Lamp', 'null', 'lamp.lh', '/projects/TestHosts/TestLamp', 0),
(16, 1, 'Lamp', '{\"phpVersion\": \"default\"}', 'salary-j.lh', '/projects/VS_SalaryJ/public/salary-j', 0),
(17, 1, 'Lamp', '{\"phpVersion\": \"default\"}', 'admin.salary-j.lh', '/projects/VS_SalaryJ/public/admin_panel', 0),
(18, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'test_1.lh', '/projects/TestHosts/TestLamp', 0),
(19, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'test_1.lh', '/projects/TestHosts/TestLamp', 0),
(20, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'test_1.lh', '/projects/TestHosts/TestLamp', 0),
(21, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'test-frontend.lh', '/projects/TestHosts/TestFrontend/public', 0),
(22, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'mlhoutel-tablatures.lh', '/projects/TestHosts/Mlhoutel_Tablatures/dist', 0),
(23, 6, 'Lamp', '[]', 'wgp.lh', '/projects/VS_WebGuitarPro/public/web-guitar-pro', 0),
(24, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'test-payment-bundle.lh', '/projects/TestHosts/TestPaymentBundle/public/test-payment-bundle', 0),
(25, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'admin.test-payment-bundle.lh', '/projects/TestHosts/TestPaymentBundle/public/admin-panel', 0),
(26, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'reactjs.lh', '/projects/FrontendProjects/ReactJs_Project/build', 0),
(27, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'admin.reactjs.lh', '/projects/TestHosts/ReactJS_Project/public/admin-panel', 0),
(28, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'reactjs.lh', '/projects/TestHosts/ReactJs_Project/public', 0),
(29, 5, 'Lamp', '{\"phpVersion\": \"default\"}', 'sylius-api.lh', '/projects/Sylius/SyliusApi/public', 0),
(30, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'prestashop-1.7.lh', '/projects/PrestaShop/1.7', 0),
(31, 11, 'Lamp', '{\"phpVersion\": \"8.1.2\", \"vsApplicationAliases\": \"1\"}', 'vankosoft-application-php8.lh', '/projects/TestHosts/VankosoftApplication/PHP_8/public/test-vankosoft-application', 0),
(32, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'game-platform.lh', '/projects/VS_GamePlatform/public/game-platform', 0),
(33, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'admin.game-platform.lh', '/projects/VS_GamePlatform/public/admin-panel', 0),
(34, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'cards.game-platform.lh', '/projects/VS_GamePlatform/public/card-games', 0),
(35, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'bridge-belote.game-platform.lh', '/projects/VS_GamePlatform/public/bridge-belote', 0),
(36, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'contract-bridge.game-platform.lh', '/projects/VS_GamePlatform/public/contract-bridge', 0),
(37, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'chess.game-platform.lh', '/projects/VS_GamePlatform/public/chess', 0),
(38, 11, 'Lamp', '{\"phpVersion\": \"default\", \"vsApplicationAliases\": \"1\"}', 'backgammon.game-platform.lh', '/projects/VS_GamePlatform/public/backgammon', 0),
(39, 11, 'Lamp', '{\"phpVersion\": \"8.1.2\"}', 'symfony-6.1.lh', '/projects/TestHosts/Symfony/6.1/public', 0),
(40, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'angular.lh', '/projects/TestHosts/Angular_Project/dist/angular-project', 0),
(41, 11, 'Lamp', '{\"phpVersion\": \"8.1.13\"}', 'prestashop-8.0.lh', '/projects/PrestaShop/8.0.1', 0),
(42, 11, 'Lamp', '{\"phpVersion\": \"default\"}', 'sylius-1.12.lh', '/projects/Sylius/1.12/public', 0),
(43, 11, 'Lamp', '{\"phpVersion\": \"8.0.26\"}', 'magento-2.4.lh', '/projects/Magento/2.4/pub', 0),
(44, 11, 'Lamp', '{\"phpVersion\": \"7.4.33\"}', 'test-phpbrew.lh', '/projects/TestHosts/TestPhpBrew', 0);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
