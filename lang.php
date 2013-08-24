<?php

# function to wrap code to run in local scope
#
# $x = 'global';
# $y = local(function($arg) {
#   $x = 'local';
#   return $arg
# }, 'passed');
#
# $x still equals 'global'
# $y equals 'passed'
#
function local($function) {
    return call_user_func_array($function, array_slice(func_get_args(), 1));
}

# function to make anonymous inline recursive functions
#
# $fact = recurse(function($self, $n) {
#   if ($n < 3) {
#     return $n;
#   } else {
#     return $n * $self($n - 1);
#   }
# }, 4);
#
# $fact equals 24
#
function recurse($function) {
    return call_user_func_array($function, array_merge(array(function() use($function) {
        return call_user_func_array('recurse', array_merge(array($function), func_get_args()));
    }), array_slice(func_get_args(), 1)));
}

# check that a variable is set and, if not, set it
#
# $x == null
# check($x, function($arg) {
#   return $arg;
# }, 'first');
# now $x == 'first'
# check($x, 'second');
# still $x == 'first'
#
function check(&$variable, $value_or_function) {
    if (!isset($variable) and is_callable($variable = $value_or_function)) {
        $variable = call_user_func_array($variable, array_slice(func_get_args(), 2));
    }
    return $variable;
}
