<?php

namespace garmayev\max;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        // Регистрация компонентов, модулей и т.д.
        if (!$app->has('max')) {
            $app->set('max', [
                'class' => 'garmayev\max\components\MaxComponent',
            ]);
        }
    }
}