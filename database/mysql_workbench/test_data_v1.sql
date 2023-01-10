--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id_users`, `username`, `name`, `surname`, `email`, `password`, `role`, `created`, `deleted`) VALUES
(1, 'AutorUsername' ,'AutorJmeno', 'AutorPrijmeni', NULL, NULL, 0, '2022-12-28 09:45:53', 0),
(2, 'AdminUsername', 'AdminJmeno', 'AdminPrijmeni', NULL, NULL, 0, '2022-12-28 09:46:11', 0),
(3, 'Recenzent1Username', 'RecenzentJmeno1', 'RecenzentPrijmeni', NULL, NULL, 1, '2022-12-28 09:45:53', 0),
(4, 'Recenzent2Username', 'RecenzentJmeno2', 'RecenzentPrijmeni', NULL, NULL, 1, '2022-12-28 09:45:53', 0),
(6, 'Recenzent3Username', 'RecenzentJmeno3', 'RecenzentPrijmeni', NULL, NULL, 1, '2022-12-28 09:45:53', 0);


-- Vypisuji data pro tabulku `contributions`
--

INSERT INTO `contributions` (`id_contributions`, `name`, `abstract`, `id_users`, `state`, `created`, `deleted`) VALUES
(1, 'NazevPrispevek1', 'AbstractPrispevek. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel euismod leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer vel mattis enim. Fusce convallis metus sit amet enim rhoncus eleifend. Curabitur tincidunt, massa sed lacinia tempus, felis sapien semper tellus, eu posuere nisl tellus vitae dui. Vestibulum nec metus nec purus elementum luctus eget in arcu. Sed et consectetur magna. Integer eget eleifend nunc. Curabitur tincidunt nisl eu orci porta, vel dictum nisi imperdiet. Donec mollis, libero ac mollis placerat, velit massa ultricies eros, sed molestie odio risus ac sapien. Aliquam fermentum interdum est eget maximus.', 1, 0, '2022-12-28 09:48:08', 0),
(2, 'NazevPrispevek2', 'AbstractPrispevek. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel euismod leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer vel mattis enim. Fusce convallis metus sit amet enim rhoncus eleifend. Curabitur tincidunt, massa sed lacinia tempus, felis sapien semper tellus, eu posuere nisl tellus vitae dui. Vestibulum nec metus nec purus elementum luctus eget in arcu. Sed et consectetur magna. Integer eget eleifend nunc. Curabitur tincidunt nisl eu orci porta, vel dictum nisi imperdiet. Donec mollis, libero ac mollis placerat, velit massa ultricies eros, sed molestie odio risus ac sapien. Aliquam fermentum interdum est eget maximus.', 1, 1, '2022-12-28 09:48:08', 0),
(3, 'NazevPrispevek3', 'AbstractPrispevek. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel euismod leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer vel mattis enim. Fusce convallis metus sit amet enim rhoncus eleifend. Curabitur tincidunt, massa sed lacinia tempus, felis sapien semper tellus, eu posuere nisl tellus vitae dui. Vestibulum nec metus nec purus elementum luctus eget in arcu. Sed et consectetur magna. Integer eget eleifend nunc. Curabitur tincidunt nisl eu orci porta, vel dictum nisi imperdiet. Donec mollis, libero ac mollis placerat, velit massa ultricies eros, sed molestie odio risus ac sapien. Aliquam fermentum interdum est eget maximus.', 1, 2, '2022-12-28 09:48:08', 0),
(4, 'NazevPrispevek4', 'AbstractPrispevek. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel euismod leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer vel mattis enim. Fusce convallis metus sit amet enim rhoncus eleifend. Curabitur tincidunt, massa sed lacinia tempus, felis sapien semper tellus, eu posuere nisl tellus vitae dui. Vestibulum nec metus nec purus elementum luctus eget in arcu. Sed et consectetur magna. Integer eget eleifend nunc. Curabitur tincidunt nisl eu orci porta, vel dictum nisi imperdiet. Donec mollis, libero ac mollis placerat, velit massa ultricies eros, sed molestie odio risus ac sapien. Aliquam fermentum interdum est eget maximus.', 1, 0, '2022-12-28 09:48:08', 0);

--
-- Vypisuji data pro tabulku `reviews_assignments`
--

INSERT INTO `reviews_assignments` (`id_reviews_assignments`, `id_users`, `id_contributions`, `state`, `created`, `deleted`) VALUES
(1, 3, 1, 1, '2022-12-28 09:54:35', 0),
(2, 4, 1, 1, '2022-12-28 09:54:35', 0),
(3, 6, 1, 1, '2022-12-28 09:54:35', 0),
(4, 3, 2, 0, '2022-12-28 09:54:35', 0),
(5, 4, 2, 0, '2022-12-28 09:54:35', 0),
(6, 6, 2, 0, '2022-12-28 09:54:35', 0),
(7, 3, 3, 0, '2022-12-28 09:54:35', 0),
(8, 4, 3, 0, '2022-12-28 09:54:35', 0),
(9, 6, 3, 0, '2022-12-28 09:54:35', 0);


--
-- Vypisuji data pro tabulku `reviews`
--

INSERT INTO `reviews` (`id_reviews`, `id_users`, `id_contributions`, `abstract_review`, `topic_review`, `author_review`, `comment`, `type`, `created`, `deleted`) VALUES
(1, 3, 1, 0, 0, 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel euismod leo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer vel mattis enim. Fusce convallis metus sit amet enim rhoncus eleifend. Curabitur tincidunt, massa sed lacinia tempus, felis sapien semper tellus, eu posuere nisl tellus vitae dui. Vestibulum nec metus nec purus elementum luctus eget in arcu. Sed et consectetur magna. Integer eget eleifend nunc. Curabitur tincidunt nisl eu orci porta, vel dictum nisi imperdiet. Donec mollis, libero ac mollis placerat, velit massa ultricies eros, sed molestie odio risus ac sapien. Aliquam fermentum interdum est eget maximus.', 0, '2022-12-28 09:59:46', 0);



