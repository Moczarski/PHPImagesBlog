CREATE TABLE `images` (
  `id` int(3) NOT NULL,
  `url` varchar(255) NOT NULL,
  `text` varchar(40) NOT NULL,
  `username` varchar(20) NOT NULL,
  `points` int(3) NOT NULL
);

ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `images`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

GRANT USAGE ON *.* TO `admin`@`localhost` IDENTIFIED BY PASSWORD '*4ACFE3202A5FF5CF467898FC58AAB1D615029441'; --admin (haslo)

GRANT SELECT, INSERT, UPDATE, DELETE ON `moczarskibd`.* TO `admin`@`localhost`;

GRANT USAGE ON *.* TO `mateusz`@`localhost` IDENTIFIED BY PASSWORD '*3A29FD62701CAF57B0751E6B3C404FD600DC6BD5'; --moczarski (haslo)

GRANT SELECT, INSERT, UPDATE, DELETE ON `moczarskibd`.* TO `mateusz`@`localhost`;