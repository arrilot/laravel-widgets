<?php

function javascript_data_stub($widgetName, $widgetParams = [], $id = 1)
{
    return json_encode([
        'id'     => $id,
        'name'   => $widgetName,
        'params' => serialize($widgetParams),
        '_token' => 'token_stub',
    ]);
}