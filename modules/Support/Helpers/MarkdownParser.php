<?php

namespace Modules\Support\Helpers;

use Parsedown as BaseParsedown;

class MarkdownParser extends BaseParsedown
{

    /**
     * @param array $Element
     *
     * @return null|string
     */
    protected function element(array $Element)
    {
        if (is_array($Element) and is_string($Element['name'])) {

            $method = 'customElement' . ucfirst($Element['name']);

            if (method_exists($this, $method)) {
                $Element = $this->$method($Element);

                if (is_null($Element)) {
                    return null;
                }
            }
        }

        return parent::element($Element);
    }
}