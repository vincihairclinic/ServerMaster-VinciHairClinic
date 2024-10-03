<?php

namespace App\Repositories\Base;

class XmlRepository
{
    static function xmlElement(\XMLWriter &$xml, $element, $text)
    {
        $xml->startElement($element);
        $xml->text($text);
        $xml->endElement();
    }

    static function xmlOption(\XMLWriter &$xml, $option_name, $option_values = [])
    {
        $xml->startElement('option');

        $xml->startElement('option_name');
        $xml->text($option_name);
        $xml->endElement();

        foreach ($option_values as $option_value){
            $xml->startElement('option_value');
            $xml->text($option_value);
            $xml->endElement();
        }

        $xml->endElement();
    }

}