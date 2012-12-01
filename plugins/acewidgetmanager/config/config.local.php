<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Block Manager for LiveStreet/ACE
 * @Version:
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version:
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/***
 * Сброс блоков - сбрасывать/не сбрасывать установку блоков, которая
 * задается во actions
 *
 * Например:
 *   $config['clear'] = true; // Сбросить все блоки
 *
 *   $config['clear'] = false; // Не сбрасывать блоки
 *
 *   $config['clear'] = array('on'=>'index,blog');
 *     // Cбрасывать блоки, которые заданы в actions index и blog
 *
 *   $config['clear'] = array('off'=>'admin, people, profile');
 *     // Cбрасывать блоки во всех actions, кроме admin и profile
 *
 * Формат задаваемых путей:
 *      admin       - только для пути 'admin'
 *      admin/users - только для пути 'admin/users'
 *      admin/*     - для всех путей, начинающихся с 'admin/'
 */

$config['clear'] = false;

/**
 * Описание виджета (блока)
 *
 * $config['widgets'][] = array(
 *  'name' => 'bloc_name',      // название блока (прежнее название 'block')
 *  'group' => 'right',         // расположение блока (прежнее название 'position')
 *  'priority' => 1000,         // приоритет блока - блоки с бОльшим приоритетом выводятся выше других
 *                              //      возможны либо числа, либо строковое значение 'top'
 *                              //      если задано 'top', то выводится выше всех
 *  'params' => array(
 *      'plugin' => 'acewidgetmanager',  // указывает, что это блок плагина
 *      'other_parameter' => 123,       // остальные параметры могут быть какие угодно
 *      'yet_another_parameter' => 'abc',   // ...
 *  ),
 *  'on'=>array('admin/*'),    // опции include и exclude такие же, как и для $config['clear']
 *  'off'=>array('admin/plugins/*', 'settings/*'),
 *  'display' => 0, // показывать блок или нет, значения:
 *                  //      true или 1 - выводить блок
 *                  //      false или 0 - не выводить блок,
 *                  // array('date_from'=>'2011-10-10', 'date_upto'=>'2011-10-20') - выводить с... по...
 *  'condition' => 'any_boolean_condition', // php-выражение (в виде строки), которое вычисляется при инициализации блоков
 *                                          // если результат true, то блок выводится, иначе - нет
 *
 */

$config['widgets'][] = array(
    'name' => 'usersonline', // название блока
    'group' => 'right', // позиционирование блока
    'priority' => 1000, // приоритет блока (блоки с бОльшим приоритетом выводятся выше других)
    'params' => array(
        'plugin' => 'acewidgetmanager', //
        'users_max' => 20, // Максимальное число отображаемых пользователей
        'users_period' => 600, // Время, в течение которого пользователь считается в онлайне (сек)
        'renew_time' => 100, // Время обновления (сек). Если = 0, то без автообновления
        'show_last_time' => true, // Показывать время последней активности юзера
                                  // true - показывать
                                  // false - не показывать
        'show_avatar' => 0, // Показывать аватар юзера. Задается размер аватары. Если = 0, то не показывается аватара
        'show_username' => true, // Показывать имя пользователя (если имя юзером не задано, то показывается его логин)
        'show_login_only' => false, // Показывать только логин, даже если юзер задал свое имя (имеет смысл, только если задан предыдущий параметр)
        'show_compact_mode' => false, // Выводить пользователей в компактном режиме (не списком)
    ),
    'js' => '___/js/usersonline.js',
    'on'=>'/,blog/*,personal_blog/*' ,
    //'off'=>array('admin/*', 'settings/*', 'profile/*', 'talk/*', 'people/*'),
    'display' => 1,  // true - выводить блок, false - не выводить блок,
                        // array('date_from'=>'2011-10-10', 'date_upto'=>'2011-10-20') - выводить с... по...
);

/**
 * ВНИМАНИЕ!!!
 *
 * Следующие два блока добавлены исключительно в демонстрационных целях!
 */
$config['widgets'][] = array(
    'name' => 'template', // название блока
    'group' => 'right', // позиционирование блока
    'priority' => 100,
    'params' => array(
        'plugin' => 'acewidgetmanager', //
        'title' => 'Title of template block',
        'text' => 'text1 text1 text1 ',
        'footer' => 'footer1',
    ),
    //'off'=>array('admin/*', 'settings/*', 'profile/*', 'talk/*', 'people/*'),
);

$config['widgets'][] = array(
    'name' => 'template', // название блока
    'group' => 'right', // позиционирование блока
    'priority' => 0,
    'params' => array(
        'plugin' => 'acewidgetmanager', //
        'title' => 'Block with file content',
        'file' => 'demo-js.txt',
    ),
    'condition' => '$this->User_GetUserCurrent()', // этот блок будет выводиться только для зарегистрированных пользователей
);

return $config;

// EOF