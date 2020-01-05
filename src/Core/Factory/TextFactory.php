<?php

namespace Pool\Core\Factory;

use Pool\Core\Object\Text;

class TextFactory
{
    /**
     * Create a `Text` object
     * 
     * @param array $data
     * @return Text
     */
    public static function create(array $data = []): Text
    {
        $text = new Text();

        $text->setId($data['id'] ?? null);
        $text->setText($data['text'] ?? '');

        return $text;
    }
}