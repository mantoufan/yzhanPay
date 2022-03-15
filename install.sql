-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-03-14 23:28:45
-- 服务器版本： 5.7.34-log
-- PHP 版本： 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `pay`
--

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_app`
--

CREATE TABLE `yzhanpay_app` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `app_id` varchar(16) NOT NULL COMMENT 'APP ID',
  `display_name` varchar(30) NOT NULL COMMENT 'Display name',
  `app_key` varchar(40) NOT NULL COMMENT 'APP Key',
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='App created by users';

--
-- 转存表中的数据 `yzhanpay_app`
--

INSERT INTO `yzhanpay_app` (`id`, `app_id`, `display_name`, `app_key`, `user_id`, `add_time`, `update_time`) VALUES
(1, '2020102012563901', 'Default App', 'ab3a4d15a438ee95f25cb66f24501208b6e0d8ba', 1, '2020-10-20 12:56:39', '2022-03-14 23:27:49');

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_channel`
--

CREATE TABLE `yzhanpay_channel` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `display_name` varchar(30) NOT NULL COMMENT 'Display name',
  `payment` varchar(10) NOT NULL COMMENT 'Payment method',
  `plugin` varchar(20) NOT NULL COMMENT 'Payment plug-in',
  `env` enum('production','sandbox') NOT NULL COMMENT 'Enviroment',
  `ability` varchar(30) DEFAULT 'checkout' COMMENT 'Channel Ability',
  `config` text NOT NULL COMMENT 'Configuration',
  `client` varchar(60) NOT NULL COMMENT 'Device',
  `app_id` varchar(16) NOT NULL COMMENT 'APP ID',
  `active` tinyint(1) NOT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Payment channel';

--
-- 转存表中的数据 `yzhanpay_channel`
--

INSERT INTO `yzhanpay_channel` (`id`, `display_name`, `payment`, `plugin`, `env`, `ability`, `config`, `client`, `app_id`, `active`) VALUES
(1, 'Alipay China', 'alipay', 'alipaychina', 'production', 'checkout', '{\"app_id\":\"202100320063555\",\"private_key\":\"MIIEvAIBADANBgkAhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCAWLu7eVjz3Q9GoJIOSfJqTtOwMUX+bZHrv7wjP+naxq6UufuLWd8NGVFdLEyFR5c3xVcW42sY5fHLqS6CHeOY2qSWhkPKi87PVVb5xbmK/oicEiHDXwsdfc7+5m+/frc+xOMPux+fyr/74EXBpM+4dMdThUEDIq4oqWmsn3vSHe9EbdnPtSuXD0I8HyPxRz58Uso5Yl+TC8bitkeCvhnLSkyhoDhgmVkp4cjnbW61A+LUEedWro7VMIu+2OuI4BtWjwBcORzNLyFLn5jBN400XJwEMJORfY5nmTKFi0rLMJVP1RWXw8iHv5V/DL8JBNwSYr9sVj3KYsbpQ5E3p9bhAgMBAAECggEACuZ+Wze9JEj8CSWOAgSpv/md5PLqXOd1Yy5PjjbZZ6lEHoGFKZqiZPxneqBOh2tDHot2EA2UhPLSjFd8CbT8JXk8TURt8X/aOqWm02PFlFZ1x7uKfotN6F1M/T0Y9IyQh5Y1Kprb3rhbgcrUYdPbiHDylNdWZCvH2tA4l16cJ4Ygaw+2rJVkHuzCZz8VChe7yCvPWcS7i5aBz4YwIjphEVrpydhQlitWEWlFL4oXIqMaEG0yVKKtCjC4QLH1nMB+SOOLIvNOTYBprejoYVM87/QXWMbyJbOX53jY1wQa/2xuK1ZCouBFf6oRDSXVD7w3HG+Gr/aCWl+/tqJJDfvgAQKBgQDFct7eCgd6tLrc/Dd1dnDTO7X3g1VbKCKgrwGzrbUHodoH3JKMWT5MwoJr9n7+ptDAFaQ+/vRC1UDxlNv9YZSfbXp/edVsw8t2mPwq5lUsfRrSPLYpoHEs6UxODZWkatXoDbhsgMAyOw2knjrQWmQ84H6pzWBWmbGJtZtlIYcBYQKBgQCmaAsWERrY4koIKJBfaK4586NgTHIkgaEPHJ3fVZwZQdDk+fDzwaINMoKom/f5LLqCJ/i1C2yVLn4R3biR25WrfwbjYTgewICuLbCpc7E+oDaBh4ihGqNxFQhZBcKYsSUotzEVNuRLVUUWWmLk848a+sLq3tYnLC4nSo/PWeFFgQKBgHnMOVSIpUJ5OAfXgbJwxHpZDA/JsR6RLIMoUYlv7wrtOVy+IJx49KhPGDrXDFGzv3OuJepCRZTwjaY4aFfuGMsbsoPuOMxmHx1ik7M28HWIGsJzdv9InGfS5iID2TpaOOdzhz9PUL/rk6fnf2pFSC4RYbEHIpVpK45CO8BvpSMhAoGARVVQWS9jSj5urhuIm9gXz5mN1s/DNyaznoJD3QvkcDmV+fGRzV4+UNVczze9CBr00soou/Y4lae7a2JARrWBFOVmT1LweQ+oDeqHkvLbRMaoLyvzZ3yb4L/srHrT657TZrV9Q+ONFz49/ORIFDDOzWTx1b5m6Adma4SLis9eJwECgYBOiMqMGXjkf6+tvHtcc2GHVeWEfuq4hK8BUpqPVN7bdt7eR65CMpEo8kwvvKFORbk9ETUZafp1LeVrbH0ok0oLfhxJBLqOwY4B2nHxoz19hhNM4Dev9FCuKd77nTkvUqOQdtHcXiSwY8X9sExcq7xBWgxiZIZKNPDcZtqZLWZgUg==\",\"public_key\":\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiXeQ5YZylA2hfSkio+XYBbuUO+/76yepjMdZdn3/FhgRIKF2LkQiUAqObcMj/OZq4yyDbCwrN2cddoc57LLxKc9B/2maiCaVIcOP/1fcHq/Nrmc6K+shdZ1eMQpZS+JzOLam+ae9/p7FZPSaa2aUzsOMPm8aDZV1zL2TTmeuDLfg3lNqyWPpFxy5V+lmusnpn6GoH4io6nyauDwQs1pGdA3kuFh0+xKp68MJcl9awfH8QsYG0hXAe3VPcp9IB7oI560aBaIsRQP+getuxnTGwTJChS0Y4i8m36JB0YAJIjHs/3dKxElwkUYlXPgNHYnMeK+vPIYRyfhRE5yc1NkwpwIDAQB\",\"sign_type\":\"RSA2\",\"is_sandbox\":\"0\"}', 'pc,mobile', '2020102012563901', 1),
(2, 'Alipay China Sandbox', 'alipay', 'alipaychina', 'sandbox', 'checkout', '{\"app_id\":\"2023000118663598\",\"private_key\":\"MIIEpQIBAFKCAQEAtlVpel80lAqAJNvpuhn0mYepN2NY2lYIqtgDfj4dtF6qA7pUXzdDSHEP8gymoYMdJlXdm+szh501x+56y7395e/sh5/+t20h7MefV+9OGLlkmdlwv3fTh7Zd+CUqDxifQuSxkReOywaR5RxIylO1bq+81+DxE1MRnjnGyALL17oXsetD6MSNxwTzYjrzc95RgXC61twApfliwYw/wY96FJoCvlfqbsXmMmmJM6QaK2F9vQLSwfkP/kyOJrIxx6eYy+VxD3b9ClYIXPKhT5x1MkDD4b9I4TNTN6P52Svj/BF1+xbOjtMgZtqTdbz1RxMo5q0SsN8XmXsPLDNEG6kQHQIDAQABAoIBADM4JxszJ2f20yyHgk6+/9EpJMXkaI9c365uY/zQojOK7COD8jOVKJdu+1W5bA2u8T9Vm50zIxSTewog2enmAy7WiRFIRptsUr1bDk37cWrMmZAGXv/KP2e+OQN+HSSEfCikaaUigwBRZiaAHYqInUzsnRXfoJkXGrnh7Q8+ididei2VH3JoVQB394LPVqZaKuuwtjsrl2wu4hVS3aAe3+uoMk0Q9WlX7gFhKsOukcVcfPtFC29rnLcDhLU6wqxZOOlexopb5qABeNVpJHXawZwb98kD1XL9r3Avh08QQhspXiADqe7i6L0cisilq2OL3oUSa2Pr1j8ZKz902Ejsd4ECgYEA7HsK8wEQorhfW9PjHQIFMptYIlrG+91s/1uGMrbiVZ2EmyhAcYju05Qsa27eimzJmMU/Nmj2c/Bl47wUuq/h49Y6wakzTqc5GL/cLb2Zb8S6o3k7y/RxuXPFtIHDyPTFRL8V75Sz9rsWa6/EF8UM4nDCH5WjpHoTn+z+kClW+00CgYEAxWI3J8d5FBE4RHIvxQL0VHb38lf1RzL6CxFo0oOCEAtl3MOg6E3q/pN1p2vQTdx8jSwFQzwguhv4W3qAp4Yu7RLtoJ22nDpXPnIJYdAnBBvSOAiurAZwFKnhHVdNKxVglnJ10R6ZF179eaj8nobm2RElQ0faDae91qOvhQ974BECgYEArr3hAl2YGFVbCXTRzr6Obq3Be2lOvhAJmcvcpx7islb6BNjCfeKsb6V/CUfco7btZLjkE+WNr3BFKfnx611J8tx7gFzx7727gCFNpnMCrUdWjrNnzzbqzhnRTqfQr27HUhxNLkLYlIn8cEhqAxJ1ieG+YC5nxIL8e2FZXddeUcUCgYEAnKoM3wzrJCtavrDnC3cB2Lyopfh8XOuwHPLPgS8SH4v8aJH5eAjIORFvc9gV+IbFcQN8ldX2n+EtaeY8kTrmqhK4+x/S3gNMVesLWVBud0thuknh4fYmJbCEVdaCEG0iQadNrhKcLjmd9F9VSroGJkn+in1QLEB0ZBV9bZ5RkxECgYEA1qn5B8sF2B7UiNUKhOOdugy5AFiiwMHlHnG69Nli1ZO/9/PaCbBvKWLJqkFJ0EbRGnPJRKRch0SzaDcTlHBUyegL3QF0dVyOObE5AF0uHyscWtTtGfkKqT/VdIzF5rDHBgbY09XDdG1iCUCFjNLi7MbciCM5W7lqY656JFbrDto=\",\"public_key\":\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg2S//kpuOFyiUATEdYJCZBevmNYhJ5gKrhrJCuaTZr3yjT4IM+JvG2rs11k+8kJwxd9AE+kxUSDNvslaH/a7fW18J6pvxgHkN78tt3bb/e+MNUfZtNHMLA/CyNqguFyOHex6bycE+AwUfsRoLyGHpSeTuDEEAZBkq7c7COPldli6ez8xST2CbKmXsr9rtxYwxLTijhhy1pzqu7Uw2TqoNhTBQBROHG43FK4t2n+IzP0ejzXr8X4/QEIVz3lfCVVZyHgBiRrSsqNSKVOq2UBEo6Za8MjTf2CDDbBtIPC6imcoXa6lZQFy/V3uvNyJqQr3FtcXNFr7C/aoN7JhbjHtcQIDAQAB\",\"sign_type\":\"RSA2\"}', 'pc,mobile', '2020102012563901', 1),
(3, 'Paypal', 'paypal', 'paypal', 'production', 'checkout,subscribe', '{\"client_id\":\"Aeb_GQLIZf5wAkgeWF-ZqWOCh_X0Sl1vJhhryVB-d45qsYSANErq7J9IjFtizPlbzzBu1i95TNZHoni\",\"secret\":\"Aeb_GqkIZf5wAkEeWF-CqWOCh_X0Sl1vJhhryVB-d45qsYSANcrq7J9IjFtizPlbzBu1i95TNZHo2ni\",\"is_sandbox\":\"0\"}', 'pc,mobile', '2020102012563901', 1),
(4, 'Paypal Sandbox', 'paypal', 'paypal', 'sandbox', 'checkout,subscribe', '{\"client_id\":\"ASFFo4BrY3Q1fjONAaruNcsZcs4IPOJZw9cWbsDP1z5ZeZuil5A6vNokHCQjNJD6NWkY594ccereWDh9\",\"secret\":\"EArZCkdVD9_FoFAPQyligNsKjw0AspgKHK8tsTdGlfukk8fZNIPOg4fbr3deyUgY1m5vLpWeWVAfTIjG\"}', 'pc,mobile', '2020102012563901', 1),
(5, 'Alipay Global', 'alipay', 'alipayglobal', 'production', 'checkout,subscribe', '{\"app_id\":\"202100311064562\",\"private_key\":\"MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCAWLu7eVjz3Q9PoJIOSfJqTtOwMUX+bZHrv7wjP+naxq6UufuLWd8NGVFdLEyFR5c3xVcW42sY5fHLqS6CHeOY2qSWhkPKi87PVVb5xbmK/oicEiHDXwsdfc7+5m+/frc+xOMPux+fyr/74EXBpM+4dMdThUEDIq4oqWmsn3vSHe9EbdnPtSuXD0I8HyPxRz58Uso5Yl+TC8bitkeCvhnLSkyhoDhgmVkp4cjnbW61A+LUEedWro7VMIu+2OuI4BtWjwBcORzNLyFLn5jBN400XJwEMJORfY5nmTKFi0rLMJVP1RWXw8iHv5V/DL8JBNwSYr9sVj3KYsbpQ5E3p9bhAgMBAAECggEACuZ+Wze9JEj8CSWOAgSpv/md5PLqXOd1Yy5PjjbZZ6lEHoGFKZqiZPxneqBOh2tDHot2EA2UhPLSjFd8CbT8JXk8TURt8X/aOqWm02PFFZ1x7uKfotN6F1M/T0Y9IyQh5Y1Kprb3rhbgcrUYdPbiHDylNdWZCvH2tA4l16cJ4Ygaw+2rJVkHuzCZz8VChe7yCvPWcS7i5aBz4YwIjphEVrpydhQlitWEWlFL4oXIqMaEG0yVKKtCjC4QLH1nMB+SOOLIvNOTYBprejoYVM87/QXWMbyJbOX53jY1wQa/2xuK1ZCouBFf6oRDSXVD7w3HG+Gr/aCWl+/tqJJDfvgAQKBgQDFct7eCgd6tLrc/Dd1dnDTO7X3g1VbKCKgrwGzrbUHodoH3JKMWT5MwoJr9n7+ptDAFaQ+/vRC1UDxlNv9YZSfbXp/edVsw8t2mPwq5lUsfRrSPLYpoHEs6UxODZWkatXoDbhsgMAyOw2knjrQWmQ84H6pzWBWmbGJtZtlIYcBYQKBgQCmaAsWERrY4koIKJBfaK4586NgTHIkgaEPHJ3fVZwZQdDk+fDzwaINMoKom/f5LLqCJ/i1C2yVLn4R3biR25WrfwbjYTgewICuLbCpc7E+oDaBh4ihGqNxFQhZBcKYsSUotzEVNuRLVUUWWmLk848a+sLq3tYnLC4nSo/PWeFFgQKBgHnMOVSIpUJ5OAfXgbJwxHpZDA/JsR6RLIMoUYlv7wrtOVy+IJx49KhPGDrXDFGzv3OuJepCRZTwjaY4aFfuGMsbsoPuOMxmHx1ik7M28HWIGsJzdv9InGfS5iID2TpaOOdzhz9PUL/rk6fnf2pFSC4RYbEHIpVpK45CO8BvpSMhAoGARVVQWS9jSj5urhuIm9gXz5mN1s/DNyaznoJD3QvkcDmV+fGRzV4+UNVczze9CBr00soou/Y4lae7a2JARrWBFOVmT1LweQ+oDeqHkvLbRMaoLyvzZ3yb4L/srHrT657TZrV9Q+ONFz49/ORIFDDOzWTx1b5m6Adma4SLis9eJwECgYBOiMqMGXjkf6+tvHtcc2GHVeWEfuq4hK8BUpqPVN7bdt7eR65CMpEo8kwvvKFORbk9ETUZafp1LeVrbH0ok0oLfhxJBLqOwY4B2nHxoz19hhNM4Dev9FCuKd77nTkvUqOQdtHcXiSwY8X9sExcq7xBWgxiZIZKNPDcZtqZLWZgUg==\",\"public_key\":\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiXeQ5YZylA2hfSkio+XYBbuUO+/76yepjMdZdn3/FhgRIKF2LkQiUAqObcMj/OZq4yyDbCwrN2cddoc57LLxKc9B/2maiCaVIcOP/1fcHq/Nrmc6K+shdZ1eMQpZS+JzOLam+ae9/p7FZPSaa2aUzsOMPm8aDZV1zL2TTmeuDLfg3lNqyWPpFxy5V+lmusnpn6GoH4io6nyauDwQs1pGdA3kuFh0+xKp68MJcl9awfH8QsYG0hXAe3VPcp9IB7oI560aBaIsRQP+getuxnTGwTJChS0Y4i8m36JB0YAJIjHs/3dKxElwkUYlXPgNHYnMeK+vPIYRyfhRE5yc1NkwpwIDAQAB\",\"sign_type\":\"RSA2\",\"is_sandbox\":\"0\"}', 'pc,mobile', '2020102012563901', 1),
(6, 'Alipay Global Sandbox', 'alipay', 'alipayglobal', 'sandbox', 'checkout,subscribe', '{\"app_id\":\"SANDBOX_5Y3A2N2YEB5003022\",\"private_key\":\"MIIEvQIFADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCfsLCiwlKAiYdtLNoUTxx57SUFFrp4+qEnIn0gCl+bBj9/JiOiG24TSoFlvFAjFiTkL3udOXg9OXwtz0svXLtjrkhAQF7mmfl1Myj/5DrwZJSATO/503ILXBJuyAr64WG1xFl6KPeFfvCeZ6Yo75fIL/vbi7x/8RD7KwrSD4HCebpc+VNSb18ha7wPFDNJ2wgaNVHnPtxwYjiUW2VqBC5GkS1roAH2ernywC2oxXwaOqhxawbBBSMj6S3H73JENY7Sf0fhweKffp0qFqhc3s7lNppo1vTVO+UhVobIkFhpDp/bBddHlc7dCCzqLy65S/0cXyvYjezdkkiKv3pW4EnTAgMBAAECggEAOsRVXLBSmEcEdaMJ5mtuuVgSRZslqJvjbnl8vqvSn0RfXbV1a5TYn3TNxdjVTPQ7Q1ZOEYAyxaVAE8OzkYx40agzoqGNyyNi8ESRlAozvn/lPooRzkiIMbICfo5TrBwBT1kg7Jni7VfXyROvzGTP4LX348W66wKWEzi11LQsNprr9Yty9VHX9xHAcrMvveLYbcjnJbka/XPmoKMpiQzk+q8/7d/IeSkt32TT6cmQGmLcucM68fM859/N/7fUnG8jJ3OvTStFq41RSQxIvknzzoBqFffRbond/XE3zZTR0LAYf4eEGUyd6Ckxl2506ew9awXPsVpZfcUO4+X60OV9kQKBgQDfLn5h9dc1G4q4B4Ge0mkC/wtJejE4nsw5/HNKE0P15RnsmFcruNGebHm3cZIz3TQGE/Y4xMGrMGEowCMsL6MEv0hKsfZxutFxTceykZbyPCqIFNklJBqLhFa+kjtOTShpSyR4ukXupPyStemqXI8bls9BCyHg8f4RD8bhZfMmdwKBgQC3LBvfzBSSzKYRa5YJG7rlRvd0pQKX4dENeBugrPpzcbQcMYXdz0Cg/CGH3yB+50sLGiPEJNSEVlLHXYsjyvP8EXyBf2sdxe0q8aB7RpI0aoEZwREdYthm2KPDNwawl3lWpJmDZZXFH12XWlWluusf5nWV8BwW34PdCOt91/qihQKBgEqzlTnUv6PXBCd02739T4jhNTXy4GbDQDhod25j5Gt5s6Otmf/YbNFKHbN5ICab873VEKUPtoNGPj8VkLBJgZclL11RE9wdW91A24a5lHygBtxlngkAfKIWObKv34nl6ZDUxfBi6OVn8+JnYT7UnFHvuI9c2oogdZngXgEzzp8hAoGBAKpCeMJkWvc8/FwS6cobmXSGq3sj6i4tO3lOnDeUsdPe67CNI8ZSX9uDoNM/xl/PNATkzjwCk/tSle00eSHTA0jZJCbmvjVVhac2I5qStVG9cbTzrann+AhY9Jd/LVu14JZ1ty/YnAc3qXIHCLdc8DKdr8yn/CQSrOom4WX+/LxVAoGAXtOeeYKUvp5KzwFbY+zHm2wpZ2fw+xc7Up/RUeYryFRxXhWw/G3EJHTK7dMadnAYeKaOuE4n9XXmFyuP29QLJ+HCQfuSHOLGbCx3a+j7UsrRbWIcx4vg+YnW1EDd2/Ui8cPCHukRhSm7+jJCjfv2GmatdI73eZ+CQcMS27NTAJY=\",\"public_key\":\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAihzSL26iayp+mj1ipXa7zdQoNDPhTBaxwJ08KZn3ja+G1eFJP445AmbZwGtASGJtbnctuav+ztElJvEU+NvNW3db+EvJXsb9QIj1Elgnt5WCvMDIhUQyDcp/b7WMRZlAyAWbO52sgA9ioAwaNS/jBPtb+8lx0s0bloAVleG7st8Wy7VTXrhOgpMZqsbQfE6dM4PiX7oeU+8NWGWR+pihLYTUsjaY2l+McusfQkBqKvp1bILljbVxBtT66dldCoEPxoCUN4kihwovXhkUzDbVhKFQ8fwrwOTWi2UgNnnMNrtH+cPcJCMz3WMcUaFy0cbQlyQmUbapI3moyPx20m+7jwIDAQAB\",\"sign_type\":\"RSA2\"}', 'pc,mobile', '2020102012563901', 1);

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_client`
--

CREATE TABLE `yzhanpay_client` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(10) NOT NULL COMMENT 'Unique name',
  `display_name` varchar(30) NOT NULL COMMENT 'Display name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Supported client types	';

--
-- 转存表中的数据 `yzhanpay_client`
--

INSERT INTO `yzhanpay_client` (`id`, `name`, `display_name`) VALUES
(1, 'pc', 'PC'),
(2, 'mobile', 'Mobile'),
(3, 'app', 'APP'),
(4, 'face', 'In Person');

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_customer`
--

CREATE TABLE `yzhanpay_customer` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `first_name` varchar(30) DEFAULT NULL COMMENT 'Customer First Name',
  `last_name` varchar(30) DEFAULT NULL COMMENT 'Customer Last Name',
  `description` varchar(254) DEFAULT NULL COMMENT 'Customer Description',
  `email` varchar(30) NOT NULL COMMENT 'Customer Email',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
  `app_id` varchar(16) NOT NULL COMMENT 'APP ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Customer List';

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_log`
--

CREATE TABLE `yzhanpay_log` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `path` varchar(1024) NOT NULL COMMENT 'API Path',
  `action` enum('GET','POST','PUT','DELETE') NOT NULL COMMENT 'Request Method',
  `controller` varchar(30) NOT NULL COMMENT 'Controller Name',
  `method` varchar(20) NOT NULL COMMENT 'Controller Method',
  `payload` text COMMENT 'Payload Params',
  `res_status` int(3) DEFAULT NULL COMMENT 'Response Status',
  `res_header` text COMMENT 'Response Header',
  `res_body` text COMMENT 'Response Body',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `user_id` int(11) DEFAULT NULL COMMENT 'User ID',
  `app_id` varchar(16) DEFAULT NULL COMMENT 'APP ID',
  `queue_status` enum('PENDING','SUCCEED','FAIL') DEFAULT NULL COMMENT 'Queue Task Status',
  `queue_expect` varchar(20) DEFAULT NULL COMMENT 'Queue Task Expect Body',
  `queue_timeout` tinyint(2) DEFAULT NULL COMMENT 'Queue Task Timeout',
  `queue_retry_times` tinyint(2) DEFAULT NULL COMMENT 'Queue Retry Times '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Operation Log';

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_menu`
--

CREATE TABLE `yzhanpay_menu` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(10) NOT NULL COMMENT 'Unique name',
  `display_name` varchar(30) NOT NULL COMMENT 'Display name',
  `path` varchar(60) NOT NULL COMMENT 'Route path or URL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Menu list';

--
-- 转存表中的数据 `yzhanpay_menu`
--

INSERT INTO `yzhanpay_menu` (`id`, `name`, `display_name`, `path`) VALUES
(1, 'order', 'Order', '/order'),
(2, 'myorder', 'My Order', '/myorder'),
(3, 'user', 'User', '/user'),
(4, 'log', 'Log', '/log');

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_payment`
--

CREATE TABLE `yzhanpay_payment` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(16) NOT NULL COMMENT 'Unique name',
  `display_name` varchar(30) NOT NULL COMMENT 'Display name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Payment method';

--
-- 转存表中的数据 `yzhanpay_payment`
--

INSERT INTO `yzhanpay_payment` (`id`, `name`, `display_name`) VALUES
(1, 'depositcard', 'Deposit Card'),
(2, 'creditcard', 'Credit Card'),
(3, 'alipay', 'Alipay China'),
(4, 'alipayglobal', 'Alipay Global'),
(5, 'wechat', 'Wechat Pay'),
(6, 'qq', 'QQ Wallet'),
(7, 'paypal', 'Paypal');

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_plan`
--

CREATE TABLE `yzhanpay_plan` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(254) NOT NULL COMMENT 'Plan Name',
  `description` varchar(254) DEFAULT NULL COMMENT 'Plan Description',
  `status` enum('CREATED','INACTIVE','ACTIVE') DEFAULT 'CREATED' COMMENT 'Plan Status',
  `billing_cycles` text NOT NULL COMMENT 'Billing Cycles',
  `payment_preferences` text NOT NULL COMMENT 'Payment Preferences',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
  `app_id` varchar(16) NOT NULL COMMENT 'APP ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Plan List';

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_plugin`
--

CREATE TABLE `yzhanpay_plugin` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(20) NOT NULL COMMENT 'Unique Name',
  `display_name` varchar(30) NOT NULL COMMENT 'Display Name',
  `payment` varchar(60) NOT NULL COMMENT 'Payment method',
  `type` varchar(30) NOT NULL COMMENT 'Plugin Type',
  `ability` varchar(30) DEFAULT 'checkout' COMMENT 'Plugin Ability',
  `input` varchar(255) NOT NULL COMMENT 'Configurable items',
  `author` varchar(30) NOT NULL COMMENT 'Author name',
  `link` varchar(255) NOT NULL COMMENT 'Support site'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Payment plug-in';

--
-- 转存表中的数据 `yzhanpay_plugin`
--

INSERT INTO `yzhanpay_plugin` (`id`, `name`, `display_name`, `payment`, `type`, `ability`, `input`, `author`, `link`) VALUES
(1, 'alipaychina', 'Alipay China', 'alipay', 'pay', 'checkout', '[{\"type\":\"input\",\"label\":\"App ID\",\"name\":\"app_id\"},{\"type\":\"input\",\"label\":\"App Private Key\",\"name\":\"private_key\"},{\"type\":\"input\",\"label\":\"Alipay Public Key\",\"name\":\"public_key\"},{\"type\":\"input\",\"label\":\"Sign Type\",\"name\":\"sign_type\"}]', 'mantoufan', 'https://github.com/mantoufan'),
(2, 'paypal', 'Paypal', 'paypal', 'pay', 'checkout,subscribe', '[{\"type\":\"input\",\"label\":\"Client ID\",\"name\":\"client_id\"},{\"type\":\"input\",\"label\":\"App Secret\",\"name\":\"secret\"}]', 'mantoufan', 'https://github.com/mantoufan'),
(3, 'alipayglobal', 'Alipay Global', 'alipay', 'pay', 'checkout,subscribe', '[{\"type\":\"input\",\"label\":\"App ID\",\"name\":\"app_id\"},{\"type\":\"input\",\"label\":\"App Private Key\",\"name\":\"private_key\"},{\"type\":\"input\",\"label\":\"Alipay Public Key\",\"name\":\"public_key\"},{\"type\":\"input\",\"label\":\"Sign Type\",\"name\":\"sign_type\"}]', 'mantoufan', 'https://github.com/mantoufan');

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_product`
--

CREATE TABLE `yzhanpay_product` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(254) NOT NULL COMMENT 'Product Name',
  `description` varchar(254) DEFAULT NULL COMMENT 'Product Description',
  `type` enum('PHYSICAL','DIGITAL','SERVICE','') NOT NULL COMMENT 'Product Type',
  `category` enum('AC_REFRIGERATION_REPAIR','ACADEMIC_SOFTWARE','ACCESSORIES','ACCOUNTING') NOT NULL COMMENT 'Product Category',
  `image_url` varchar(1000) DEFAULT NULL COMMENT 'Product Image Url',
  `url` varchar(1000) DEFAULT NULL COMMENT 'Product Url',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `update_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
  `app_id` varchar(16) NOT NULL COMMENT 'APP ID',
  `list` text COMMENT 'Product List'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Product List';

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_token`
--

CREATE TABLE `yzhanpay_token` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `channel_id` int(11) NOT NULL COMMENT 'Channel ID',
  `customer_id` int(11) NOT NULL COMMENT 'Customer ID',
  `trade_no` varchar(64) DEFAULT NULL COMMENT 'Trading Trade No',
  `access_token` varchar(128) DEFAULT NULL COMMENT 'Access Token',
  `access_token_expiry_time` datetime DEFAULT NULL COMMENT 'Access Token Expiry Time',
  `refresh_token` varchar(128) DEFAULT NULL COMMENT 'Refresh Token',
  `refresh_token_expiry_time` datetime DEFAULT NULL COMMENT 'Refresh Token Expiry Time',
  `auth_state` varchar(256) DEFAULT NULL COMMENT 'Auth State',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_trade`
--

CREATE TABLE `yzhanpay_trade` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `trade_no` varchar(64) NOT NULL COMMENT 'Own Trade No',
  `out_trade_no` varchar(64) NOT NULL COMMENT 'App Trade No',
  `api_trade_no` varchar(64) DEFAULT NULL COMMENT 'Api Trade No',
  `status` enum('CREATED','CHECKOUT_SUCCEED','CHECKOUT_FAIL','SUBSCRIPTION_WAIT_REMIND','SUBSCRIPTION_WAIT_CHARGE','SUBSCRIPTION_CHARGE_SUCCEED','SUBSCRIPTION_CHARGE_FAIL','CLOSED') DEFAULT 'CREATED' COMMENT 'Trade Status\r\n0-',
  `channel_id` int(11) NOT NULL COMMENT 'Channel ID',
  `app_id` varchar(16) NOT NULL COMMENT 'App ID',
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `subject` varchar(255) NOT NULL COMMENT 'Title',
  `currency` enum('USD','CNY') DEFAULT 'CNY' COMMENT 'Currency',
  `total_amount` decimal(10,2) NOT NULL COMMENT 'Total Amount',
  `buyer_pay_amount` decimal(10,2) DEFAULT NULL COMMENT 'Buyer Pay Amount',
  `request_time` datetime NOT NULL COMMENT 'Request Time',
  `return_url` varchar(255) NOT NULL COMMENT 'Return Url',
  `notify_url` varchar(255) NOT NULL COMMENT 'Notify Url',
  `cancel_url` varchar(255) DEFAULT NULL COMMENT 'Cancel Url',
  `body` varchar(255) DEFAULT NULL COMMENT 'Description',
  `notify_status` tinyint(1) DEFAULT '0' COMMENT 'Notify status',
  `notify_time` datetime DEFAULT NULL COMMENT 'Last Notify Time',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Customer ID',
  `api_customer_id` varchar(30) DEFAULT NULL COMMENT 'Api Customer ID',
  `product_id` int(11) DEFAULT NULL COMMENT 'Product ID',
  `api_product_id` varchar(30) DEFAULT NULL COMMENT 'API Product ID',
  `plan_id` int(11) DEFAULT NULL COMMENT 'Plan ID',
  `api_plan_id` varchar(30) DEFAULT NULL COMMENT 'API Plan ID',
  `subscription_id` varchar(64) DEFAULT NULL COMMENT 'Subscription ID',
  `api_subscription_id` varchar(30) DEFAULT NULL COMMENT 'API Subscription ID',
  `add_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Add Time',
  `subscription_start_time` datetime DEFAULT NULL COMMENT 'Subscription Start Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Trade records';

-- --------------------------------------------------------

--
-- 表的结构 `yzhanpay_user`
--

CREATE TABLE `yzhanpay_user` (
  `id` int(11) NOT NULL COMMENT 'Increment ID',
  `name` varchar(30) NOT NULL COMMENT 'Unique name',
  `password` varchar(40) NOT NULL COMMENT 'Login password',
  `permission` varchar(255) NOT NULL COMMENT 'Menu permission configuration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='User list';

--
-- 转存表中的数据 `yzhanpay_user`
--

INSERT INTO `yzhanpay_user` (`id`, `name`, `password`, `permission`) VALUES
(1, 'admin', 'fd658251dba5a99a0b08f89ba8990303', '{\"order\":{\"read\":true,\"write\":true,\"delete\":true},\"myorder\":{\"read\":true,\"write\":true,\"delete\":true},\"user\":{\"read\":true,\"write\":true,\"delete\":true}}');

--
-- 转储表的索引
--

--
-- 表的索引 `yzhanpay_app`
--
ALTER TABLE `yzhanpay_app`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_id` (`app_id`);

--
-- 表的索引 `yzhanpay_channel`
--
ALTER TABLE `yzhanpay_channel`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_client`
--
ALTER TABLE `yzhanpay_client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `yzhanpay_customer`
--
ALTER TABLE `yzhanpay_customer`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_log`
--
ALTER TABLE `yzhanpay_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_menu`
--
ALTER TABLE `yzhanpay_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `yzhanpay_payment`
--
ALTER TABLE `yzhanpay_payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `yzhanpay_plan`
--
ALTER TABLE `yzhanpay_plan`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_plugin`
--
ALTER TABLE `yzhanpay_plugin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_product`
--
ALTER TABLE `yzhanpay_product`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yzhanpay_token`
--
ALTER TABLE `yzhanpay_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_state` (`auth_state`);

--
-- 表的索引 `yzhanpay_trade`
--
ALTER TABLE `yzhanpay_trade`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trade_no` (`trade_no`);

--
-- 表的索引 `yzhanpay_user`
--
ALTER TABLE `yzhanpay_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `yzhanpay_app`
--
ALTER TABLE `yzhanpay_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `yzhanpay_channel`
--
ALTER TABLE `yzhanpay_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `yzhanpay_client`
--
ALTER TABLE `yzhanpay_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `yzhanpay_customer`
--
ALTER TABLE `yzhanpay_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_log`
--
ALTER TABLE `yzhanpay_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_menu`
--
ALTER TABLE `yzhanpay_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `yzhanpay_payment`
--
ALTER TABLE `yzhanpay_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `yzhanpay_plan`
--
ALTER TABLE `yzhanpay_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_plugin`
--
ALTER TABLE `yzhanpay_plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `yzhanpay_product`
--
ALTER TABLE `yzhanpay_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_token`
--
ALTER TABLE `yzhanpay_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_trade`
--
ALTER TABLE `yzhanpay_trade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID';

--
-- 使用表AUTO_INCREMENT `yzhanpay_user`
--
ALTER TABLE `yzhanpay_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Increment ID', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
