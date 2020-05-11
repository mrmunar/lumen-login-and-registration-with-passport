<?php

// Array helpers
function isValidRequiredFields(array $input, array $relevantKeys)
{
    return empty(array_diff($relevantKeys, array_keys($input)));
}

// Class helpers
function isOneOfInstances(object $model, $relevantClasses)
{
    return in_array(get_class($model), $relevantClasses);
}
