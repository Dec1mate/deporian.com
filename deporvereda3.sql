-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-02-2019 a las 18:51:31
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `deporvereda3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `amonesta`
--

CREATE TABLE `amonesta` (
  `arbitro_dni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `equipo_nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arbitro`
--

CREATE TABLE `arbitro` (
  `dni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `edad` int(8) NOT NULL,
  `altura` decimal(3,2) NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `contrasenya` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `arbitro`
--

INSERT INTO `arbitro` (`dni`, `nombre`, `edad`, `altura`, `foto`, `contrasenya`) VALUES
('14841495-Q', 'Albert Rivera', 54, '1.87', 'IMGs\\generic.png', '$2y$10$NhoojTNIqsxFe4Qbuhre8uFyW.1US/qzfLUkF4dBw6FK0VGkRu1RG'),
('15978157-Q', 'Pablo Iglesias', 49, '1.75', 'IMGs\\generic.png', '$2y$10$CTteDpw8btMIBhg0/OJAfOsiyYUKfpPEq8Bb0zX9XU8hnShuTn76a'),
('46556798-P', 'Jose Maria Aznar', 75, '1.87', 'IMGs\\generic.png', '$2y$10$ni3qq4jHARap/u1fvQjIOuZvUJRoJ2/HTeY2uWmI9RYqrLVF4Rfxa'),
('54789189-L', 'Pablo Casado', 59, '1.99', 'IMGs\\generic.png', '$2y$10$TMj2joPQBuadzZ4P6b29wOGHaEgLdIxwpF4HcE555UAC7VEd3M.mW'),
('55555555-J', 'Anakin Darth Vader Skywalker', 89, '1.99', 'IMGs\\generic.png', '$2y$10$ft0JW9mnYlQFJDagpSTKPuISeNqL0aw3qFXV4.K6V.bCL57ql76tm'),
('56541415-P', 'Felipe Gonzalez', 66, '1.87', 'IMGs\\generic.png', '$2y$10$PcIfCT1cflCelf3Tt5bcQOJt1bpE4Sjwx5J15IQgRy7z5aIX9t3v.'),
('58745356-P', 'Pedro Sanchez', 56, '1.89', 'IMGs\\generic.png', '$2y$10$iyIo0mHZupuq1rc9RjXYe.Aewb3lM36zuiAFflMbxNhX0qe5Cqi4.'),
('66666666-T', 'Lord Voldemort', 99, '1.88', 'IMGs\\generic.png', '$2y$10$e5uH/MBmaMeTUyno6o3tOenkxLtulyob7akLfLIbnPhQIDpLpCAPq'),
('95447865-K', 'Mariano Rajoy', 78, '1.99', 'IMGs\\generic.png', '$2y$10$L7jELcoIkXTwv8CwPVRn0O8vZ2q41deJq7RIC6oARLVp/T6dqJ6Z.'),
('99999999-Z', 'Senyoroscuro Sauron', 999, '2.50', 'IMGs\\generic.png', '$2y$10$ELKGjl4dODXA/tMbXT7iHe3erh/QbYK0QPXC5mdDEUUtNZgs/X.KO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campo`
--

CREATE TABLE `campo` (
  `id` int(8) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `campo`
--

INSERT INTO `campo` (`id`, `nombre`) VALUES
(1, 'interior'),
(2, 'exterior');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `puntos` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`nombre`, `logo`, `fecha`, `puntos`) VALUES
('Cloud 9', 'IMGs\\equipos\\cloud9.png', '2019-01-29 14:50:31', 0),
('Echo Fox', 'IMGs\\equipos\\echofox.png', '2019-01-29 14:50:40', 0),
('FlyQuest', 'IMGs\\equipos\\flyquest.png', '2019-01-29 14:50:48', 0),
('Fnatic', 'IMGs\\equipos\\fnatic.png', '2019-01-29 14:50:54', 0),
('G2 Esports', 'IMGs\\equipos\\g2.png', '2019-01-29 14:51:01', 0),
('Gen G', 'IMGs\\equipos\\geng.png', '2019-01-29 14:51:09', 0),
('Giants', 'IMGs\\equipos\\giants.png', '2019-01-29 14:51:15', 0),
('Origen', 'IMGs\\equipos\\origen.png', '2019-01-29 14:51:31', 0),
('SKT T1', 'IMGs\\equipos\\skt.png', '2019-01-29 14:51:39', 0),
('Team Liquid', 'IMGs\\equipos\\liquid.png', '2019-01-29 14:51:23', 0),
('Team Solo Mid', 'IMGs\\equipos\\tsm.png', '2019-01-29 14:51:48', 0),
('Vitality', 'IMGs\\equipos\\1550078441_vitality.png', '2019-02-13 18:20:41', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `dni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `edad` int(8) NOT NULL,
  `altura` decimal(3,2) NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `contrasenya` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `equipo` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`dni`, `nombre`, `edad`, `altura`, `foto`, `contrasenya`, `equipo`) VALUES
('11111111-Q', 'Zachary Sneaky Scuderi', 26, '1.75', 'IMGs\\jugadores\\sneaky.png', '$2y$10$tpgEqh.yKNvFWiVTCeF1F.TG3vDKyqUwr1RQwzAqc.Unrt6zH0t0q', 'Cloud 9'),
('11222333-C', 'Rasmus Caps Winther', 20, '1.75', 'IMGs\\jugadores\\caps.png', '$2y$10$W/2ov.OC66KnyX.Nwqj09eIuNrHx6kg59ehuQ.sUpv/ONwSpjsSnC', 'G2 Esports'),
('12423234-P', 'Colin Solo Earnest', 24, '1.87', 'IMGs\\jugadores\\solo.png', '$2y$10$dn7yKCYjN.yatAXZGAJtBeov0aOWGEHPaLmnCvlMVm84dNT5tacOa', 'Echo Fox'),
('14785693-K', 'Wangho Peanut Han', 24, '1.87', 'IMGs\\jugadores\\peanut.png', '$2y$10$cCKMj2scJFb14NO5QnYMtOn3oB2bTVsnLU9zGcqoe9JvZBGZOLDi2', 'Gen G'),
('14785693-L', 'Patrik Patrik Jiru', 25, '1.69', 'IMGs\\jugadores\\patrik.png', '$2y$10$V72dirneECA2h8nF69RXou5/84XPFtyYM2yunersgCy6eQJq.rAdq', 'Origen'),
('15154515-P', 'Daniele Jiizuke Di Mauro', 16, '1.96', 'IMGs\\jugadores\\jiizuke.png', '$2y$10$4oOfYYYVaYvyz6Gf6u0tI.G1xM6ZK6xI0NrEkAf/PoWOjy.mnBbvq', 'Vitality'),
('15445123-T', 'Pedro Grig Garcia', 21, '1.78', 'IMGs\\jugadores\\grig.png', '$2y$10$IEO1POIJsmab1Tf/uX8GcOEVzmVSJZ6JZHeNdb4HQMGSWwQQAEE4K', 'Team Solo Mid'),
('16412152-F', 'Sehyoung Mata Cho', 42, '1.84', 'IMGs\\jugadores\\mata.png', '$2y$10$.ntCrKkv1erHorT.5Wb8cehO2Z.0.youC8XcQiQLrp0R4pmumm7gq', 'SKT T1'),
('21354687-T', 'Charly Djoko Guillard', 26, '1.78', 'IMGs\\jugadores\\djoko.png', '$2y$10$YOdr8TR1lD1zdXu/7V5SE.ftJTnJJdj1XGym7JgC1WCSPIi32/9/m', 'Giants'),
('24169841-R', 'Andy Smoothie Ta', 21, '1.77', 'IMGs\\jugadores\\smoothie.png', '$2y$10$.qzzqnpbTTY/lWfKvJ1PRu17jfI10HbAWikdzpEpE5nABSbuHziI.', 'Team Solo Mid'),
('25874411-L', 'Eugene Pobelter Park', 23, '1.54', 'IMGs\\jugadores\\pobelter.png', '$2y$10$OoHLicUw8fN25zph5cCY2Of6bSs90PG4pJI/oBDYPhEADXufaM9He', 'FlyQuest'),
('37241828-L', 'Soren Bjergsen Bjerg', 26, '1.87', 'IMGs\\jugadores\\bjergsen.png', '$2y$10$LANoY9toL32Dr62puyRJ0.1/law0qPitmm/mDzsZ.Ct/APbA/AC5S', 'Team Solo Mid'),
('44122788-T', 'Gabriel Bwipo Rau', 23, '1.69', 'IMGs\\jugadores\\bwipo.png', '$2y$10$cqyrZJzM3T.ERLBm40xEnenzfdnIGGGqoQbxPqn5FWkYNjAnJRESS', 'Fnatic'),
('45589963-F', 'Mads Broxah Brock-Pedersen', 23, '1.87', 'IMGs\\jugadores\\broxah.png', '$2y$10$jq0y3pijqDIw7NQ5W1UOBOmcoLwh5ppqg7B8F5ayVMlVFq/OE.7I.', 'Fnatic'),
('45698712-T', 'Pierre Steeelback Medjaldi', 26, '1.78', 'IMGs\\jugadores\\steelback.png', '$2y$10$ifLx2Utfe7kUO6Tz70F4tu1u9SEWFP.eJJ2mguH/8Uu.shXUPVdeu', 'Giants'),
('47845587-P', 'Jesper Zven Svenningsen', 24, '1.87', 'IMGs\\jugadores\\zven.png', '$2y$10$XCoREohASWjos4cPs5KSZuwAf7bx6vQjp9TyziTWXbnXe/HplUN5W', 'Team Solo Mid'),
('52147896-P', 'Risto Sirnukesalot Luuri', 52, '1.68', 'IMGs\\jugadores\\sirnukesalot.png', '$2y$10$MCYUTSQYcmXYV7KLKDymXuOZesO3DSlihqG9qyUDUH6xpTAjkH86S', 'Giants'),
('52147896-Q', 'Apollo Apollo Price', 15, '1.87', 'IMGs\\jugadores\\apollo.png', '$2y$10$3SaAjGisMzJKAgGWNG.H8.6yyGrCPQUZwpiThh4DLqm7q2nmvW0S2', 'Echo Fox'),
('54123698-T', 'Nickolas Hakuho Surgent', 65, '1.90', 'IMGs\\jugadores\\hakuho.png', '$2y$10$oYoXZBM4X9S6wWT91LIa3eMY9iIqm3yO.ORv9CuqHMMQWTzv/g/m2', 'Echo Fox'),
('54477663-R', 'Martin Wunder Hansen', 26, '1.58', 'IMGs\\jugadores\\wunder.png', '$2y$10$gneJK2WAfvi/ZrRWXkY1N.YQZD7WfpzL9AmPKDi//lRyJVb1mA0tO', 'G2 Esports'),
('54485984-L', 'Barney Alphari Morris', 22, '1.87', 'IMGs\\jugadores\\alphari.png', '$2y$10$gPQ/6YcjngPA/3Nm8P.29Om1BThZCKhrufjors2/cR1bygtNBcl6u', 'Origen'),
('54879145-P', 'Amadeu Attila Carvalho', 21, '1.89', 'IMGs\\jugadores\\attila.png', '$2y$10$JLt2A6PoVsWd3pgpCeHo3u1erOmYimd9sZfp1CurIqy2Y0Q/6VlwC', 'Vitality'),
('55447778-P', 'Lucas Santorin Larsen', 26, '1.89', 'IMGs\\jugadores\\santorin.png', '$2y$10$incRNsuM2VqtKXVwg6Yf0ut85L.6cbIlWyhtmkQ2868a8gjlTrFqi', 'FlyQuest'),
('55477441-G', 'Jason Wildturtle Tran', 24, '1.58', 'IMGs\\jugadores\\wildturtle.png', '$2y$10$Yio8RuqtU/S2JYhzxIV0Se1raRZhnUTcyrpIZqNXksAIQbqtdyvW6', 'FlyQuest'),
('58418946-P', 'Jaeha Mowgli Lee', 26, '1.87', 'IMGs\\jugadores\\mowgli.png', '$2y$10$HJTG.yJtV4kdEmQhv1c24e2S.GhkJPK51ZRokx9z/XYsmZ9j5mA5e', 'Vitality'),
('58963214-L', 'Yoonjae Rush Lee', 25, '1.98', 'IMGs\\jugadores\\rush.png', '$2y$10$Cov5e1Di6GL0Sx0ufZLiQOYWD7wan0U7q9NlovO6HKSrcmkSk.dT2', 'Echo Fox'),
('63251478-T', 'Jake Xmithie Puchero', 24, '1.58', 'IMGs\\jugadores\\xmithie.png', '$2y$10$DxLqsdiIkS/1Qd2aGCVsR.sbwa57keJWt8b2GZoDz53dzf9Ggq/Fu', 'Team Liquid'),
('64794468-P', 'Jakub Jactroll Skurzynski', 23, '1.89', 'IMGs\\jugadores\\jactroll.png', '$2y$10$DtBSz/ZlaWFM0FiiZuDEN.DkYGcPtyeXn36.BH2tqVMeif4SZFzGG', 'Vitality'),
('65412879-T', 'Jaehyeok Ruler Park', 24, '1.78', 'IMGs\\jugadores\\ruler.png', '$2y$10$fH/orxxMD4Id9DbKp8giD.G9SIzKOnfKaiohQbr5migVQxmYMQta2', 'Gen G'),
('65478932-E', 'Juan Jayj Guibert', 21, '1.87', 'IMGs\\jugadores\\jayj.png', '$2y$10$84/RISB5PTUWkCLL0NgsS.MKt6Y6TGRllSggXWkhmpQTYKYcpEbUi', 'FlyQuest'),
('65514544-R', 'Sergen Brokenblade Celik', 21, '1.78', 'IMGs\\jugadores\\broken blade.png', '$2y$10$.DdhrmafAzHdXGUjZm8tGul9GwaHzqFRdNvsy4ol7QHfPP6VkSTNC', 'Team Solo Mid'),
('65896325-Q', 'Eric Licorice Ritchie', 25, '1.89', 'IMGs\\jugadores\\licorice.png', '$2y$10$x9nyeOhkMa/llC4MgFqIVOyYPFqTnhOPWdiNktT76I3rL7RsY0ng6', 'Cloud 9'),
('66555444-J', 'Marcin Jankos Jankowski', 24, '1.85', 'IMGs\\jugadores\\jankos.png', '$2y$10$MvjMTOo0xkDS1F9ds1Ff8u2Y88jXK8CCVGVCsWR1T94MS1YgG6YoC', 'G2 Esports'),
('66555444-P', 'Tim Nemesis Lipovsek', 23, '1.74', 'IMGs\\jugadores\\nemesis.png', '$2y$10$vekyW0cp0jA0m4OeRa8LXuFIOs/mebUZ2cf1xp42ABLYKsd1E40IG', 'Fnatic'),
('69415445', 'Jinsung Teddy Park', 21, '1.45', 'IMGs\\jugadores\\teddy.png', '$2y$10$dbaZK3NoGCwHISAKbd6re.mhGXLXC3E2xpjzrGxQzR5mbv4j2dfGm', 'SKT T1'),
('69853214-R', 'Felix Betsy Edling', 24, '1.87', 'IMGs\\jugadores\\betsy.png', '$2y$10$VPip9.Lce3PNuWuU/darJO6vnqb/OeC31w514KiikHpckrjkY2jAm', 'Giants'),
('69985223-K', 'Sungjin Cuvee Lee', 24, '1.89', 'IMGs\\jugadores\\cuvee.png', '$2y$10$iNbyl30RFhyBFCgxsyCew.V9AyMCGBDL2WfqSTGZFiNlhOnZ4zUUa', 'Gen G'),
('74127961-J', 'Eon-Young Impact Jeong', 26, '1.87', 'IMGs\\jugadores\\impact.png', '$2y$10$MvVfWsVMfPLApXh4ijBXxu/Z8sQQQ5P.EnDDOM7M6QBubR/3UqA9K', 'Team Liquid'),
('78128791-T', 'pepega', 23, '1.96', 'IMGs\\generic.png', '$2y$10$rt3Vz8ZMHoDIFVBFOY2v3ugob0To3Z1oqTR.1YiQ/ml8rllwNIPTi', 'Cloud 9'),
('78187918-R', 'Alfonso Mithy Rodriguez', 45, '1.69', 'IMGs\\jugadores\\mithy.png', '$2y$10$IIpdiuUFsuM.hywv1CQ/2euTq0vW.vMJofJFiR5Cf49IYOYGkinda', 'Origen'),
('78965412-Q', 'Yasin Nisqy Dincer', 26, '1.87', 'IMGs\\jugadores\\nisqy.png', '$2y$10$DXxFu1OHAxX9VY2qUfDnXeg4czaw1bMQxh06vYx6fNN5j8dSM0nI2', 'Cloud 9'),
('79134798-L', 'Lucas Cabochard Simon-Meslet', 19, '1.87', 'IMGs\\jugadores\\cabochard.png', '$2y$10$WEz1tzZGGYonr3x3urB9R.JKopAZtY8vVsqOWZvkMuAS5ULKAebeS', 'Vitality'),
('79864541-P', 'Nicolaj Jensen Jensen', 21, '1.58', 'IMGs\\jugadores\\jensen.png', '$2y$10$.arpHK8oWUp9XPB05sdyzeKNd9ZXcPLabysnj.3cAz8dv2fdFCAGe', 'Team Liquid'),
('84555595-L', 'Taemin Clid Kim', 22, '1.87', 'IMGs\\jugadores\\clid.png', '$2y$10$ggjERC71E5W4gfez.d5P3.IaesbV5gNgTtzp52X0tmy9wQIJ3RUU2', 'SKT T1'),
('84736474-T', 'Yiliang Doublelift Peng', 26, '1.98', 'IMGs\\jugadores\\doublelift.png', '$2y$10$ND0F9XjuUNlKMc7dFBny1.38M90CWi/Vr7rhwmUSzA8CM6fl84kBS', 'Team Liquid'),
('85214789-S', 'Luka Perkz Perkovic', 18, '1.87', 'IMGs\\jugadores\\perkz.png', '$2y$10$WQGhBC3sBEyS7vQg08TA0OIZ8kJMjvGOKBXn4lj/LeAwpMwsNZ.5O', 'G2 Esports'),
('85214796-R', 'Jae Hoon Fenix Kim', 24, '1.25', 'IMGs\\jugadores\\fenix.png', '$2y$10$SRsVoBtkXUFzm8iS6JP32OO8bA1M.eCSax8ilvX4wFNlmsrFw79NC', 'Echo Fox'),
('87128793-D', 'Dongha Khan Kim', 24, '1.99', 'IMGs\\jugadores\\khan.png', '$2y$10$pCeYTKsMnMqMEsjnHeAaaOnNKF8.lCE39nkznqERZZt3gB8lxQizi', 'SKT T1'),
('87547454-L', 'Tristan Zeyzal Stidam', 23, '1.78', 'IMGs\\jugadores\\zeyzal.png', '$2y$10$EjAEd61.ECDS2F7aXekKHevALcOoYMqPnJLK1F1VxmJ4LfQqo8/EC', 'Cloud 9'),
('88888888-H', 'Sanghyeok Faker Lee', 21, '1.80', 'IMGs\\jugadores\\faker.png', '$2y$10$1fG2uky7XHbpXXJ0JpcWTuGdvhGXQmHbHinQL8kQgr9O97/s7zi2e', 'SKT T1'),
('89696325-P', 'Dennis Svenskeren Johnsen', 56, '1.57', 'IMGs\\jugadores\\svenskeren.png', '$2y$10$Ei1eeiBF3u0vrWTTz/TIaei35SI6Atd2qeTtaz0loZaUzF./WmGay', 'Cloud 9'),
('89789845-S', 'Erlend Nukeduck Holm', 27, '1.58', 'IMGs\\jugadores\\nukeduck.png', '$2y$10$irQQidA4fzRRuqi9BQk3z.6gBlm4E81/ywKYKZPj8FVinJeNxR/Yu', 'Origen'),
('95854468-T', 'Jonas Kold Anderson', 24, '1.58', 'IMGs\\jugadores\\kold.png', '$2y$10$T8GOWdAHmSDeQknouv9MwOkokRfMm3KlGMim4aJf3.ZV1eEraucAW', 'Origen'),
('96325874-P', 'Raphael Targamas Crabbe', 26, '1.68', 'IMGs\\jugadores\\targamas.png', '$2y$10$FFi4mevYrgwhGzaHJNGY4eeeYd4crz/1nONZctou03U2LRGbXmbzG', 'Giants'),
('98653214-P', 'Jeongmin Life Kim', 24, '1.58', 'IMGs\\jugadores\\life.png', '$2y$10$Bc/b9rV.3D1I2OV0LG4C2eyzvjrl6GRYAFvLSlqBYwsBwccKpI/gG', 'Gen G'),
('98653214-W', 'Yongin Corejj Jo', 23, '1.98', 'IMGs\\jugadores\\corejj.png', '$2y$10$lzALh5yRTUN0ah7NFkERd.dSEHkQEiG5y0UDr85JFPEhYGJHyVGja', 'Team Liquid'),
('98745123-J', 'Yongjun Fly Song', 25, '1.87', 'IMGs\\jugadores\\fly.png', '$2y$10$iJzJx6y.upELGfkzoX04HO49R4SlXT7XxDic0/nVnhwLvpo8SaZC.', 'Gen G'),
('98889696-S', 'Omran V1per Shoura', 52, '1.89', 'IMGs\\jugadores\\v1per.png', '$2y$10$5xpIam5xPIZlNYjsLzgdm.UJnX2Y9/xooPKylKnY8XenzE/Ky1DPq', 'FlyQuest'),
('99877441-P', 'Zdravets Hylissang Iliev Galabov', 23, '1.87', 'IMGs\\jugadores\\hylissang.png', '$2y$10$xFbs00piLJXXF2mpzY2HEOpt8XXMrhyyCPiPwYGnNs6rqtRiBpMSG', 'Fnatic'),
('99887744-L', 'Martin Rekkles Larsson', 26, '1.88', 'IMGs\\jugadores\\rekkles.png', '$2y$10$WwHOCJ8DPRVkuxToPsXV1uY2UtKZpyATT2Q2TbnZErM9286e6yKd6', 'Fnatic'),
('99887778-P', 'Mihael Mikyx Mehle', 26, '1.96', 'IMGs\\jugadores\\mikyx.png', '$2y$10$.8GJZKA.GDSrABObIMLeAuvdM2oAKHGNWmnyOWsLYeqEb56bxf.PK', 'G2 Esports');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liga`
--

CREATE TABLE `liga` (
  `edicion` int(8) NOT NULL,
  `estado` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `liga`
--

INSERT INTO `liga` (`edicion`, `estado`) VALUES
(1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int(8) NOT NULL,
  `equipo_nombre_1` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `equipo_nombre_2` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `arbitro_dni` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `goles_1` int(8) NOT NULL,
  `goles_2` int(8) NOT NULL,
  `campo_id` int(8) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `jornada_numero` int(8) NOT NULL,
  `liga_edicion` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `equipo_nombre_1`, `equipo_nombre_2`, `arbitro_dni`, `goles_1`, `goles_2`, `campo_id`, `fecha`, `jornada_numero`, `liga_edicion`) VALUES
(1068, 'G2 Esports', 'Gen G', '99999999-Z', 0, 0, 2, '2019-02-24 09:00:00', 1, 1),
(1069, 'FlyQuest', 'Team Liquid', '55555555-J', 0, 0, 1, '2019-02-24 19:00:00', 1, 1),
(1070, 'Fnatic', 'Giants', '46556798-P', 0, 0, 1, '2019-02-23 13:00:00', 1, 1),
(1071, 'SKT T1', 'Origen', '55555555-J', 0, 0, 1, '2019-02-23 11:00:00', 1, 1),
(1072, 'Echo Fox', 'Vitality', '95447865-K', 0, 0, 2, '2019-02-24 13:00:00', 1, 1),
(1073, 'Team Solo Mid', 'Cloud 9', '99999999-Z', 0, 0, 2, '2019-02-23 09:00:00', 1, 1),
(1074, 'G2 Esports', 'Origen', '58745356-P', 0, 0, 1, '2019-03-02 13:00:00', 2, 1),
(1075, 'Cloud 9', 'Gen G', '58745356-P', 0, 0, 2, '2019-03-03 19:00:00', 2, 1),
(1076, 'Giants', 'Team Liquid', '58745356-P', 0, 0, 1, '2019-03-03 21:00:00', 2, 1),
(1077, 'Echo Fox', 'FlyQuest', '56541415-P', 0, 0, 2, '2019-03-03 09:00:00', 2, 1),
(1078, 'Fnatic', 'SKT T1', '14841495-Q', 0, 0, 2, '2019-03-02 11:00:00', 2, 1),
(1079, 'Vitality', 'Team Solo Mid', '56541415-P', 0, 0, 2, '2019-03-02 21:00:00', 2, 1),
(1080, 'SKT T1', 'Team Liquid', '46556798-P', 0, 0, 2, '2019-03-09 21:00:00', 3, 1),
(1081, 'Vitality', 'Cloud 9', '54789189-L', 0, 0, 1, '2019-03-09 13:00:00', 3, 1),
(1082, 'Origen', 'FlyQuest', '15978157-Q', 0, 0, 1, '2019-03-09 19:00:00', 3, 1),
(1083, 'Team Solo Mid', 'G2 Esports', '55555555-J', 0, 0, 2, '2019-03-10 11:00:00', 3, 1),
(1084, 'Gen G', 'Fnatic', '14841495-Q', 0, 0, 1, '2019-03-09 21:00:00', 3, 1),
(1085, 'Echo Fox', 'Giants', '54789189-L', 0, 0, 1, '2019-03-09 09:00:00', 3, 1),
(1086, 'FlyQuest', 'Gen G', '99999999-Z', 0, 0, 1, '2019-03-17 17:00:00', 4, 1),
(1087, 'SKT T1', 'G2 Esports', '54789189-L', 0, 0, 1, '2019-03-17 13:00:00', 4, 1),
(1088, 'Cloud 9', 'Echo Fox', '58745356-P', 0, 0, 1, '2019-03-17 19:00:00', 4, 1),
(1089, 'Team Solo Mid', 'Giants', '54789189-L', 0, 0, 2, '2019-03-17 09:00:00', 4, 1),
(1090, 'Fnatic', 'Team Liquid', '56541415-P', 0, 0, 2, '2019-03-17 21:00:00', 4, 1),
(1091, 'Vitality', 'Origen', '95447865-K', 0, 0, 2, '2019-03-16 19:00:00', 4, 1),
(1092, 'Gen G', 'Giants', '15978157-Q', 0, 0, 2, '2019-03-23 11:00:00', 5, 1),
(1093, 'Origen', 'Team Solo Mid', '66666666-T', 0, 0, 2, '2019-03-23 19:00:00', 5, 1),
(1094, 'SKT T1', 'FlyQuest', '95447865-K', 0, 0, 2, '2019-03-24 17:00:00', 5, 1),
(1095, 'G2 Esports', 'Cloud 9', '55555555-J', 0, 0, 2, '2019-03-24 13:00:00', 5, 1),
(1096, 'Echo Fox', 'Fnatic', '95447865-K', 0, 0, 2, '2019-03-24 11:00:00', 5, 1),
(1097, 'Team Liquid', 'Vitality', '14841495-Q', 0, 0, 1, '2019-03-24 11:00:00', 5, 1),
(1098, 'Gen G', 'Origen', '54789189-L', 0, 0, 1, '2019-03-31 09:00:00', 6, 1),
(1099, 'SKT T1', 'Echo Fox', '99999999-Z', 0, 0, 1, '2019-03-30 19:00:00', 6, 1),
(1100, 'Fnatic', 'Vitality', '99999999-Z', 0, 0, 1, '2019-03-30 09:00:00', 6, 1),
(1101, 'FlyQuest', 'Cloud 9', '54789189-L', 0, 0, 1, '2019-03-30 11:00:00', 6, 1),
(1102, 'G2 Esports', 'Giants', '66666666-T', 0, 0, 1, '2019-03-31 13:00:00', 6, 1),
(1103, 'Team Solo Mid', 'Team Liquid', '54789189-L', 0, 0, 1, '2019-03-31 19:00:00', 6, 1),
(1104, 'Team Solo Mid', 'Gen G', '56541415-P', 0, 0, 1, '2019-04-06 19:00:00', 7, 1),
(1105, 'Origen', 'Fnatic', '99999999-Z', 0, 0, 2, '2019-04-07 17:00:00', 7, 1),
(1106, 'Vitality', 'G2 Esports', '99999999-Z', 0, 0, 1, '2019-04-07 13:00:00', 7, 1),
(1107, 'SKT T1', 'Cloud 9', '46556798-P', 0, 0, 1, '2019-04-06 09:00:00', 7, 1),
(1108, 'Giants', 'FlyQuest', '99999999-Z', 0, 0, 1, '2019-04-07 17:00:00', 7, 1),
(1109, 'Team Liquid', 'Echo Fox', '54789189-L', 0, 0, 1, '2019-04-07 09:00:00', 7, 1),
(1110, 'FlyQuest', 'G2 Esports', '14841495-Q', 0, 0, 1, '2019-04-14 11:00:00', 8, 1),
(1111, 'Vitality', 'Giants', '14841495-Q', 0, 0, 2, '2019-04-13 11:00:00', 8, 1),
(1112, 'Cloud 9', 'Team Liquid', '54789189-L', 0, 0, 2, '2019-04-13 09:00:00', 8, 1),
(1113, 'Gen G', 'SKT T1', '99999999-Z', 0, 0, 1, '2019-04-14 09:00:00', 8, 1),
(1114, 'Origen', 'Echo Fox', '99999999-Z', 0, 0, 2, '2019-04-13 19:00:00', 8, 1),
(1115, 'Team Solo Mid', 'Fnatic', '14841495-Q', 0, 0, 2, '2019-04-14 13:00:00', 8, 1),
(1116, 'Echo Fox', 'Gen G', '46556798-P', 0, 0, 2, '2019-04-20 19:00:00', 9, 1),
(1117, 'Origen', 'Team Liquid', '14841495-Q', 0, 0, 1, '2019-04-20 11:00:00', 9, 1),
(1118, 'SKT T1', 'Team Solo Mid', '14841495-Q', 0, 0, 2, '2019-04-21 11:00:00', 9, 1),
(1119, 'Giants', 'Cloud 9', '55555555-J', 0, 0, 2, '2019-04-20 09:00:00', 9, 1),
(1120, 'Fnatic', 'G2 Esports', '95447865-K', 0, 0, 2, '2019-04-21 09:00:00', 9, 1),
(1121, 'Vitality', 'FlyQuest', '95447865-K', 0, 0, 2, '2019-04-21 21:00:00', 9, 1),
(1122, 'Vitality', 'Gen G', '56541415-P', 0, 0, 1, '2019-04-27 11:00:00', 10, 1),
(1123, 'G2 Esports', 'Team Liquid', '66666666-T', 0, 0, 1, '2019-04-28 11:00:00', 10, 1),
(1124, 'Origen', 'Cloud 9', '14841495-Q', 0, 0, 2, '2019-04-28 09:00:00', 10, 1),
(1125, 'Giants', 'SKT T1', '54789189-L', 0, 0, 2, '2019-04-28 17:00:00', 10, 1),
(1126, 'Team Solo Mid', 'Echo Fox', '54789189-L', 0, 0, 1, '2019-04-28 13:00:00', 10, 1),
(1127, 'FlyQuest', 'Fnatic', '56541415-P', 0, 0, 2, '2019-04-27 19:00:00', 10, 1),
(1128, 'SKT T1', 'Vitality', '58745356-P', 0, 0, 1, '2019-05-04 11:00:00', 11, 1),
(1129, 'Gen G', 'Team Liquid', '46556798-P', 0, 0, 1, '2019-05-05 09:00:00', 11, 1),
(1130, 'G2 Esports', 'Echo Fox', '54789189-L', 0, 0, 2, '2019-05-04 09:00:00', 11, 1),
(1131, 'Cloud 9', 'Fnatic', '58745356-P', 0, 0, 2, '2019-05-04 11:00:00', 11, 1),
(1132, 'FlyQuest', 'Team Solo Mid', '66666666-T', 0, 0, 2, '2019-05-05 17:00:00', 11, 1),
(1133, 'Origen', 'Giants', '46556798-P', 0, 0, 1, '2019-05-05 19:00:00', 11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pertenece`
--

CREATE TABLE `pertenece` (
  `equipo_nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `liga_edicion` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pertenece`
--

INSERT INTO `pertenece` (`equipo_nombre`, `liga_edicion`) VALUES
('Cloud 9', 1),
('Echo Fox', 1),
('FlyQuest', 1),
('Fnatic', 1),
('G2 Esports', 1),
('Gen G', 1),
('Giants', 1),
('Origen', 1),
('SKT T1', 1),
('Team Liquid', 1),
('Team Solo Mid', 1),
('Vitality', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `equipo_nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `arbitro_dni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `campo_id` int(8) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`equipo_nombre`, `arbitro_dni`, `campo_id`, `fecha`) VALUES
('Cloud 9', '14841495-Q', 1, '2019-02-18 15:00:00'),
('Cloud 9', '66666666-T', 2, '2019-02-19 18:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `amonesta`
--
ALTER TABLE `amonesta`
  ADD PRIMARY KEY (`arbitro_dni`,`equipo_nombre`),
  ADD KEY `fk_aequipo` (`equipo_nombre`);

--
-- Indices de la tabla `arbitro`
--
ALTER TABLE `arbitro`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `campo`
--
ALTER TABLE `campo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`nombre`);

--
-- Indices de la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `fk_jequipo` (`equipo`);

--
-- Indices de la tabla `liga`
--
ALTER TABLE `liga`
  ADD PRIMARY KEY (`edicion`);

--
-- Indices de la tabla `partido`
--
ALTER TABLE `partido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pequipo1` (`equipo_nombre_1`),
  ADD KEY `fk_pequipo2` (`equipo_nombre_2`),
  ADD KEY `fk_parbit` (`arbitro_dni`),
  ADD KEY `fk_pcampo` (`campo_id`),
  ADD KEY `liga_edicion` (`liga_edicion`);

--
-- Indices de la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD PRIMARY KEY (`equipo_nombre`,`liga_edicion`),
  ADD KEY `fk_ligaedicion` (`liga_edicion`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`equipo_nombre`,`campo_id`,`fecha`) USING BTREE,
  ADD KEY `fk_rcampo` (`campo_id`),
  ADD KEY `arbitro_dni` (`arbitro_dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `liga`
--
ALTER TABLE `liga`
  MODIFY `edicion` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1134;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `amonesta`
--
ALTER TABLE `amonesta`
  ADD CONSTRAINT `fk_aequipo` FOREIGN KEY (`equipo_nombre`) REFERENCES `equipo` (`nombre`),
  ADD CONSTRAINT `fk_arbit` FOREIGN KEY (`arbitro_dni`) REFERENCES `arbitro` (`dni`);

--
-- Filtros para la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD CONSTRAINT `fk_jequipo` FOREIGN KEY (`equipo`) REFERENCES `equipo` (`nombre`);

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `fk_parbit` FOREIGN KEY (`arbitro_dni`) REFERENCES `arbitro` (`dni`),
  ADD CONSTRAINT `fk_pcampo` FOREIGN KEY (`campo_id`) REFERENCES `campo` (`id`),
  ADD CONSTRAINT `fk_pequipo1` FOREIGN KEY (`equipo_nombre_1`) REFERENCES `equipo` (`nombre`),
  ADD CONSTRAINT `fk_pequipo2` FOREIGN KEY (`equipo_nombre_2`) REFERENCES `equipo` (`nombre`),
  ADD CONSTRAINT `partido_ibfk_1` FOREIGN KEY (`liga_edicion`) REFERENCES `liga` (`edicion`);

--
-- Filtros para la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD CONSTRAINT `fk_ligaedicion` FOREIGN KEY (`liga_edicion`) REFERENCES `liga` (`edicion`),
  ADD CONSTRAINT `fk_ligaequipo` FOREIGN KEY (`equipo_nombre`) REFERENCES `equipo` (`nombre`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_rcampo` FOREIGN KEY (`campo_id`) REFERENCES `campo` (`id`),
  ADD CONSTRAINT `fk_requipo` FOREIGN KEY (`equipo_nombre`) REFERENCES `equipo` (`nombre`),
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`arbitro_dni`) REFERENCES `arbitro` (`dni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
