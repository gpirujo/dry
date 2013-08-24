<?php

# function to wrap a critical section
function critical_section($key, $function) {
    $id = is_int($key) ? $key : crc32($key);
    $sem = sem_get($id, 1);
    if (sem_acquire($sem)) {
        $result = call_user_func_array($function, array_slice(func_get_args(), 2));
        sem_release($sem);
        return $result;
    } else {
        throw new Exception('Could not acquire semaphore ' . $key);
    }
}
