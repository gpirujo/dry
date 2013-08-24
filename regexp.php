<?php

# function that generates a regular expression to match a set of strings
function re_for_set($elements, $options = null) {

    $elements = array_unique($elements);
    sort($elements);

    $options = array_merge(array(
        'non_capturing' => true,
    ), (array) $options);

    return recurse(function($self, $elements) use($options) {

        $forward = array();
        foreach ($elements as $element) {

            $char = (string) substr($element, 0, 1);

            if (!isset($forward[$char])) {
                $forward[$char] = array();
            }
            $forward[$char][] = (string) substr($element, 1);

        }

        $backward = array();
        foreach ($forward as $char => $rest) {

            if ($null = in_array('', $rest)) {
                $rest = array_diff($rest, array(''));
            }

            if (count($rest)) {
                $rest = $self($rest);
                if ($null) {
                    $rest = '(' . $rest . ')?';
                }
            } else {
                $rest = '';
            }

            if (!isset($backward[$rest])) {
                $backward[$rest] = array();
            }
            $backward[$rest][] = $char;

        }

        $re = array();
        foreach ($backward as $rest => $set) {

            $list = '';
            $count = count($set);
            $i = 0;
            while ($i < $count) {

                $j = 0;
                while ($i + $j + 1 < $count && ord($set[$i + $j + 1]) == ord($set[$i]) + $j + 1) {
                    $j++;
                }

                if ($j >= 3) {
                    $list .= preg_quote($set[$i]) . '-' . preg_quote($set[$i + $j]);
                } else {
                    $list .= preg_quote(implode('', array_slice($set, $i, $j + 1)));
                }

                $i += $j + 1;

            }
            if ($count > 1) $list = '[' . $list . ']';

            $re[] = $list . $rest;

        }

        $list = implode('|', $re);
        if (count($re) > 1) $list = '(' . ($options['non_capturing'] ? '?:' : '') . $list . ')';

        return $list;

    }, $elements);

}
