-- Пересоздание категорий документов под реальную структуру purehome.ru:
-- Общие · Зорге 9А корпус 1 · Зорге 9А корпус 6 · Зорге 9А корпус 7.
-- Документы заполняются 1:1 со старого сайта (URL-ы файлов сохраняются).

-- Очищаем категории. ON DELETE SET NULL у documents.category_id не повлияет —
-- documents мы тоже очищаем ниже. На свежей установке таблицы и так пусты.
DELETE FROM documents;
DELETE FROM document_categories;

INSERT INTO document_categories(id, name, sort_order) VALUES (1, 'Общие документы',     10);
INSERT INTO document_categories(id, name, sort_order) VALUES (2, 'Зорге 9А, корпус 1',  20);
INSERT INTO document_categories(id, name, sort_order) VALUES (3, 'Зорге 9А, корпус 6',  30);
INSERT INTO document_categories(id, name, sort_order) VALUES (4, 'Зорге 9А, корпус 7',  40);

-- Общие документы --
INSERT INTO documents(category_id, title, file_path, file_size, mime_type, sort_order, created_at) VALUES
(1, 'Уведомление о запрете размещения посторонних предметов в колясочных комнатах', 'uv_27082025.pdf', 85705,  'application/pdf', 10, strftime('%s','now')),
(1, 'Регламент работы сотрудников ЧОП на объекте',                                  '000.docx',        18682,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 20, strftime('%s','now')),
(1, 'Положение о внутриобъектовом и контрольно-пропускном режиме',                  '01.docx',         267227, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 30, strftime('%s','now')),
(1, 'Памятка населению о соблюдении мер пожарной безопасности',                     '02.docx',         61706,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 40, strftime('%s','now')),
(1, 'Информация о праве потребителей обратиться за установкой приборов учёта',      '03.docx',         33701,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 50, strftime('%s','now')),
(1, 'Последствия недопуска исполнителя в помещение для проверки прибора учёта',     '04.docx',         41153,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 60, strftime('%s','now')),
(1, 'Правила обращения с отходами I–IV классов опасности и раздельного сбора',      '05.pdf',          339624, 'application/pdf', 70, strftime('%s','now')),
(1, 'Постановление правительства о предоставлении коммунальных услуг',              '06.pdf',          1873748,'application/pdf', 80, strftime('%s','now')),
(1, 'Что необходимо знать каждому жителю при эвакуации',                            '07.docx',         37545,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 90, strftime('%s','now')),
(1, 'Памятка: оповещение населения о стихийных бедствиях, авариях и катастрофах',   '08.pdf',          399702, 'application/pdf', 100, strftime('%s','now')),
(1, 'Гражданская оборона: порядок действий населения',                              '09.pdf',          926569, 'application/pdf', 110, strftime('%s','now')),
(1, 'Памятка населению по борьбе с терроризмом',                                    '10.pdf',          225721, 'application/pdf', 120, strftime('%s','now')),
(1, 'Бланк заявления',                                                              '11.docx',         38223,  'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 130, strftime('%s','now')),
(1, 'Правила выхода на ремонт',                                                     '12.pdf',          1823315,'application/pdf', 140, strftime('%s','now')),
(1, 'Акт готовности к ремонту',                                                     '13.docx',         238960, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 150, strftime('%s','now')),
(1, 'Положение о предоставлении дополнительных услуг на возмездной основе',         'price.pdf',       461827, 'application/pdf', 160, strftime('%s','now')),
(1, 'Правила оформления пропусков доверенными лицами собственников на строителей',  '14.docx',         246574, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 170, strftime('%s','now')),
(1, 'Образец доверенности для доверенных лиц на время проведения работ',            '15.doc',          99328,  'application/msword', 180, strftime('%s','now')),
(1, 'Заявление на временное отключение пожарной сигнализации для ремонтных работ',  '16.docx',         236974, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 190, strftime('%s','now')),
(1, 'Требования к фасадным вывескам',                                               '17.docx',         1246229,'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 200, strftime('%s','now'));

-- Зорге 9А, корпус 1 --
INSERT INTO documents(category_id, title, file_path, file_size, mime_type, sort_order, created_at) VALUES
(2, 'Отчёт управляющей организации ООО УК «ХОУМ» о выполнении договора управления', 'ddu_2024_k1.pdf', 113751, 'application/pdf', 10, strftime('%s','now')),
(2, 'Протокол внеочередного Общего Собрания Собственников от 06.05.2025 г.',        'pr_itogi.pdf',    238574, 'application/pdf', 20, strftime('%s','now')),
(2, 'Договор управления (2025)',                                                    'du_1.1.pdf',      799113, 'application/pdf', 30, strftime('%s','now')),
(2, 'Сообщение о проведении Общего Собрания Собственников от 11.03.2025',           'OSS_1.pdf',       95474,  'application/pdf', 40, strftime('%s','now')),
(2, 'Протокол Общего Собрания Собственников от 11.12.2023 г.',                      'P_111223.pdf',    778392, 'application/pdf', 50, strftime('%s','now')),
(2, 'Правила проведения отделочных работ',                                          '01.pdf',          22709,  'application/pdf', 60, strftime('%s','now')),
(2, 'Схема проезда в зону погрузки/разгрузки с паркинга',                           'pv.pdf',          2533771,'application/pdf', 70, strftime('%s','now'));

-- Зорге 9А, корпус 6 --
INSERT INTO documents(category_id, title, file_path, file_size, mime_type, sort_order, created_at) VALUES
(3, 'Договор управления',                                                           'du6.pdf',         853944, 'application/pdf', 10, strftime('%s','now')),
(3, 'Протокол Общего Собрания Собственников от 07.02.2025 г.',                      'OSS_2025_6.pdf',  270626, 'application/pdf', 20, strftime('%s','now')),
(3, 'Правила проведения отделочных работ',                                          '067.pdf',         29376,  'application/pdf', 30, strftime('%s','now')),
(3, 'Схема проезда в зону погрузки/разгрузки с паркинга',                           'pv.pdf',          2533771,'application/pdf', 40, strftime('%s','now')),
(3, 'Уведомление о проведении Общего Собрания Собственников',                       'uv_6.pdf',        198158, 'application/pdf', 50, strftime('%s','now'));

-- Зорге 9А, корпус 7 --
INSERT INTO documents(category_id, title, file_path, file_size, mime_type, sort_order, created_at) VALUES
(4, 'Договор управления',                                                           'du7.pdf',         851396, 'application/pdf', 10, strftime('%s','now')),
(4, 'Протокол Общего Собрания Собственников от 07.02.2025 г.',                      'OSS_2025_7.pdf',  251770, 'application/pdf', 20, strftime('%s','now')),
(4, 'Правила проведения отделочных работ',                                          '067.pdf',         29376,  'application/pdf', 30, strftime('%s','now')),
(4, 'Схема проезда в зону погрузки/разгрузки с паркинга',                           'pv.pdf',          2533771,'application/pdf', 40, strftime('%s','now')),
(4, 'Уведомление о проведении Общего Собрания Собственников',                       'uv_7.pdf',        194839, 'application/pdf', 50, strftime('%s','now'));
