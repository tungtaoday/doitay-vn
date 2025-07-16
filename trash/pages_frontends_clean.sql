-- PAGES TABLE DATA --
DELETE FROM pages;
INSERT INTO pages (id, name, slug, tempname, secs, seo_content, is_default, created_at, updated_at) VALUES (1, 'HOME', '/', 'templates.basic.', '\"[\\\"category\\\",\\\"choose_reason\\\",\\\"review\\\",\\\"cta\\\",\\\"testimonial\\\",\\\"faq\\\",\\\"blog\\\"]\"', '[]', 1, '2020-07-11 06:23:58', '2022-10-25 11:10:54');
INSERT INTO pages (id, name, slug, tempname, secs, seo_content, is_default, created_at, updated_at) VALUES (4, 'Blog', 'blog', 'templates.basic.', '[]', '[]', 1, '2020-10-22 01:14:43', '2020-10-22 01:14:43');
INSERT INTO pages (id, name, slug, tempname, secs, seo_content, is_default, created_at, updated_at) VALUES (5, 'Contact', 'contact', 'templates.basic.', '[]', '[]', 1, '2020-10-22 01:14:53', '2022-10-25 11:10:39');
INSERT INTO pages (id, name, slug, tempname, secs, seo_content, is_default, created_at, updated_at) VALUES (19, 'Review', 'review', 'templates.basic.', '\"[\\\"review\\\"]\"', '[]', 0, '2022-11-14 06:47:46', '2022-11-14 06:47:58');

-- FRONTENDS TABLE DATA --
DELETE FROM frontends;
INSERT INTO frontends (id, data_keys, data_values, seo_content, tempname, slug, created_at, updated_at) VALUES (1, 'banner.content', '{\"heading\":\"Doitay\",\"subheading\":\"N\\u1ec1n t\\u1ea3ng k\\u1ebft n\\u1ed1i th\\u1ee3\",\"image\":\"684429740b3a01749297524.jpg\"}', '[]', 'basic', '', '2025-06-07 18:46:03', '2025-06-07 18:58:44');
INSERT INTO frontends (id, data_keys, data_values, seo_content, tempname, slug, created_at, updated_at) VALUES (2, 'banner.content', '{\"heading\":\"Doitay\",\"subheading\":\"N\\u1ec1n t\\u1ea3ng k\\u1ebft n\\u1ed1i th\\u1ee3\",\"image\":\"6844267ddeb071749296765.png\"}', '[]', 'basic', '', '2025-06-07 18:46:05', '2025-06-07 18:46:11');
INSERT INTO frontends (id, data_keys, data_values, seo_content, tempname, slug, created_at, updated_at) VALUES (3, 'seo.data', '{\"keywords\":[],\"description\":\"\",\"social_title\":\"\",\"social_description\":\"\",\"image\":null}', '[]', '', '', '2025-06-07 21:37:35', '2025-06-07 21:37:35');
INSERT INTO frontends (id, data_keys, data_values, seo_content, tempname, slug, created_at, updated_at) VALUES (4, 'breadcrumb.content', '{\"image\":\"6844af5bd27f21749331803.jpg\"}', '[]', 'basic', 'default-slug', '2025-06-08 04:26:51', '2025-06-08 04:30:03');
INSERT INTO frontends (id, data_keys, data_values, seo_content, tempname, slug, created_at, updated_at) VALUES (5, 'testimonial.element', '{\"name\":\"Ch\\u1ecb H\\u01b0\\u01a1ng\",\"address\":\"C\\u1ea7u Gi\\u1ea5y - H\\u00e0 N\\u1ed9i\",\"quote\":\"\\\"T\\u00ecm \\u0111\\u01b0\\u1ee3c th\\u1ee3 s\\u1eeda \\u0111i\\u1ec7n n\\u01b0\\u1edbc r\\u1ea5t nhanh, gi\\u00e1 c\\u1ea3 h\\u1ee3p l\\u00fd. S\\u1ebd d\\u00f9ng l\\u1ea1i!\\\"\",\"image\":\"68476e68a21e31749511784.jpg\"}', '[]', 'basic', 'default-slug', '2025-06-10 06:29:44', '2025-06-10 06:29:49');

-- EXPORT COMPLETED -- 