<?php

return [

    'header'  => 'Сообщений',
    'empty'   => [
        'description' => 'Посты не найдены, начните с',
        'action'      => 'добавление нового поста',
    ],
    'search'  => [
        'input' => 'Поиск...',
        'empty' => 'Нет записей, соответствующих заданным критериям поиска.',
    ],
    'details' => [
        'published' => 'опубликованный',
        'draft'     => 'Проект',
        'updated'   => 'обновленный',
        'scheduled' => 'по расписанию',
    ],
    'forms'   => [
        'editor'   => [
            'title'  => 'заглавие',
            'body'   => 'Расскажи свою историю ...',
            'link'   => 'Вставьте или введите ссылку ...',
            'html'   => [
                'label'       => 'Вставить HTML',
                'placeholder' => 'Вставьте свой HTML здесь',
            ],
            'images' => [
                'featured' => [
                    'title'       => 'Подпись к изображению',
                    'placeholder' => 'Добавьте подпись для вашего изображения',
                ],
                'picker'   => [
                    'greeting'    => 'пожалуйста',
                    'action'      => 'загружать',
                    'item'        => 'картинка',
                    'operator'    => 'или же',
                    'unsplash'    => 'поиск Unsplash',
                    'key'         => 'Пожалуйста, настройте ваш ключ Unsplash API.',
                    'placeholder' => 'Поиск бесплатных фотографий в высоком разрешении',
                    'clear'       => [
                        'action'      => 'Удалить',
                        'description' => 'выбранное изображение',
                    ],
                    'caption'     => [
                        'by' => 'фото',
                        'on' => 'на',
                    ],
                    'search'      => [
                        'empty' => 'Мы не смогли найти совпадений.',
                    ],
                    'uploader'    => [
                        'label'   => 'Добавить изображение',
                        'caption' => [
                            'placeholder' => 'Введите подпись к изображению (необязательно)',
                        ],
                        'layout'  => [
                            'default' => 'Макет по умолчанию',
                            'wide'    => 'Широкое изображение',
                        ],
                    ],
                ],
            ],
        ],
        'image'    => [
            'header' => 'Популярное изображение',
        ],
        'publish'  => [
            'header'  => 'Дата публикации (м / д / ч ч: м)',
            'subtext' => [
                'details'  => 'Пост-планирование использует 24-часовой формат времени и использует',
                'timezone' => 'часовой пояс',
            ],
        ],
        'seo'      => [
            'header'    => 'SEO и социальные сети',
            'meta'      => 'Мета Описание',
            'facebook'  => [
                'title'       => [
                    'label'       => 'Название карты Facebook',
                    'placeholder' => 'Заголовок в карточке Facebook',
                ],
                'description' => [
                    'label'       => 'Описание карты Facebook',
                    'placeholder' => 'Описание в карточке Facebook',
                ],
            ],
            'twitter'   => [
                'title'       => [
                    'label'       => 'Название карты в Твиттере',
                    'placeholder' => 'Заголовок в Твиттере',
                ],
                'description' => [
                    'label'       => 'Твиттер Описание карты',
                    'placeholder' => 'Описание в Твиттере',
                ],
            ],
            'canonical' => [
                'label'       => 'канонический',
                'placeholder' => 'Канонический URL оригинального источника',
            ],
        ],
        'settings' => [
            'header'  => 'Общие настройки',
            'slug'    => [
                'label'       => 'слизень',
                'placeholder' => 'а-уникально-пробковый',
            ],
            'summary' => [
                'label'       => 'Резюме',
                'placeholder' => 'Описательное резюме ..',
            ],
            'topic'   => [
                'label' => 'Тема',
            ],
            'tags'    => [
                'label' => 'Теги',
            ],
        ],
    ],
    'delete'  => [
        'header'  => 'удалять',
        'warning' => 'Удаленные сообщения ушли навсегда. Уверены ли вы?',
    ],

];
