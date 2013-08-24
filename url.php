<?php

# disassemble an url, call back with the parts, receive them back, assemble and return
function edit_url($url, $function) {
    $url = call_user_func($function, parse_url($url));
    return
         ((isset($url['scheme'])) ? $url['scheme'] . '://' : '')
        .((isset($url['user'])) ? $url['user'] . ((isset($url['pass'])) ? ':' . $url['pass'] : '') .'@' : '')
        .((isset($url['host'])) ? $url['host'] : '')
        .((isset($url['port'])) ? ':' . $url['port'] : '')
        .((isset($url['path'])) ? $url['path'] : '')
        .((isset($url['query'])) ? '?' . $url['query'] : '')
        .((isset($url['fragment'])) ? '#' . $url['fragment'] : '')
    ;
}

# disassemble an url, call back with its params, receive them back, assemble and return
function edit_url_params($url, $function) {
    return edit_url($url, function($url) use($function) {
        parse_str($url['query'], $params);
        $url['query'] = http_build_query(call_user_func($function, $params));
        if (!strlen($url['query'])) unset($url['query']);
        return $url;
    });
}
