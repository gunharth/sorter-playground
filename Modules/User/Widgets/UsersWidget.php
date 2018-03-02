<?php

namespace Modules\User\Widgets;

use Modules\Dashboard\Foundation\Widgets\BaseWidget;

class UsersWidget extends BaseWidget
{

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'UserWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'user::widgets.hello';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        return ['userText' => 'Hello User Widget'];
    }

    /**
     * Get the widget type
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '2',
            'height' => '2',
            'x' => '0',
        ];
    }
}
